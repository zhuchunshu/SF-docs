<?php

namespace App\Plugins\Docs\src\Controllers;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

#[Controller(prefix:"/admin/docs")]
class AdminController
{
    #[GetMapping(path:"")]
    public function index(){
        return view("Docs::admin.index");
    }
}