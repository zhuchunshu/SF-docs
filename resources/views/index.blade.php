@extends('Core::app')
@section('title','本站文档')
@section('content')

    <div class="row row-cards justify-content-center">
        <div class="col-md-10">
            <div class="row row-cards justify-content-center">
                <div class="col-md-7">
                    <div class="row row-cards justify-content-center">
                        @if($page->count())
                            @foreach($page as $data)
                                <div class="col-md-12">
                                    <div class="border-0 card card-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col col-auto">
                                                    <a href="/docs/{{$data->id}}"><span class="avatar" style="background-image:url({{$data->icon}})"></span></a>
                                                </div>
                                                <div class="col">
                                                    <a href="/docs/{{$data->id}}" class="card-title text-reset" style="font-size:18px;font-weight: bold">{{$data->name}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            由 <a href="/users/{{$data->user->username}}.html">{{$data->user->username}}</a> 创建于: <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{$data->created_at}}">{{format_date($data->created_at)}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="border-0 card card-body">
                                    <div class="text-center card-title">暂无内容</div>
                                </div>
                            </div>
                        @endif
                        {!! make_page($page) !!}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row row-cards rd">
                        <div class="col-md-12 sticky" style="top: 105px">
                            <div class="row row-cards">
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-status-top bg-primary"></div>
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                {{get_options("web_name")}}
                                            </h3>
                                            <p>
                                                {{get_options("description","无描述")}}
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            @if(auth()->check())
                                                <a href="/docs/create.class" class="btn btn-dark">创建文档</a>
                                            @else
                                                <a href="/login" class="btn btn-dark">登陆</a>
                                                <a href="/register" class="btn btn-light">注册</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @foreach(Itf()->get("index_right") as $value)
                                    @include($value)
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection