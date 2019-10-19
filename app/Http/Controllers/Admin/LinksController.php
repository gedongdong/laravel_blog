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
use App\Http\Models\Links;
use App\Library\Response;
use App\Validate\LinksStoreValidate;
use App\Validate\LinksUpdateValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinksController extends Controller
{
    public function index()
    {
        $links = Links::paginate(config('page_size'));

        return view('admin.links.index', ['links' => $links]);
    }

    public function create()
    {
        return view('admin.links.create');
    }


    public function store(Request $request)
    {
        $validate = new LinksStoreValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        try {
            $link = new Links();

            $link->title = $params['title'];
            $link->url   = $params['link'];
            $link->save();

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');

        $link = Links::find($id);

        return view('admin.links.edit', ['link' => $link]);
    }

    public function update(Request $request)
    {
        $validate = new LinksUpdateValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        DB::beginTransaction();

        try {
            $link = Links::find($params['id']);

            $link->title = $params['title'];
            $link->url   = $params['link'];
            $link->save();

            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('更新友情链接数据库异常', [$e->getMessage()]);

            return Response::response(['e' => $e, 'code' => Response::SQL_ERROR]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $link = Links::find($id);
        if (!$link) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        DB::beginTransaction();

        try {
            Links::where('id', $id)->delete();
            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('删除友情链接数据库异常', [$e->getMessage()]);

            return Response::response(['code' => Response::SQL_ERROR, 'e' => $e]);
        }
    }


    public function status(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $link = Links::find($id);
        if (!$link) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        $link->status = Links::STATUS_ENABLE === $link->status ? Links::STATUS_DISABLE : Links::STATUS_ENABLE;

        if (!$link->save()) {
            return Response::response(['code' => Response::SQL_ERROR]);
        }

        return Response::response();
    }

}
