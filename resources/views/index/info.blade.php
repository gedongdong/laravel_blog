@extends('layouts.index')

@section('style')
    <link rel="stylesheet" href="/css/detail.css">
@endsection

@section('content')
    <div class="layui-container">
        <div class="layui-row layui-col-space20">
            <div class="layui-col-md8">
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="main">
                            <div class="title">
                                <p>{{ $info->title }}</p>
                                <div class="layui-row stat">
                                    <div class="layui-col-md3 layui-col-xs12">发布时间：<em>{{ $info->created_at }}</em></div>
                                    <div class="layui-col-md2 layui-col-xs6">分类：<a href="javascript:;">{{ $info->category->name }}</a></div>
                                    <div class="layui-col-md2 layui-col-xs6">作者：<a href="javascript:;">木子</a></div>
                                    <div class="layui-col-md5 layui-col-xs12">
                                        <div class="layui-row">

                                            <div class="layui-col-md6 layui-col-xs6">
                                                <i class="layui-icon layui-icon-read"></i>
                                                <em>{{ $info->click }}次阅读</em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="content">
                                {!! $info->content !!}
                            </div>
                            <div class="copyright">
                                <p>
                                    本文链接：<a href="{{ url()->full() }}">{{ url()->full() }}</a>
                                </p>
                                <p>转载声明：本站文章若无特别说明，皆为原创，转载请注明来源，谢谢！^^</p>
                            </div>
                            <div class="Label">
                                <i class="layui-icon layui-icon-note"></i>
                                @foreach($info->tags_name as $item1)
                                    <a href="javascript:;">{{ $item1 }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12 margin20"></div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header">
								<span>
									相关推荐
								</span>
                            </div>
                            <div class="layui-card-body">
                                <div class="layui-row">
                                    @foreach($relates as $relate)
                                    <div class="layui-col-md4">
                                        <div class="layui-card">
                                            <a href="/info?id={{ $relate->id }}" class="layui-card-body recommend">
                                                @if($relate->photo)
                                                    <img src="{{ $relate->photo }}">
                                                @else
                                                <img src="http://www.muzhuangnet.com/upload/201610/18/201610181739277776.jpg">
                                                @endif
                                                <p>{{ $relate->title }}</p>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <td><a href="/info?id={{ $item->id }}">{{ $item->title }}</a></td>
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
