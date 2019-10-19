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

        });
    </script>
@endsection