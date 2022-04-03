@extends('App::app')
@section('title','「'.$data->title.'」的文档内容')
@section('content')

    <div class="row row-cards justify-content-center">
        <div class="col-md-12" id="docs-app">
            <div class="row row-cards justify-content-center">
                <div class="col-md-9">
                    <div class="row row-cards justify-content-center">
                        <div class="border-0 card">
                            <div class="card-body topic">

                                <div class="row">
                                    <div class="col-md-12" id="title">
                                        <h1 data-bs-toggle="tooltip" data-bs-placement="left" title="文档标题">

                                            {{ $data->title }}
                                        </h1>
                                    </div>
                                    <div class="col-md-12">
                                        <ol class="breadcrumb" aria-label="breadcrumbs">

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="{{$data->created_at}}" class="breadcrumb-item active" aria-current="page"><a  href="#">
                                                    发布于:{{format_date($data->created_at)}}
                                                </a>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="{{$data->updated_at}}" class="breadcrumb-item active" aria-current="page"><a href="#">
                                                    更新于:{{format_date($data->updated_at)}}
                                                </a>
                                            </li>
                                            @if(auth()->check())
                                                @if((int)$data->user_id===auth()->id() && Authority()->check("docs_delete"))
                                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="删除此文档" class="breadcrumb-item">
                                                        <a class="cursor-pointer" style="text-decoration: none" @@click="docs_delete({{$data->id}})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                            </svg>
                                                            删除
                                                        </a>
                                                    </li>
                                                @elseif(Authority()->check("admin_docs_delete"))
                                                    <li data-bs-toggle="tooltip" data-bs-placement="top" title="删除此文档" class="breadcrumb-item">
                                                        <a class="cursor-pointer" style="text-decoration: none" @@click="docs_delete({{$data->id}})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                            </svg>
                                                            删除
                                                        </a>
                                                    </li>
                                                @endif

                                                    @if((int)$data->user_id===auth()->id() && Authority()->check("docs_edit"))
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="修改此文档" class="breadcrumb-item">
                                                            <a class="cursor-pointer" style="text-decoration: none" href="/docs/edit/{{$data->id}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                                                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                                                    <line x1="16" y1="5" x2="19" y2="8"></line>
                                                                </svg>
                                                                修改
                                                            </a>
                                                        </li>
                                                    @elseif(Authority()->check("admin_docs_edit"))
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="修改此文档" class="breadcrumb-item">
                                                            <a class="cursor-pointer" style="text-decoration: none" href="/docs/edit/{{$data->id}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                                                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                                                    <line x1="16" y1="5" x2="19" y2="8"></line>
                                                                </svg>
                                                                修改
                                                            </a>
                                                        </li>
                                                    @endif
                                            @endif
                                        </ol>


                                    </div>
                                    <hr class="hr-text" style="margin-top: 5px;margin-bottom: 5px">
                                    <div class="col-md-12" id="author">
                                        <div class="row">
                                            <div class="col-auto">
                                                <a class="avatar" href="/users/{{ $data->user->username }}.html"
                                                   style="background-image: url({{ super_avatar($data->user) }})"></a>
                                            </div>
                                            <div class="col">
                                                <div class="topic-author-name">
                                                    <a href="/users/{{ $data->user->username }}.html"
                                                       class="text-reset">{{ $data->user->username }}</a>
                                                </div>
                                                <div>发表于:{{ format_date($data->created_at) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 vditor-reset" id="docs-content">
                                        {!! ShortCodeR()->handle($data->content) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row row-cards rd">
                        <div class="col-md-12 sticky" style="top: 105px">
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-status-top bg-primary"></div>
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                <a href="/docs/{{$data->docsClass->id}}">{{$data->docsClass->name}}</a>
                                            </h3>
                                            <p>
                                                创建于:{{format_date($data->docsClass->created_at)}}
                                            </p>
                                        </div>
                                        <div class="card-footer">
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

@section('headers')
    <link rel="stylesheet" href="{{ mix('plugins/Topic/css/app.css') }}">
@endsection

@section('scripts')
    <script>var docs_class_id = {{$data->id}};</script>
    <script src="{{file_hash("plugins/Docs/js/docs.js")}}"></script>
@endsection