@extends('Core::app')
@section('title','创建文档')
@section('content')

    <div class="row row-cards justify-content-center">
        <div class="col-md-10">
            <div class="border-0 card card-body">
                <h3 class="card-title">创建文档</h3>
                <form action="/docs/create.class?Redirect=/docs/create.class" method="post" enctype="multipart/form-data">
                    <x-csrf/>
                    <div class="mb-3">
                        <label class="form-label">文档名</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">图标</label>
                        <input type="file" accept="image/gif, image/png, image/jpeg, image/jpg" class="form-control" name="icon">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">哪些用户组可看?</label>
                        <select name="userClass[]" id="" class="form-select" multiple>
                            @foreach($userClass as $data)
                                <option value="{{$data->id}}">{{$data->name}} 「权限值:{{$data['permission-value']}}」</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('header')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            Overview
                        </div>
                        <h2 class="page-title">
                            创建文档
                        </h2>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection