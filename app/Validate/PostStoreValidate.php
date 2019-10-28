<?php

/*
 * This file is part of the gedongdong/laravel_rbac_permission.
 *
 * (c) gedongdong <gedongdong2010@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Validate;


use App\Http\Models\Category;
use App\Http\Models\Tags;

class PostStoreValidate extends BaseValidate
{
    protected $rules = [];

    protected $message = [
        'cate_id.required' => '请选择栏目',
        'title.required'   => '请输入标题',
        'title.max'        => '标题最长不超过20个字符',
        'sub_title.max'    => '副标题最长不超过30个字符',
        'summary.max'      => '摘要最长不超过200个字符',
    ];

    public function __construct($request)
    {
        parent::__construct($request);
        $this->rules = [
            'cate_id'   => 'required',
            'title'     => 'required|max:20',
            'sub_title' => 'nullable|max:30',
            'photo'     => 'nullable',
            'summary'   => 'nullable|max:200',
            'tags'      => 'nullable',
        ];
    }

    protected function customValidate()
    {
        $cate_id = $this->requestData['cate_id'];
        if (!Category::find($cate_id)) {
            $this->validator->errors()->add('cate_id', '请选择正确的栏目');
            return false;
        }

        $tags = $this->requestData['tags'] ?? '';
        if ($tags) {
            $tags       = explode(',', $tags);
            $tags       = array_unique($tags);
            $tags_count = Tags::whereIn('id', $tags)->count();
            if ($tags_count != count($tags)) {
                $this->validator->errors()->add('tags', '请选择正确的标签');
                return false;
            }
        }
    }
}
