@extends('Core::app')
@section('title','「'.$data->name.'」的文档信息')
@section('content')

    <div class="row row-cards justify-content-center">
        <div class="col-md-10" id="docs-app">
            <div class="row row-cards justify-content-center">
                <div class="col-md-7">
                    <div class="row row-cards justify-content-center">
                        @if($page->count())
                            @foreach($page as $value)
                                <div class="col-md-12">
                                    <div class="border-0 card card-body">
                                        <div class="row">
                                            <div class="col col-auto">
                                                <span class="avatar" style="background-image: url('{{$data->icon}}')"></span>
                                            </div>
                                            <div class="col">
                                                <a href="/docs/{{$data->id}}/{{$value->id}}.html" style="font-size: 18px; font-weight: bold;" class="text-black text-reset">
                                                    {{$value->title}}
                                                </a>
                                                <br>
                                                <span class="text-reset">由{{$value->user->username}}发表于: <span>{{format_date($value->created_at)}}</span> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="border-0 card card-body">
                                    <h3 class="card-title">暂无内容</h3>
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
                                                {{$data->name}}
                                            </h3>
                                            <p>
                                                创建于:{{format_date($data->created_at)}}
                                            </p>
                                        </div>
                                        <div class="card-footer" id="vue-docs-class-show-footer">
                                            @if(auth()->check())
                                                @if(auth()->id()===(int)$data->user_id && Authority()->check("docs_create"))
                                                    <a href="/docs/create/{{$data->id}}" class="btn btn-dark">发布文档</a>
                                                @else
                                                    <button class="btn btn-dark"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ban" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="12" r="9"></circle>
                                                            <line x1="5.7" y1="5.7" x2="18.3" y2="18.3"></line>
                                                        </svg>无权发布</button>
                                                @endif
                                                    @if(auth()->id()===(int)$data->user_id && Authority()->check("docs_edit"))
                                                        <a href="/docs/editClass/{{$data->id}}" class="btn btn-dark">修改文档</a>
                                                        @elseif(Authority()->check("admin_docs_edit"))
                                                        <a href="/docs/editClass/{{$data->id}}" class="btn btn-dark">修改文档</a>
                                                    @endif
                                                    @if(auth()->id()===(int)$data->user_id && Authority()->check("docs_delete"))
                                                        <button class="btn btn-dark" @@click="docs_delete_class">删除此分类</button>
                                                    @elseif(Authority()->check("admin_docs_delete"))
                                                        <button class="btn btn-dark" @@click="docs_delete_class">删除此分类</button>
                                                    @endif
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

@section('scripts')
    <script>var docs_class_id = {{$data->id}};</script>
    <script src="{{file_hash("plugins/Docs/js/docs.js")}}"></script>
@endsection