<?php

/*
 * This file is part of the gedongdong/laravel_rbac_permission.
 *
 * (c) gedongdong <gedongdong2010@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $guarded = [];

    const STATUS_ENABLE = 1;

    const STATUS_DISABLE = -1;

    public function category()
    {
        return $this->hasOne('App\Http\Models\Category', 'id', 'cate_id');
    }
}
