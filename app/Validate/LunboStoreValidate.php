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


class LunboStoreValidate extends BaseValidate
{
    protected $rules = [];

    protected $message = [
        'src.required' => '请上传图片',
        'url.url' => '链接地址有误',
    ];

    public function __construct($request)
    {
        parent::__construct($request);
        $this->rules = [
            'src' => 'required',
            'url' => 'nullable|url',
        ];
    }
}
