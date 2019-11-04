@extends('layouts.admin')

@section('content')
    网站配置
    <hr>
    <form class="layui-form" action="" style="width: 1200px;">
        @foreach($config as $conf)
            @if($conf['key']=='site_switch')
                <div class="layui-form-item">
                    <label class="layui-form-label">{{ $conf['desc'] }}</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="{{ $conf['key'] }}" lay-skin="switch"
                               @if($conf['value']=='on')
                               checked
                                @endif
                        >
                    </div>
                </div>
            @elseif($conf['key']=='logo')
                <div class="layui-form-item" style="width: 400px;
                @if(!$conf['value'])
                        display: none;
                @endif
                        " id="view">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <img src="{{ $conf['value'] }}" alt="" id="view_img" style="width: 200px;">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">{{ $conf['desc'] }}</label>
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn layui-btn-sm" id="upload">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                        <input type="hidden" name="logo" id="src" value="{{ $conf['value'] }}">
                    </div>
                </div>
            @else
                <div class="layui-form-item" style="width: 700px;">
                    <label class="layui-form-label">{{ $conf['desc'] }}</label>
                    <div class="layui-input-block">
                        <input type="text" name="{{ $conf['key'] }}"
                               placeholder="请输入{{ $conf['desc'] }}"
                               autocomplete="off" class="layui-input" value="{{ $conf['value'] }}">
                    </div>
                </div>
            @endif
        @endforeach

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo" type="button">立即提交</button>
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
                $.post("{{ route('config.store') }}", data.field,
                    function (data) {
                        layer.close(load);
                        if (data.code === 0) {
                            layer.msg('操作成功', {
                                offset: '15px'
                                , icon: 1
                                , time: 1000
                            }, function () {
                                location.href = '{{ route('config.index') }}';
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
                        $('#view').show();
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