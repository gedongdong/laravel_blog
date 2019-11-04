<?php

/*
 * This file is part of the gedongdong/laravel_rbac_permission.
 *
 * (c) gedongdong <gedongdong2010@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Middleware;

use App\Http\Models\Category;
use App\Http\Models\Config;
use Closure;
use Illuminate\Support\Facades\View;

class CommonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $config  = Config::all();
        $configs = [];
        foreach ($config as $conf) {
            $configs[$conf->key] = $conf->value;
        }
        View::share('configs', $configs);

        $category = Category::where('status', Category::STATUS_ENABLE)->get();
        View::share('category', $category);

        $cate_id = $request->get('id');
        View::share('cate_id', $cate_id);

        return $next($request);
    }
}
