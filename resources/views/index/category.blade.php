@extends('layouts.index')

@section('style')
    <style>
        #page li {
            display: inline-block;
        }

        #page .active span {
            background-color: #009688;
            color: #fff;
            border: 0px;
            height: 30px;
            border-radius: 2px;
        }

        #page .disabled span {
            color: #ccc;
        }
    </style>
@endsection

@section('content')
    <div class="layui-container">
        <div class="layui-row layui-col-space20">
            <div class="layui-col-md8">
                <div class="layui-row">

                    <div class="layui-card">
                        <div class="layui-card-body">
					  		<span class="layui-breadcrumb">
							  	<a href="/">首页</a>
							  	<a><cite>{{ $cate_info->name }}</cite></a>
							</span>
                        </div>
                    </div>
                    @foreach($posts as $post)
                        <div class="layui-col-md12">
                            <div class="main list">
                                <div class="subject"><a href="javascript:;" class="caty">[{{ $post->category->name }}
                                        ]</a><a href="javascript:;"
                                                title="{{ $post->title }}">{{ $post->title }}</a><em> {{ $post->created_at }}
                                        发布</em></div>
                                <div class="content layui-row">
                                    <div class="layui-col-md4 list-img">
                                        <a href="javascript:;">
                                            @if($post->photo)
                                                <img src="{{ $post->photo }}">
                                            @else
                                                <img src="http://www.muzhuangnet.com/upload/201610/18/201610181739277776.jpg">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="layui-col-md8">
                                        <div class="list-text">{{ $post->summary }}</div>
                                        <div class="list-stat layui-row">

                                            @if($post->tags_name)
                                                <div class="layui-col-xs3 layui-col-md3 Label">
                                                    <i class="layui-icon layui-icon-note"></i>
                                                    @foreach($post->tags_name as $item1)
                                                        <a href="javascript:;">{{ $item1 }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="layui-col-xs3 layui-col-md3">
                                                <i class="layui-icon layui-icon-read"></i>
                                                <em>{{ $post->click }}次阅读</em>
                                            </div>

                                            <div class="layui-col-xs3 layui-col-md3 alink">
                                                <a href="javascript:;" class="layui-btn layui-btn-xs">阅读原文</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-col-md12 margin20"></div>
                    @endforeach
                    <div class="layui-col-md12 margin20"></div>
                    <div class="layui-col-md12 layui-box layui-laypage layui-laypage-default"
                         id="page">{{ $posts->links() }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">
								<span class="layui-breadcrumb" lay-separator="|">
									<a href="javascript:;">站点统计</a>
									<a href="javascript:;">联系站长</a>
								</span>
                            </div>
                            <div class="layui-card-body" id="stat">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="120">
                                        <col width="230">
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td>运行时间：</td>
                                        <td>{{ $day }} 天</td>
                                    </tr>
                                    <tr>
                                        <td>发表文章：</td>
                                        <td>{{ \App\Http\Models\Posts::where('status',\App\Http\Models\Posts::STATUS_ENABLE)->count() }} 篇</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="layui-card-body" id="binfo">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="120">
                                        <col width="230">
                                    </colgroup>
                                    <tbody>
                                    <tr>
                                        <td>QQ：</td>
                                        <td>2253538281</td>
                                    </tr>
                                    <tr>
                                        <td>Wechat：</td>
                                        <td>gedongdong1988</td>
                                    </tr>
                                    <tr>
                                        <td>github：</td>
                                        <td><a href="https://github.com/gedongdong/laravel_rbac_permission" target="_blank">Laravel
                                                RBAC Permission</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12 margin20"></div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">
								<span>
									热门文章
								</span>
                            </div>
                            <div class="layui-card-body">
                                <table class="layui-table" lay-skin="nob">
                                    <tbody>
                                    @foreach($hot_post as $item)
                                        <tr>
                                            <td><a href="javascript:;">{{ $item->title }}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
