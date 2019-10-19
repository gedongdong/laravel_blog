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
    <a href="{{ route('links.create') }}" class="layui-btn">添加友情链接</a>
    <table class="layui-table">
        <colgroup>
            <col width="100">
            <col>
            <col>
            <col>
            <col width="200">
            <col width="200">
        </colgroup>
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>链接</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($links as $link)
            <tr>
                <td>{{ $link->id }}</td>
                <td>{{ $link->title }}</td>
                <td>{{ $link->url }}</td>
                <td>
                    @if($link->status===\App\Http\Models\Links::STATUS_ENABLE)
                        <span style="color:#009688;">启用</span>
                    @else
                        <span style="color:#FF5722;">禁用</span>
                    @endif
                </td>
                <td>{{ $link->created_at }}</td>
                <td style="text-align: center;">
                    @if($link->status===\App\Http\Models\Links::STATUS_ENABLE)
                        <button class="layui-btn layui-btn-warm layui-btn-xs" type="button"
                                onclick="changeStatus({{ $link->id }})">禁用
                        </button>
                    @else
                        <button class="layui-btn layui-btn-xs" type="button"
                                onclick="changeStatus({{ $link->id }})">启用
                        </button>
                    @endif
                    <a href="{{ route('links.edit') }}?id={{ $link->id }}"
                       class="layui-btn layui-btn-xs">编辑</a>
                    <button class="layui-btn layui-btn-danger layui-btn-xs" type="button"
                            onclick="del({{ $link->id }})">删除
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="page" class="layui-box layui-laypage layui-laypage-default">{{ $links->links() }}</div>
@endsection

@section('script')
    <script>
        layui.use(['layer'], function () {
            var layer = layui.layer;
        });

        function del(id) {
            layer.confirm('你确定要删除这个友情链接吗？', {
                title: '删除确认',
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var load = layer.load();
                $.post("{{ route('links.delete') }}", {id: id},
                    function (data) {
                        layer.close(load);
                        if (data.code === 0) {
                            layer.msg('操作成功', {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                location.href = '{{ route('links.index') }}';
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

        function changeStatus(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            var load = layer.load();
            $.post("{{ route('links.status') }}", {id: id},
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