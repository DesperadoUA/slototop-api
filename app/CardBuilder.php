<?php
namespace App;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class CardBuilder {
    const BONUS_CASINO_DB = 'bonus_casino';
    const LENGTH_SHORT_DESC = 50;
   /* static function bonusCard($arr_bonuses){
        if(empty($arr_bonuses)) return [];
        $posts = [];
        foreach ($arr_bonuses as $item) {
            $current_casino_id = DB::table(self::BONUS_CASINO_DB)
                ->where('post_id', $item->post_id)
                ->get();
            if(!$current_casino_id->isEmpty()) {
                $casino_db = new Posts(['post_type' => 'casino']);
                $current_casino = $casino_db->getPublicPostById($current_casino_id[0]->relative_id);
                if(!$current_casino->isEmpty()) {
                    $posts[] = [
                        'permalink' => '/'.$item->slug.'/'.$item->permalink,
                        'title' => $current_casino[0]->title,
                        'icon'  => $current_casino[0]->icon,
                        'rating' => $item->rating,
                        'short_desc' => strip_tags(Str::substr(htmlspecialchars_decode($item->content), 0, self::LENGTH_SHORT_DESC).'...'),
                        'bonus' => $current_casino[0]->bonus,
                        'ref' => json_decode($current_casino[0]->ref),
                        'thumbnail' => $current_casino[0]->thumbnail,
                        'bonus_self' => htmlspecialchars_decode($item->bonus)
                    ];
                }
            }
        }
        return $posts;
    }
    static function casinoCard($arr_casino) {
        if(empty($arr_casino)) return [];
        $posts = [];
        foreach ($arr_casino as $item) {
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'regular_offers' => $item->regular_offers,
                'live_chat' => $item->live_chat,
                'live_casino' => $item->live_casino,
                'vip_program' => $item->vip_program,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'bonus' => $item->bonus,
                'bonus_wagering' => $item->bonus_wagering,
                'freespins' => $item->freespins,
                'freespins_wagering' => $item->freespins_wagering,
                'icon' => $item->icon,
                'title' => $item->title
            ];
        }
        return $posts;
    }
    static function slotCard($arr_slot) {
        if(empty($arr_slot)) return [];
        $posts = [];
        foreach ($arr_slot as $item) {
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'rtp' => $item->rtp,
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'icon' => $item->icon
            ];
        }
        return $posts;
    }
    static function mainSlotCard($arr_slot) {
        if(empty($arr_slot)) return [];
        $posts = [];
        foreach ($arr_slot as $item) {
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'rtp' => $item->rtp,
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'volatility' => $item->volatility,
                'number_rows' => $item->number_rows,
                'min_bet' => $item->min_bet,
                'max_bet' => $item->max_bet,
                'reels' => $item->reels,
                'pay_lines' => $item->pay_lines
            ];
        }
        return $posts;
    }
    static function categoryLinks($arr_category){
        if(empty($arr_category)) return [];
        $posts = [];
        foreach ($arr_category as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->permalink
            ];
        }
        return $posts;
    }
    static function vendorCard($arr_vendors){
        if(empty($arr_vendors)) return [];
        $posts = [];
        foreach ($arr_vendors as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'icon' => $item->icon
            ];
        }
        return $posts;
    }
    static function blogCard($arr_blog) {
        if(empty($arr_blog)) return [];
        $posts = [];
        foreach ($arr_blog as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
    static function newsCard($arr_news) {
        if(empty($arr_news)) return [];
        $posts = [];
        foreach ($arr_news as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'short_desc' => $item->short_desc,
                'create_at' => mb_substr($item->create_at, 0, 10)
            ];
        }
        return $posts;
    }
    static function paymentCard($arr_payments){
        if(empty($arr_payments)) return [];
        $posts = [];
        foreach ($arr_payments as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink
            ];
        }
        return $posts;
    }
    static function searchCard($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink
            ];
        }
        return $posts;
    }
   */
    static function searchAdminCard($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/admin/'.$item->post_type.'/'.$item->id
            ];
        }
        return $posts;
    }
}