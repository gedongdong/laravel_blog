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
use App\Http\Models\Tags;
use App\Library\Response;
use App\Validate\TagsStoreValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tags::paginate(config('page_size'));

        return view('admin.tags.index', ['tags' => $tags]);
    }

    public function create()
    {
        return view('admin.tags.create');
    }


    public function store(Request $request)
    {
        $validate = new TagsStoreValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        try {
            $tag = new Tags();

            $tag->name = $params['name'];
            $tag->save();

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $tag = Tags::find($id);
        if (!$tag) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        DB::beginTransaction();

        try {
            Tags::where('id', $id)->delete();
            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('删除标签数据库异常', [$e->getMessage()]);

            return Response::response(['code' => Response::SQL_ERROR, 'e' => $e]);
        }
    }
}
