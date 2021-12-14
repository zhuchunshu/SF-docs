<?php

namespace App\Plugins\Docs\src\Controllers;

use App\Plugins\Core\src\Handler\UploadHandler;
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
}