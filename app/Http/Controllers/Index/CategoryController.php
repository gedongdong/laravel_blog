<?php
/**
 * User: gedongdong
 * Date: 2019-11-03 20:34
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Http\Models\Category;
use App\Http\Models\Posts;
use App\Http\Models\Tags;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            return redirect('/');
        }

        $cate_info = Category::find($id);
        if (!$cate_info) {
            return redirect('/');
        }

        $posts = Posts::where('status', Posts::STATUS_ENABLE)->where('cate_id', $id)->paginate(10);
        foreach ($posts as &$item) {
            if (!$item->summary) {
                $content       = preg_replace("/<[^>]+>/", '', $item->content);
                $item->summary = mb_substr($content, 0, 50);
            }
            $tags = [];
            if ($item->tags) {
                $tags = Tags::whereIn('id', explode(',', $item->tags))->pluck('name');
            }
            $item->tags_name = $tags;
        }

        //热门文章
        $hot_posts = Posts::where('status', Posts::STATUS_ENABLE)->select('id', 'title')->orderby('click', 'desc')->limit(10)->get();

        //运行天数
        $day = ceil((time() - strtotime('2019-10-01')) / (60 * 60 * 24));

        return view('index.category', ['posts' => $posts, 'cate_info' => $cate_info, 'hot_post' => $hot_posts, 'day' => $day]);
    }

    public function info(Request $request)
    {
        $id = $request->get('id');
        if (!$id) {
            return redirect('/');
        }

        $info = Posts::find($id);
        if (!$info || $info->status === Posts::STATUS_DISABLE) {
            return redirect('/');
        }

        $tags = [];
        if ($info->tags) {
            $tags = Tags::whereIn('id', explode(',', $info->tags))->pluck('name');
        }
        $info->tags_name = $tags;

        $relates = Posts::where('status', Posts::STATUS_ENABLE)->where('cate_id', $info->cate_id)->where('id', '!=', $id)->limit(3)->get();

        //热门文章
        $hot_posts = Posts::where('status', Posts::STATUS_ENABLE)->select('id', 'title')->orderby('click', 'desc')->limit(10)->get();

        //运行天数
        $day = ceil((time() - strtotime('2019-10-01')) / (60 * 60 * 24));

        return view('index.info', ['info' => $info, 'hot_post' => $hot_posts, 'day' => $day, 'relates' => $relates]);
    }
}