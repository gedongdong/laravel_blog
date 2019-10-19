<?php

/*
 * This file is part of the gedongdong/laravel_rbac_permission.
 *
 * (c) gedongdong <gedongdong2010@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\Config;
use App\Http\Models\Lunbo;
use App\Library\Response;
use App\Validate\LunboStoreValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        $config = Config::all();

        return view('admin.config.index', ['config' => $config]);
    }

    public function store(Request $request)
    {
        $datas = $request->all();
        try {
            //foreach ($datas as $key => $data) {
            //    Config::where('key', $key)->update(['value' => $data]);
            //}
            $config = Config::all();
            foreach ($config as $conf) {
                if ($conf->key === 'site_switch') {
                    $site_switch = $datas['site_switch'] ?? null;
                    if ($site_switch === 'on') {
                        $conf->value = 'on';
                    } else {
                        $conf->value = 'off';
                    }
                } else {
                    $conf->value = $datas[$conf->key];
                }
                $conf->save();
            }

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }
}
