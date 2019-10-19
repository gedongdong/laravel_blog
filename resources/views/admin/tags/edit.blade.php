@extends('layouts.admin')

@section('content')
    <a href="{{ route('links.index') }}" class="layui-btn layui-btn-primary layui-btn-sm">返回</a>
    <hr>
    <form class="layui-form" action="" style="width: 900px;">
        <div class="layui-form-item" style="width: 400px;">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" required lay-verify="required" placeholder="请输入标题"
                       autocomplete="off" class="layui-input"
                       @if($link)
                       value="{{ $link->title }}"
                        @endif
                >
                @if($link)
                    <input type="hidden" name="id" value="{{ $link->id }}">
                @endif
            </div>
        </div>
        <div class="layui-form-item" style="width: 700px;">
            <label class="layui-form-label">链接</label>
            <div class="layui-input-block">
                <input type="text" name="link" required lay-verify="required" placeholder="请输入链接，如 http://www.baidu.com"
                       autocomplete="off" class="layui-input" style="width: 700px;"
                       @if($link)
                       value="{{ $link->url }}"
                        @endif
                >
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo" type="button">保存</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        layui.use(['form', 'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;

            //监听提交
            form.on('submit(formDemo)', function (data) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var load = layer.load();
                $.post("{{ route('links.update') }}", data.field,
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

        });
    </script>
@endsection