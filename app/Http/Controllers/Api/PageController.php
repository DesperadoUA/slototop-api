<?php

namespace App\Http\Controllers\Api;

use App\Models\Cash;
use App\Models\Posts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Models\Category;
use App\CardBuilder;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    const POST_TYPE = 'page';
    const OFFSET    = 0;
    const LIMIT     = 8;
    const MAIN_PAGE_LIMIT_CASINO = 12;
    const LIMIT_POPULAR_SLOTS = 4;
    const LIMIT_POPULAR_BONUS = 4;
    const LIMIT_NEW_CASINO = 4;
    const ORDER_BY  = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG      = 1;
    const REVIEWS_LIMIT_CASINO = 5;
    const BLOG_LIMIT = 1000;
    const LIMIT_NEWS_USEFUL = 11;
    const LIMIT_BLOG_USEFUL = 5;
    const LIMIT_INTERVIEW_USEFUL = 5;
    const TABLE = 'pages';
    const TABLE_CATEGORY = 'category';
    const TABLE_POSTS = 'posts';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        /*
        $posts = new Pages();
        $settings = [
            'offset'    => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit'     => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by'  => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $data = $posts->getPublicPosts($settings);
        if(!$data->isEmpty()) {
            $response['body'] = $data;
            $response['confirm'] = 'ok';
        }
        */
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function main()
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {
            $bonus = new Posts(['post_type' => 'bonus']);
            $casino = new Posts(['post_type' => 'casino']);
            $slot = new Posts(['post_type' => 'slot']);
            $category = new Category();

            $response['body'] = self::dataMetaDecode($data[0]);
            $response['body']['category_link'] = [];
            $parent_category = $category->getPublicPostByUrl(config('constants.CATEGORY.CASINO'));
            if(!$parent_category->isEmpty()) {
                $response['body']['category_link'] = CardBuilder::categoryLinks($category->getChildPublicCategory($parent_category[0]->id));
            }
            $response['body']['bonuses'] = CardBuilder::bonusCard($bonus->getBonusMainPage($data[0]->lang));
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_POPULAR_BONUS,
                'order_key' => 'rating'
            ];
            $response['body']['popular_bonus'] = CardBuilder::bonusCard($bonus->getPublicPosts($settings));
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $response['body']['casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_POPULAR_SLOTS,
                'order_key' => 'rating'
            ];
            $response['body']['popular_slots'] = CardBuilder::slotCard($slot->getPublicPosts($settings));
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_NEW_CASINO
            ];
            $response['body']['new_casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function reviews() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.REVIEWS'));
        if(!$data->isEmpty()) {
            $casino = new Posts(['post_type' => 'casino']);
            $category = new Category();
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::REVIEWS_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $response['body']['top_rating_casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::REVIEWS_LIMIT_CASINO,
            ];
            $response['body']['new_casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $category_popular_casino = $category->getPublicPostByUrl(config('constants.CATEGORY.POPULAR_CASINO'));
            $response['body']['popular_casino'] = [];
            if(!$category_popular_casino->isEmpty()) {
                $arr_id = [];
                $arr_posts = $category->getPublicPostsFromCategory($category_popular_casino[0]->id)
                                      ->slice(0, self::REVIEWS_LIMIT_CASINO);
                foreach ($arr_posts as $item) $arr_id[] = $item->id;
                $response['body']['popular_casino'] = CardBuilder::casinoCard($casino->getPublicPostsByArrId($arr_id));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function bonuses(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.BONUSES'));
        if(!$data->isEmpty()) {
            $bonus = new Posts(['post_type' => 'bonus']);
            $response['body'] = self::dataMetaDecode($data[0]);
            $response['body']['bonuses'] = CardBuilder::bonusCard($bonus->getBonusMainPage($data[0]->lang));
            $settings = [
                'limit' => 1000,
                'order_key' => 'rating',
                'lang' =>  $data[0]->lang
            ];
            $response['body']['list'] = CardBuilder::bonusCard($bonus->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function blog(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.BLOG'));
        if(!$data->isEmpty()) {
            $blog = new Posts(['post_type' => 'blog']);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::BLOG_LIMIT,
            ];
            $response['body']['blog'] = CardBuilder::blogCard($blog->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function news(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.NEWS'));
        if(!$data->isEmpty()) {
            $news = new Posts(['post_type' => 'news']);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::BLOG_LIMIT,
            ];
            $response['body']['news'] = CardBuilder::newsCard($news->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function interview(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.INTERVIEW'));
        if(!$data->isEmpty()) {
            $interview = new Posts(['post_type' => 'interview']);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::BLOG_LIMIT,
            ];
            $response['body']['news'] = CardBuilder::blogCard($interview->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function useful(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.USEFUL'));
        if(!$data->isEmpty()) {
            $interview = new Posts(['post_type' => 'interview']);
            $news = new Posts(['post_type' => 'news']);
            $blog = new Posts(['post_type' => 'blog']);
            $response['body'] = self::dataMetaDecode($data[0]);

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_INTERVIEW_USEFUL,
            ];
            $response['body']['interview'] = CardBuilder::blogCard($interview->getPublicPosts($settings));

            $settings['limit'] = self::LIMIT_BLOG_USEFUL;
            $response['body']['blog'] = CardBuilder::blogCard($blog->getPublicPosts($settings));

            $settings['limit'] = self::LIMIT_NEWS_USEFUL;
            $response['body']['news'] = CardBuilder::newsCard($news->getPublicPosts($settings));

            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $str = str_replace('<pre', '<div', $data->content);
        $str = str_replace('</pre', '</div', $str);
        $str = str_replace('&nbsp;', '', $str);
        $str = str_replace( '<p><br></p>', '', $str);
        $str = str_replace( '<p></p>', '', $str);
        $newData['content'] = htmlspecialchars_decode($str);
        return $newData;
    }
    public function search(Request $request){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        if($request->has('search_word')){
            $lang = $request->has('lang') ? $request->input('lang') : self::LANG;
            $response['body']['posts'] = CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $request->input('search_word')));
            $response['confirm'] = 'ok';
        }
        return response()->json($response);
    }
    public function siteMap(){
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $priority = 0.9;
        $data = [];
        $static_page = DB::table(self::TABLE)
                           ->where('status',  'public')
                           ->where('lang',  self::LANG)
                           ->get();

        foreach ($static_page as $item) {
            $data[] = [
                'url'        => $item->permalink === '/' ? $item->permalink : '/'.$item->permalink,
                'lastmod'    => $item->update_at,
                'changefreq' => 'daily',
                'priority'   => $item->permalink === '/' ? 1 : $priority
            ];
        }

        $category = DB::table(self::TABLE_CATEGORY)
                        ->where('status',  'public')
                        ->where('lang',  self::LANG)
                        ->get();
        foreach ($category as $item) {
            $data[] = [
                'url'  => '/'.$item->permalink,
                'lastmod'    => $item->update_at,
                'changefreq' => 'daily',
                'priority'   => 0.8
            ];
        }

        $posts = DB::table(self::TABLE_POSTS)
                    ->where('status',  'public')
                    ->where('lang',  self::LANG)
                    ->get();
        foreach ($posts as $item) {
            $data[] = [
                'url'        =>  '/'.$item->slug.'/'.$item->permalink,
                'lastmod'    => $item->update_at,
                'changefreq' => 'daily',
                'priority'   => 0.6
            ];
        }

        $response['body']['posts'] = $data;
        return response()->json($response);
    }
}



