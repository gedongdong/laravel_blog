@extends('layouts.admin')

@section('content')
    <a href="{{ route('lunbo.index') }}" class="layui-btn layui-btn-primary layui-btn-sm">返回</a>
    <hr>
    <form class="layui-form" action="" style="width: 900px;">
        <div class="layui-form-item" style="width: 400px;" id="view">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <img src="{{ $lunbo->src }}" alt="" id="view_img" style="width: 200px;">
            </div>
        </div>
        <div class="layui-form-item" style="width: 400px;">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="upload">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
                <input type="hidden" name="src" id="src" value="{{ $lunbo->src }}">
            </div>
        </div>
        <div class="layui-form-item" style="width: 400px;">
            <label class="layui-form-label">链接</label>
            <div class="layui-input-block">
                <input type="text" name="url" placeholder="请输入图片链接，可为空"
                       autocomplete="off" class="layui-input"
                       @if($lunbo)
                       value="{{ $lunbo->url }}"
                        @endif
                >
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" name="id" value="{{ $lunbo->id }}">
                <button class="layui-btn" lay-submit lay-filter="formDemo" type="button">保存</button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        layui.use(['form', 'layer', 'upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            //监听提交
            form.on('submit(formDemo)', function (data) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var load = layer.load();
                $.post("{{ route('lunbo.update') }}", data.field,
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            var uploadInst = upload.render({
                elem: '#upload' //绑定元素
                , url: '{{ route('admin.upload') }}' //上传接口
                , field: 'photo'
                , acceptMime: 'image/*'
                , before: function (obj) { //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                    layer.load(); //上传loading
                }
                , done: function (res) {
                    layer.closeAll('loading'); //关闭loading
                    if (res.code === 0) {
                        $('#src').val(res.data.src);
                        $('#view_img').attr('src', res.data.src);
                    } else {
                        layer.msg(res.msg, {
                            offset: '15px'
                            , icon: 2
                            , time: 2000
                        });
                    }
                }
                , error: function (index, upload) {
                    layer.closeAll('loading'); //关闭loading
                }
            });
        });
    </script>
@endsection