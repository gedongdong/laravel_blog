<?php
/**
 * User: gedongdong
 * Date: 2019-11-03 20:34
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('index.index');
    }
}