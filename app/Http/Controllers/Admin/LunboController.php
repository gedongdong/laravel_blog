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
use App\Http\Models\Lunbo;
use App\Library\Response;
use App\Validate\LunboStoreValidate;
use App\Validate\LunboUpdateValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LunboController extends Controller
{
    public function index()
    {
        $lunbos = Lunbo::paginate(config('page_size'));

        return view('admin.lunbo.index', ['lunbos' => $lunbos]);
    }

    public function create()
    {
        return view('admin.lunbo.create');
    }


    public function store(Request $request)
    {
        $validate = new LunboStoreValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        try {
            $lunbo = new Lunbo();

            $lunbo->src = $params['src'];
            $lunbo->url = $params['url'];
            $lunbo->save();

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');

        $lunbo = Lunbo::find($id);

        return view('admin.lunbo.edit', ['lunbo' => $lunbo]);
    }

    public function update(Request $request)
    {
        $validate = new LunboUpdateValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        DB::beginTransaction();

        try {
            $lunbo = Lunbo::find($params['id']);

            $lunbo->src = $params['src'];
            $lunbo->url = $params['url'];
            $lunbo->save();

            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('更新轮播图数据库异常', [$e->getMessage()]);

            return Response::response(['e' => $e, 'code' => Response::SQL_ERROR]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $lunbo = Lunbo::find($id);
        if (!$lunbo) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        DB::beginTransaction();

        try {
            Lunbo::where('id', $id)->delete();
            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('删除轮播图数据库异常', [$e->getMessage()]);

            return Response::response(['code' => Response::SQL_ERROR, 'e' => $e]);
        }
    }


    public function status(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $lunbo = Lunbo::find($id);
        if (!$lunbo) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        $lunbo->status = Lunbo::STATUS_ENABLE === $lunbo->status ? Lunbo::STATUS_DISABLE : Lunbo::STATUS_ENABLE;

        if (!$lunbo->save()) {
            return Response::response(['code' => Response::SQL_ERROR]);
        }

        return Response::response();
    }

}
