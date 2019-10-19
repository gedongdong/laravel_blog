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

use App\Http\Models\Links;

class LinksUpdateValidate extends BaseValidate
{
    protected $rules = [];

    protected $message = [
        'id.required'    => 'ID参数不能为空',
        'id.numeric'     => 'ID参数不正确',
        'title.required' => '请输入名称',
        'title.max'      => '名称最长20个字符',
        'link.required'  => '请输入链接',
        'link.url'       => '请检查链接格式',
    ];

    public function __construct($request)
    {
        parent::__construct($request);
        $this->rules = [
            'id'    => 'required|numeric',
            'title' => 'required|max:20',
            'link'  => 'required|url',
        ];
    }

    protected function customValidate()
    {
        $id   = $this->requestData['id'];

        if (!Links::find($id)) {
            $this->validator->errors()->add('id', '链接信息不正确');

            return false;
        }

    }
}
