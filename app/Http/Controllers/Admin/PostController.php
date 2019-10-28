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
use App\Http\Models\Tags;
use App\Library\Response;
use App\Validate\PostStoreValidate;
use App\Validate\PostUpdateValidate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Posts::paginate(config('page_size'));
        foreach ($posts as &$post) {
            if ($post->tags) {
                $post->tags = Tags::whereIn('id', explode(',', $post->tags))->pluck('name');
            }
        }

        return view('admin.post.index', ['posts' => $posts]);
    }

    public function create()
    {
        $cates = Category::where('status', Category::STATUS_ENABLE)->get();

        $tags = Tags::select('id', 'name')->get()->toArray();

        return view('admin.post.create', ['cates' => $cates, 'tags' => json_encode($tags, JSON_UNESCAPED_UNICODE)]);
    }


    public function store(Request $request)
    {
        $validate = new PostStoreValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        try {
            $lunbo = new Posts();

            $tags = $params['tags'] ?? null;
            if ($tags) {
                $tags = implode(',', array_unique(explode(',', $tags)));
            }
            $lunbo->cate_id   = $params['cate_id'];
            $lunbo->title     = $params['title'];
            $lunbo->sub_title = $params['sub_title'] ?? null;
            $lunbo->photo     = $params['photo'] ?? null;
            $lunbo->summary   = $params['summary'] ?? null;
            $lunbo->tags      = $tags;
            $lunbo->content   = $params['content'] ?? null;
            $lunbo->save();

            return Response::response();
        } catch (QueryException $e) {
            return Response::response(['e' => $e]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');

        $post = Posts::find($id);

        $cates = Category::where('status', Category::STATUS_ENABLE)->get();

        $tags = Tags::select('id', 'name')->get()->toArray();

        return view('admin.post.edit', ['post' => $post, 'cates' => $cates, 'tags' => json_encode($tags, JSON_UNESCAPED_UNICODE)]);
    }

    public function update(Request $request)
    {
        $validate = new PostUpdateValidate($request);

        if (!$validate->goCheck()) {
            return Response::response(['code' => Response::PARAM_ERROR, 'msg' => $validate->errors->first()]);
        }

        $params = $validate->requestData;

        DB::beginTransaction();

        try {
            $lunbo = Posts::find($params['id']);

            $tags = $params['tags'] ?? null;
            if ($tags) {
                $tags = implode(',', array_unique(explode(',', $tags)));
            }
            $lunbo->cate_id   = $params['cate_id'];
            $lunbo->title     = $params['title'];
            $lunbo->sub_title = $params['sub_title'] ?? null;
            $lunbo->photo     = $params['photo'] ?? null;
            $lunbo->summary   = $params['summary'] ?? null;
            $lunbo->tags      = $tags;
            $lunbo->content   = $params['content'] ?? null;
            $lunbo->save();

            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('更新文章数据库异常', [$e->getMessage()]);

            return Response::response(['e' => $e, 'code' => Response::SQL_ERROR]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $post = Posts::find($id);
        if (!$post) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        DB::beginTransaction();

        try {
            Posts::where('id', $id)->delete();
            DB::commit();

            return Response::response();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('删除文章数据库异常', [$e->getMessage()]);

            return Response::response(['code' => Response::SQL_ERROR, 'e' => $e]);
        }
    }


    public function status(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return Response::response(['code' => Response::PARAM_ERROR]);
        }

        $post = Posts::find($id);
        if (!$post) {
            return Response::response(['code' => Response::BAD_REQUEST]);
        }

        $post->status = Posts::STATUS_ENABLE === $post->status ? Posts::STATUS_DISABLE : Posts::STATUS_ENABLE;

        if (!$post->save()) {
            return Response::response(['code' => Response::SQL_ERROR]);
        }

        return Response::response();
    }

}
