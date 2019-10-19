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
use App\Library\Response;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $allowExt = ['jpg', 'jpeg', 'png', 'gif'];

    protected $allowSize = 2 * 1024 * 1024; //2M

    public function upload(Request $request)
    {
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $extension = $photo->extension();
            if (!in_array($extension, $this->allowExt)) {
                return Response::response(['code' => Response::BAD_REQUEST, 'msg' => '只允许jpg、jpeg、png和gif的图片']);
            }

            $size = $photo->getSize();
            if ($size > $this->allowSize) {
                return Response::response(['code' => Response::BAD_REQUEST, 'msg' => '图片大小不能超过2M']);
            }

            try {
                $src = $photo->store('lunbo', 'upload');
                return Response::response(['data' => ['src' => '/upload/' . $src], 'code' => 0, 'msg' => 'success']);
            } catch (\Exception $e) {
                return Response::response(['e' => $e, 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            }
        }
    }
}
