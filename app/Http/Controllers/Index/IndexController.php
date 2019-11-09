<?php
/**
 * User: gedongdong
 * Date: 2019-11-03 20:34
 */

namespace App\Http\Controllers\Index;


use App\Http\Models\Lunbo;
use App\Http\Models\Posts;
use App\Http\Models\Tags;

class IndexController
{
    public function index()
    {
        //轮播
        $lunbo = Lunbo::where('status', Lunbo::STATUS_ENABLE)->get();

        //文章
        $posts = Posts::where('status', Posts::STATUS_ENABLE)->paginate(6);
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

        return view('index.index', ['lunbo' => $lunbo, 'posts' => $posts, 'hot_post' => $hot_posts, 'day' => $day]);
    }
}