<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validate;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Костя
 * Date: 22.08.2021
 * Time: 11:40
 */
class BaseController extends Controller {
    const DEFAULT_POST_TYPE = 'default';
    const ARR_LANG = ['ru' => 1, 'ua' => 2];
    const SLUG = 'default';
    const OFFSET = 0;
    const LIMIT = 8;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'created_at';
    const LANG = 1;
    protected static function dataCommonDecode($data) {
        $newData =  [];
        $newData['id'] = $data->id;
        $newData['title'] =  htmlspecialchars_decode($data->title);
        $newData['status'] = $data->status;
        $newData['created_at'] = $data->created_at;
        $newData['updated_at'] = $data->updated_at;
        $newData['slug'] = $data->slug;
        $newData['content'] = $data->content;
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail'] = $data->thumbnail;
        $newData['permalink'] = $data->permalink;
        $newData['post_type'] = $data->post_type;
        return $newData;
    }
    protected static function dataValidateInsert($data, $main_table, $meta_table){
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['created_at'])) {
            $newData['created_at'] = $data['created_at'];
        }
        else {
            $newData['created_at'] = date('Y-m-d');
        }

        if(isset($data['updated_at'])) {
            $newData['updated_at'] = $data['updated_at'];
        }
        else {
            $newData['updated_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = Validate::textValidate($data['content']);
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(!isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkInsert($data['title'], $main_table, $meta_table);
        }

        if(isset($data['lang'])) {
            if(isset(self::ARR_LANG[$data['lang']])) {
                $newData['lang'] = self::ARR_LANG[$data['lang']];
            } else {
                $newData['lang'] = self::ARR_LANG['ru'];
            }
        }

        if(isset($data['post_type'])) {
            $newData['post_type'] = $data['post_type'];
        } else {
            $newData['post_type'] = self::DEFAULT_POST_TYPE;
        }

        if(isset($data['slug'])) {
            $newData['slug'] = $data['slug'];
        } else {
            $newData['slug'] = self::SLUG;
        }

        return $newData;
    }
    protected static function permalinkInsert($permalink, $main_table, $meta_table) {
        $permalink = str_slug($permalink);
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = $post->getByPermalink($new_permalink);
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
        }
    }
    protected static function categoryPermalinkInsert($permalink, $main_table) {
        $permalink = str_slug($permalink);
        $candidate = DB::table($main_table)->where('permalink', $permalink)->get();
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = DB::table($main_table)->where('permalink', $new_permalink)->get();
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
        }
    }
    protected static function dataValidateSave($id, $data, $main_table, $meta_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['created_at'])) {
            $newData['created_at'] = $data['created_at'];
        }
        else {
            $newData['created_at'] = date('Y-m-d');
        }

        if(isset($data['updated_at'])) {
            $newData['updated_at'] = $data['updated_at'];
        }
        else {
            $newData['updated_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($id, $data['permalink'], $main_table, $meta_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title'], $main_table, $meta_table);
        }
        else {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title'], $main_table, $meta_table);
        }

        return $newData;
    }
    protected static function dataValidateCategorySave($id, $data, $main_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['created_at'])) {
            $newData['created_at'] = $data['created_at'];
        }
        else {
            $newData['created_at'] = date('Y-m-d');
        }

        if(isset($data['updated_at'])) {
            $newData['updated_at'] = $data['updated_at'];
        }
        else {
            $newData['updated_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['permalink'], $main_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['title'], $main_table);
        }
        else {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['title'], $main_table);
        }

        return $newData;
    }
    protected static function dataValidateCategoryInsert($data, $main_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['created_at'])) {
            $newData['created_at'] = $data['created_at'];
        }
        else {
            $newData['created_at'] = date('Y-m-d');
        }

        if(isset($data['updated_at'])) {
            $newData['updated_at'] = $data['updated_at'];
        }
        else {
            $newData['updated_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::categoryPermalinkInsert($data['permalink'], $main_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::categoryPermalinkInsert($data['title'], $main_table);
        }
        else {
            $newData['permalink'] = self::categoryPermalinkInsert($data['title'], $main_table);
        }

        return $newData;
    }
    protected static function permalinkUpdate($id, $permalink, $main_table, $meta_table) {
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return str_slug($permalink);
        }
        else {
            if($candidate[0]->id === $id) return $permalink;
            else {
                $counter = 0;
                do {
                    $counter++;
                    $new_permalink = $permalink.'-'.$counter;
                    $new_candidate = $post->getByPermalink($new_permalink);
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
    protected static function permalinkCategoryUpdate($id, $permalink, $main_table) {
        $candidate = DB::table($main_table)
                         ->where('permalink', $permalink)
                         ->get();
        if($candidate->isEmpty()) {
            return str_slug($permalink);
        }
        else {
            if($candidate[0]->id === $id) return $permalink;
            else {
                $counter = 0;
                do {
                    $counter++;
                    $new_permalink = $permalink.'-'.$counter;
                    $new_candidate = DB::table($main_table)
                                         ->where('permalink', $permalink)
                                         ->get();
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
    protected static function checkParentCategorySave($data, $main_table){
        $newData['parent_id'] = 0;
        if(isset($data['parent_id'])) {
            if(!empty($data['parent_id'])){
                $current_post = DB::table($main_table)->where('id', $data['id'])->get();
                if(!$current_post->isEmpty()) {
                    $parent_post = DB::table($main_table)
                                       ->where('lang', $current_post[0]->lang)
                                       ->where('title', $data['parent_id'][0])
                                       ->get();
                    if(!$parent_post->isEmpty()) {
                        $newData['parent_id'] = $parent_post[0]->id;
                    }
                }
            }
        }
        return $newData;
    }
}