@extends('layouts.admin')

@section('content')
    <a href="{{ route('post.index') }}" class="layui-btn layui-btn-primary layui-btn-sm">返回</a>
    <hr>
    <form class="layui-form" action="" style="width: 900px;" id="form">
        <div class="layui-form-item" style="width: 400px;">
            <label class="layui-form-label">所属栏目</label>
            <div class="layui-input-block">
                <select name="cate_id" lay-verify="required">
                    @foreach($cates as $cate)
                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item" style="width: 600px;">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" required lay-verify="required" placeholder="请输入文章标题"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">副标题</label>
            <div class="layui-input-block">
                <input type="text" name="sub_title" placeholder="请输入文章副标题"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="width: 400px;display: none;" id="view">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <img src="" alt="" id="view_img" style="width: 200px;">
            </div>
        </div>
        <div class="layui-form-item" style="width: 400px;">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="upload">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
                <input type="hidden" name="photo" id="src" value="">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">摘要</label>
            <div class="layui-input-block">
                <textarea name="summary" placeholder="请输入摘要" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">文章内容</label>
            <div class="layui-input-block">
                <div id="editor"></div>
            </div>
            <input type="hidden" name="content" id="content" value="">
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章标签</label>
            <div class="layui-input-block" id="tag_ids2">

            </div>
            <div class="layui-form-item" style="margin-top: 13px;">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo" type="button">立即提交</button>
                </div>
            </div>
    </form>
@endsection

@section('script')
    <script src="/js/ext/selectM.js"></script>
    <script src="/js/wangEditor.min.js"></script>
    <script>
        var E = window.wangEditor;
        var editor = new E('#editor');
        editor.customConfig.uploadImgServer = '{{ route('admin.upload') }}';  // 上传图片到服务器
        // 将图片大小限制为 2M
        editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024;
        // 限制一次最多上传 1 张图片
        editor.customConfig.uploadImgMaxLength = 1;
        editor.customConfig.uploadFileName = 'photo';
        editor.customConfig.uploadImgHeaders = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        // 将 timeout 时间改为 3s
        editor.customConfig.uploadImgTimeout = 3000;
        editor.customConfig.uploadImgHooks = {
            // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
            // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
            customInsert: function (insertImg, result, editor) {
                // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
                // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果

                // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
                var url = result.data.src;
                insertImg(url)

                // result 必须是一个 JSON 格式字符串！！！否则报错
            }
        };
        editor.create();

        layui.use(['form', 'layer', 'upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var selectM = layui.selectM;
            var upload = layui.upload;

            var tagIns2 = selectM({
                //元素容器【必填】
                elem: '#tag_ids2'

                //候选数据【必填】
                , data: {!! $tags !!}

                //默认值
                , selected: []

                //最多选中个数，默认5
                , max: 4

                //input的name 不设置与选择器相同(去#.)
                , name: 'tags'

                //值的分隔符
                , delimiter: ','

                //候选项数据的键名
                , field: {idName: 'id', titleName: 'name'}


            });

            //监听提交
            form.on('submit(formDemo)', function (data) {
                $('#content').val(editor.txt.html());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var fields = $("#form").serializeArray();
                var load = layer.load();
                $.post("{{ route('post.store') }}", fields,
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