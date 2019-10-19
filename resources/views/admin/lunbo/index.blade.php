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
    <a href="{{ route('lunbo.create') }}" class="layui-btn">添加轮播图</a>
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
            <th>图片</th>
            <th>url</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lunbos as $lunbo)
            <tr>
                <td>{{ $lunbo->id }}</td>
                <td><img src="{{ $lunbo->src }}" width="100" onclick="showBig(this)"/></td>
                <td>{{ $lunbo->url }}</td>
                <td>
                    @if($lunbo->status===\App\Http\Models\Lunbo::STATUS_ENABLE)
                        <span style="color:#009688;">启用</span>
                    @else
                        <span style="color:#FF5722;">禁用</span>
                    @endif
                </td>
                <td>{{ $lunbo->created_at }}</td>
                <td style="text-align: center;">
                    @if($lunbo->status===\App\Http\Models\Lunbo::STATUS_ENABLE)
                        <button class="layui-btn layui-btn-warm layui-btn-xs" type="button"
                                onclick="changeStatus({{ $lunbo->id }})">禁用
                        </button>
                    @else
                        <button class="layui-btn layui-btn-xs" type="button"
                                onclick="changeStatus({{ $lunbo->id }})">启用
                        </button>
                    @endif
                    <a href="{{ route('lunbo.edit') }}?id={{ $lunbo->id }}"
                       class="layui-btn layui-btn-xs">编辑</a>
                    <button class="layui-btn layui-btn-danger layui-btn-xs" type="button"
                            onclick="del({{ $lunbo->id }})">删除
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="page" class="layui-box layui-laypage layui-laypage-default">{{ $lunbos->links() }}</div>
@endsection

@section('script')
    <script>
        layui.use(['layer'], function () {
            var layer = layui.layer;
        });

        function showBig(obj){
            obj = $(obj);
            layer.open({
                type: 1,
                title: false,
                skin: 'layui-layer-nobg', //没有背景色
                shadeClose: true,
                content: '<img src="'+obj.attr('src')+'"/>'
            });
        }

        function del(id) {
            layer.confirm('你确定要删除这个轮播图吗？', {
                title: '删除确认',
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var load = layer.load();
                $.post("{{ route('lunbo.delete') }}", {id: id},
                    function (data) {
                        layer.close(load);
                        if (data.code === 0) {
                            layer.msg('操作成功', {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                location.href = '{{ route('lunbo.index') }}';
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
            $.post("{{ route('lunbo.status') }}", {id: id},
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