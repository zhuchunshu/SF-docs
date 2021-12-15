<?php

namespace App\Plugins\Docs\src\Controllers;

use App\Plugins\Docs\src\Model\Docs;
use App\Plugins\Docs\src\Model\DocsClass;
use App\Plugins\Docs\src\Request\CreateDocsRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix:"/api/docs")]
class ApiController
{
    #[PostMapping(path:"getDocs")]
    public function getDocs(){
        $class_id = request()->input('id');
        if(!$class_id){
            return Json_Api(403,false,['msg'=>'请求参数不足,缺少:id']);
        }
        if(!DocsClass::query()->where('id',$class_id)->exists()){
            return Json_Api(403,false,['msg'=>'ID为'.$class_id.'的文档不存在']);
        }
        if(!Docs::query()->where("class_id",$class_id)->exists()){
            return Json_Api(403,false,['msg'=>'ID为'.$class_id.'分类下的文档不存在']);
        }
        $classData = DocsClass::query()->where('id',$class_id)->with('user')->first();
        $page = Docs::query()->where("class_id",$class_id)->get();
        return Json_Api(200,true,['msg' => '加载成功!','classData' => $classData,'docs' => $page]);
    }

    // 发布文档
    #[PostMapping(path:"create")]
    public function create(CreateDocsRequest $request){
        $title = $request->input("title");
        $class_id = $request->input("class_id");
        $user_id = (int)DocsClass::query()->where('id',$class_id)->first()->user_id;
        if(auth()->id()!==$user_id || !Authority()->check("docs_create")){
            return Json_Api(403,false,['无权限!']);
        }
        $markdown = $request->input("markdown");
        $html = $request->input("html");
        $html = xss()->clean($html);
        // 解析shortCode
        $html = ShortCode()->handle($html);
        // 解析标签
        $html = $this->tag($html);
        // 解析艾特
        $html = $this->at($html);
        Docs::query()->create([
            'user_id' => auth()->id(),
            'class_id' => $class_id,
            'title' => $title,
            'content' => $html,
            'markdown' => $markdown
        ]);
        return Json_Api(200,true,['发布成功!']);
    }
    public function tag(string $html): string
    {
        return replace_all_keywords($html);
    }

    public function at(string $html): string
    {
        return replace_all_at($html);
    }
}