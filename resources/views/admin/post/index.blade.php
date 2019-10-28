@extends('layouts.admin')

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
    <a href="{{ route('post.create') }}" class="layui-btn">创建文章</a>
    <table class="layui-table">
        <colgroup>
            <col width="100">
            <col>
            <col>
            <col width="200">
            <col width="200">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>副标题</th>
            <th>所属栏目</th>
            <th>标签</th>
            <th>点击量</th>
            <th>状态</th>
            <th>更新时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->sub_title }}</td>
                <td>{{ $post->category->name }}</td>
                <td>
                    @if($post->tags)
                        @foreach($post->tags as $tag)
                            <span class="layui-badge layui-bg-green">{{ $tag }}</span>
                        @endforeach
                    @endif
                </td>
                <td>{{ $post->click }}</td>
                <td>
                    @if($post->status===\App\Http\Models\Posts::STATUS_ENABLE)
                        <span style="color:#009688;">已发布</span>
                    @else
                        <span style="color:#FF5722;">禁用</span>
                    @endif
                </td>
                <td>{{ $post->updated_at }}</td>
                <td style="text-align: center;">
                    @if($post->status===\App\Http\Models\Posts::STATUS_ENABLE)
                        <button class="layui-btn layui-btn-warm layui-btn-xs" type="button"
                                onclick="changeStatus({{ $post->id }})">禁用
                        </button>
                    @else
                        <button class="layui-btn layui-btn-xs" type="button"
                                onclick="changeStatus({{ $post->id }})">发布
                        </button>
                    @endif
                    <a href="{{ route('post.edit') }}?id={{ $post->id }}"
                       class="layui-btn layui-btn-xs">编辑</a>
                    <button class="layui-btn layui-btn-danger layui-btn-xs" type="button"
                            onclick="del({{ $post->id }})">删除
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="page" class="layui-box layui-laypage layui-laypage-default">{{ $posts->links() }}</div>
@endsection

@section('script')
    <script>
        layui.use(['layer'], function () {
            var layer = layui.layer;
        });

        function del(id) {
            layer.confirm('你确定要删除这篇文章吗？', {
                title: '删除确认',
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var load = layer.load();
                $.post("{{ route('post.delete') }}", {id: id},
                    function (data) {
                        layer.close(load);
                        if (data.code === 0) {
                            layer.msg('操作成功', {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                location.href = '{{ route('post.index') }}';
                            });
                        } else {
                            layer.msg(data.msg, {
                                offset: '15px'
                                , icon: 2
                                , time: 2000
                            });
                        }
                    });
            });
        }

        function changeStatus(post_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            var load = layer.load();
            $.post("{{ route('post.status') }}", {id: post_id},
                function (data) {
                    layer.close(load);
                    if (data.code === 0) {
                        layer.msg('操作成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1000
                        }, function () {
                            window.location.reload();
                        });

                    } else {
                        layer.msg(data.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 2000
                        });
                    }
                });
        }
    </script>
@endsection