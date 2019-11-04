<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="{{ $configs['seo_desc'] }}">
    <meta name="keywords" content="{{ $configs['seo_keyword'] }}">
    <title>首页 - {{ $configs['site_name'] }}</title>
    <link rel="stylesheet" href="/js/layui/css/layui.css">
    <link rel="stylesheet" href="/css/common.css">
</head>
<body>
<header class="layui-bg-cyan">
    <nav class="layui-container">
        <div class="layui-row">
            <div class="layui-col-md2 logo">
                <a href="/"><img src="{{ $configs['logo'] }}"></a>
            </div>
            <div class="layui-col-md8 layui-hide-xs">
                <ul class="layui-nav layui-bg-cyan">
                    <li class="layui-nav-item
                    @if(!$cate_id)
                            layui-this
@endif
                            ">
                        <a href="{{ route('index.white') }}">首页</a>
                    </li>
                    @foreach($category as $cate)
                        <li class="layui-nav-item
                    @if($cate_id == $cate->id)
                                layui-this
@endif
                                ">
                            <a href="{{ route('category.white') }}?id={{ $cate->id }}">{{ $cate->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="layui-container">
    @yield('content')
</div>

<!-- 尾部 -->
<div class="footer"></div>
<footer class="layui-bg-cyan">
    <div class="layui-container">
        <div class="layui-row">
            <p>{{ $configs['beian'] }} Powered By <a href="https://github.com/gedongdong/laravel_rbac_permission" target="_blank" title="XzcBlogTemplate">Laravel_RBAC_Persmission</a>
            </p>
        </div>
    </div>
</footer>
</body>
<script src="/js/layui/layui.all.js"></script>

<script>
    layui.carousel.render({
        elem: '#carousel'
        , width: '100%' //设置容器宽度
        , arrow: 'always' //始终显示箭头
        //,anim: 'updown' //切换动画方式
    });
    layui.laypage.render({
        elem: 'pages' //注意，这里的 test1 是 ID，不用加 # 号
        , count: 123 //数据总数，从服务端得到
    });
</script>
</html>