<?php

namespace App\Plugins\Docs\src\Controllers;

use App\Plugins\Core\src\Handler\UploadHandler;
use App\Plugins\Docs\src\Model\Docs;
use App\Plugins\Docs\src\Model\DocsClass;
use App\Plugins\Docs\src\Request\CreateClassRequest;
use App\Plugins\Docs\src\Request\EditClassRequest;
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

    #[GetMapping(path:"edit/{id}")]
    public function edit($id)
    {
        if (!Docs::query()->where('id', $id)->exists()) {
            return admin_abort("页面不存在", 404);
        }
        $user_id = (int)Docs::query()->where('id', $id)->first()->user_id;
        $quanxian = false;
        if (auth()->id() === $user_id || Authority()->check("docs_edit")) {
            $quanxian = true;
        }

        if (Authority()->check("admin_docs_edit")) {
            $quanxian = true;
        }

        if (!$quanxian) {
            return admin_abort("无权限", 401);
        }
        $data = Docs::query()->where('id',$id)->first();
        return view("Docs::edit",['data' => $data]);
    }

    #[GetMapping(path:"editClass/{id}")]
    public function edit_class($id){
        if(!DocsClass::query()->where('id',$id)->exists()){
            return admin_abort("页面不存在",404);
        }
        $user_id = (int)DocsClass::query()->where('id',$id)->first()->user_id;
        $quanxian = false;
        if (auth()->id()===$user_id || Authority()->check("docs_edit")) {
            $quanxian = true;
        }

        if(Authority()->check("admin_docs_edit")) {
            $quanxian = true;
        }

        if(!$quanxian){
            return admin_abort("无权限",401);
        }

        $userClass = UserClass::query()->select('id','name','permission-value')->get();
        $data = DocsClass::query()->where('id',$id)->first();
        return view("Docs::edit_class",['data' => $data,'userClass' => $userClass]);
    }

    #[PostMapping(path:"editClass")]
    public function edit_class_submit(EditClassRequest $request, UploadHandler $uploader){
        $user_id = (int)DocsClass::query()->where('id',$request->input('class_id'))->first()->user_id;
        $quanxian = false;
        if (auth()->id()===$user_id || Authority()->check("docs_edit")) {
            $quanxian = true;
        }

        if(Authority()->check("admin_docs_edit")) {
            $quanxian = true;
        }
        if(!$quanxian){
            return redirect()->back()->with("danger",'你所在的用户组无权修改文档')->go();
        }
        $path = $request->hasFile('icon');
        if(!$path){
            $path = DocsClass::query()->where("id",$request->input("class_id"))->first()->icon;
        }else{
            $path = $uploader->save($request->file("icon"),auth()->id())['path'];
        }
        $quanxian = json_encode($request->input('userClass'));
        DocsClass::query()->where("id",$request->input("class_id"))->update([
            'name' => $request->input('name'),
            'icon' => $path,
            'user_id' => auth()->id(),
            'quanxian' => $quanxian
        ]);
        return redirect()->back()->with("success",'修改成功!')->go();
    }

    #[GetMapping(path:"{id}")]
    public function show($id){
        if(!DocsClass::query()->where('id',$id)->exists()){
            return admin_abort('页面不存在',404);
        }
        $data = DocsClass::query()->where('id',$id)->first();
        $quanxian = false;
        $arr = json_decode($data->quanxian, true, 512, JSON_THROW_ON_ERROR);
        if(in_array(auth()->data()->class_id, $arr)){
            $quanxian = true;
        }

        if((int)$data->user_id === auth()->id()){
            $quanxian = true;
        }

        $p_quanxian = true;
        foreach (UserClass::query()->get() as $value){
            if(!in_array((int)$value->id, $arr)){
                $p_quanxian = false;
            }
        }

        if(!$quanxian && !$p_quanxian){
            return admin_abort('无权查看',401);
        }
        $page = Docs::query()->where("class_id",$id)->with("user")->paginate(15);
        return view("Docs::show",['data' => $data,'page' => $page]);
    }

    #[GetMapping(path:"/docs/{class_id}/{id}.html")]
    public function showDocs($class_id,$id){
        if(!DocsClass::query()->where('id',$class_id)->exists()){
            return admin_abort('页面不存在',404);
        }
        $data = DocsClass::query()->where('id',$class_id)->first();
        $quanxian = false;
        $arr = json_decode($data->quanxian, true, 512, JSON_THROW_ON_ERROR);
        if(in_array(auth()->data()->class_id,$arr)){
            $quanxian = true;
        }
        if((int)$data->user_id === auth()->id()){
            $quanxian = true;
        }
        $p_quanxian = true;
        foreach (UserClass::query()->get() as $value){
            if(!in_array((int)$value->id, $arr)){
                $p_quanxian = false;
            }
        }
        if(!$quanxian && !$p_quanxian){
            return admin_abort('无权查看',401);
        }

        if(!Docs::query()->where(['id' => $id,'class_id' => $class_id])->exists()){
            return admin_abort('页面不存在',404);
        }
        $data = Docs::query(true)->where('id',$id)->with(['user','docsClass'])->first();
        return view("Docs::showDocs",['data' => $data]);
    }


}