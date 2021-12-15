<?php

namespace App\Plugins\Docs\src\Controllers;

use App\Plugins\Core\src\Handler\UploadHandler;
use App\Plugins\Docs\src\Model\Docs;
use App\Plugins\Docs\src\Model\DocsClass;
use App\Plugins\Docs\src\Request\CreateClassRequest;
use App\Plugins\User\src\Models\UserClass;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix:"/docs")]
class IndexController
{
    #[GetMapping(path:"")]
    public function index(): \Psr\Http\Message\ResponseInterface
    {
        $page = DocsClass::query()->with("user")->paginate(15);
        return view("Docs::index",['page' => $page]);
    }

    #[GetMapping(path:"create.class")]
    public function create_class(){
        if(!Authority()->check("docs_create")){
            return admin_abort('你所在的用户组无权创建文档',401);
        }
        $userClass = UserClass::query()->select('id','name','permission-value')->get();
        return view("Docs::create_class",['userClass' => $userClass]);
    }

    #[PostMapping(path:"create.class")]
    public function create_class_submit(CreateClassRequest $request,UploadHandler $uploader){
        if(!Authority()->check("docs_create")){
            return redirect()->back()->with("danger",'你所在的用户组无权创建文档')->go();
        }
        $path = $uploader->save($request->file("icon"),auth()->id())['path'];
        $quanxian = json_encode($request->input('userClass'));
        DocsClass::query()->create([
            'name' => $request->input('name'),
            'icon' => $path,
            'user_id' => auth()->id(),
            'quanxian' => $quanxian
        ]);
        return redirect()->url('/docs')->with("success",'创建成功!')->go();
    }

    #[GetMapping(path:"create/{id}")]
    public function create($id){
        if(!DocsClass::query()->where('id',$id)->exists()){
            return admin_abort("页面不存在",404);
        }
        $user_id = (int)DocsClass::query()->where('id',$id)->first()->user_id;
        if(auth()->id()!==$user_id || !Authority()->check("docs_create")){
            return admin_abort("无权限",401);
        }
        $data = DocsClass::query()->where('id',$id)->first();
        return view("Docs::create",['data' => $data]);
    }

    #[GetMapping(path:"/docs/{id}")]
    public function show($id){
        if(!DocsClass::query()->where('id',$id)->exists()){
            return admin_abort('页面不存在',404);
        }
        $data = DocsClass::query()->where('id',$id)->first();
        $page = Docs::query()->where("class_id",$id)->with("user")->paginate(15);
        return view("Docs::show",['data' => $data,'page' => $page]);
    }

}