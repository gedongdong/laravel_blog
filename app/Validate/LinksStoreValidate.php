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


class LinksStoreValidate extends BaseValidate
{
    protected $rules = [];

    protected $message = [
        'title.required' => '请输入名称',
        'title.max'      => '名称最长20个字符',
        'link.required'  => '请输入链接',
        'link.url'       => '请检查链接格式',
    ];

    public function __construct($request)
    {
        parent::__construct($request);
        $this->rules = [
            'title' => 'required|max:20',
            'link'  => 'required|url',
        ];
    }
}
