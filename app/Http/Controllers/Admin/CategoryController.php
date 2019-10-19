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
use App\Http\Models\Category;
use App\Http\Models\Posts;
use App\Library\Response;
use App\Validate\CategoryStoreValidate;
use App\Validate\CategoryUpdateValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $cates = Category::paginate(config('page_size'));

        return view('admin.category.index', ['cates' => $cates]);
    }

    public function create()
    {
        return view('admin.category.create');
    }


    public function store(Request $request)
    {
        $validate = new CategoryStoreValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        try {
            $category = new Category();

            $category->name = $params['name'];
            $category->save();

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }

    public function edit(Request $request)
    {
        $cate_id = $request->get('cate_id');

        $category = Category::find($cate_id);

        return view('admin.category.edit', ['category' => $category]);
    }

    public function update(Request $request)
    {
        $validate = new CategoryUpdateValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        DB::beginTransaction();

        try {
            $category = Category::find($params['id']);

            $category->name = $params['name'];
            $category->save();

            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('更新栏目数据库异常', [$e->getMessage()]);

            return Response::response(['e' => $e, 'code' => Response::SQL_ERROR]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $category = Category::find($id);
        if (!$category) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        if (Posts::where('cate_id', $id)->count()) {
            return Response::response(['code' => Response::BAD_REQUEST, 'msg' => '该栏目下存在文章，不能删除']);
        }

        DB::beginTransaction();

        try {
            Category::where('id', $id)->delete();
            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('删除栏目数据库异常', [$e->getMessage()]);

            return Response::response(['code' => Response::SQL_ERROR, 'e' => $e]);
        }
    }


    public function status(Request $request)
    {
        $cate_id = $request->get('cate_id');
        if (!$cate_id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $category = Category::find($cate_id);
        if (!$category) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        $category->status = Category::STATUS_ENABLE === $category->status ? Category::STATUS_DISABLE : Category::STATUS_ENABLE;

        if (!$category->save()) {
            return Response::response(['code' => Response::SQL_ERROR]);
        }

        return Response::response();
    }

}
