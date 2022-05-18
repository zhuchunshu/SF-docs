@extends('App::app')
@section('title','「'.$data->title.'」的文档内容')
@section('header')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            Documentation
                        </div>
                        <h2 class="page-title">
                            {{$data->docsClass->name}}
                        </h2>
                    </div>

                    <div class="col-auto">
                        <a href="/docs/create/{{$data->docsClass->id}}" class="btn btn-dark">发布文档</a>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(function () {
            $('a[docs-menu="active"]').each(function(){
                $(this).parents('ul').addClass('show');
            });
        })
    </script>
@endsection

@section('content')

    <div class="row gx-lg-5">
        @include('Docs::menu')
        <div class="col-lg-9">
            <div class="card card-lg">
                <div class="mt-3 mx-3">
                    <ol class="breadcrumb breadcrumb-arrows" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="/docs">文档</a></li>
                        <li class="breadcrumb-item"><a href="/docs/{{$data->class_id}}">{{$data->docsClass->name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$data->title}}</a></li>
                    </ol>
                </div>
                <div class="card-body">
                    <div class="markdown">
                        {!! $data->content !!}
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="border-0 card">
                    <div class="card-body">
                        <ul class="pagination ">

                            @if ($shang)
                                <li class="page-item page-prev">
                                    <a class="page-link"
                                       href="/docs/{{$data->class_id}}/{{$shang['id']}}.html">
                                        <div class="page-item-subtitle">上一篇文档</div>
                                        <div class="page-item-title">
                                            {{ \Hyperf\Utils\Str::limit($shang['title'], 20, '...') }}</div>
                                    </a>
                                </li>
                            @else
                                <li class="page-item page-prev disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <div class="page-item-subtitle">上一篇文档</div>
                                        <div class="page-item-title">暂无</div>
                                    </a>
                                </li>
                            @endif
                            @if ($xia)
                                <li class="page-item page-next">
                                    <a class="page-link"
                                       href="/docs/{{$data->class_id}}/{{ $xia['id']  }}.html">
                                        <div class="page-item-subtitle">下一篇文档</div>
                                        <div class="page-item-title">
                                            {{ \Hyperf\Utils\Str::limit($xia['title'], 20, '...') }}</div>
                                    </a>
                                </li>
                            @else
                                <li class="page-item page-next disabled">
                                    <a class="page-link" href="#">
                                        <div class="page-item-subtitle">下一篇文档</div>
                                        <div class="page-item-title">暂无</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
