<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;

class AdminGameController extends BaseController
{
    const POST_TYPE = 'game';
    const MAIN_TABLE = 'games';
    const META_TABLE = 'game_meta';
    const CATEGORY_TABLE = 'game_category';
    const CATEGORY_RELATIVE = 'game_category_relative';

    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $posts = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $settings = [
            'offset' => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit' => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by' => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang' => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) {
            $data[] = self::dataCommonDecode($item) + self::dataMetaDecode($item);
        }
        $response['body'] = $data;
        $response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $response['lang'] = config('constants.LANG')[$settings['lang']];

        return response()->json($response);

    }

    public function store(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_save = self::dataValidateInsert($request->input('data'), self::MAIN_TABLE, self::META_TABLE);
        $data_meta = self::dataValidateMetaSave($request->input('data'));
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $response['insert_id'] = $post->insert($data_save, $data_meta);
        $response['data_meta'] = $data_meta;

        return response()->json($response);
    }

    public function show($id)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);
            $response['body']['category'] = self::relativeCategoryPost($id, self::MAIN_TABLE,
                                                                         self::CATEGORY_TABLE,
                                                                          self::CATEGORY_RELATIVE);
            $response['confirm'] = 'ok';
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $data_request = $request->input('data');
        $data_save = self::dataValidateSave($data_request['id'], $request->input('data'), self::MAIN_TABLE, self::META_TABLE);
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $post->updateById($data_request['id'], $data_save);

        $data_meta = self::dataValidateMetaSave($data_request);
        $post->updateMetaById($data_request['id'], $data_meta);
        self::updateCategory($data_request['id'], $data_request['category'], self::MAIN_TABLE,
                                                                          self::CATEGORY_TABLE,
                                                                           self::CATEGORY_RELATIVE);

        return response()->json($response);
    }

    public function delete(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $post->deleteById($request->input('data'));
        return response()->json($response);
    }

    protected static function dataValidateMetaSave($data)
    {
        $newData = [];

        if (isset($data['banner'])) {
            $newData['banner'] = $data['banner'];
        }
        else {
            $newData['banner'] = '';
        }
        if(isset($data['gallery'])) {
            $newData['gallery'] = json_encode($data['gallery']);
        }
        else {
            $newData['gallery'] = json_encode([]);
        }
        if (isset($data['jackpot'])) {
            $newData['jackpot'] = $data['jackpot'];
        }
        else {
            $newData['jackpot'] = '';
        }
        if (isset($data['risk_game'])) {
            $newData['risk_game'] = $data['risk_game'];
        }
        else {
            $newData['risk_game'] = '';
        }
        if (isset($data['max_gain'])) {
            $newData['max_gain'] = $data['max_gain'];
        }
        else {
            $newData['max_gain'] = '';
        }
        if (isset($data['max_bet'])) {
            $newData['max_bet'] = $data['max_bet'];
        }
        else {
            $newData['max_bet'] = '';
        }
        if (isset($data['min_bet'])) {
            $newData['min_bet'] = $data['min_bet'];
        }
        else {
            $newData['min_bet'] = '';
        }
        if (isset($data['iframe'])) {
            $newData['iframe'] = $data['iframe'];
        }
        else {
            $newData['iframe'] = '';
        }
        if(isset($data['characters'])) {
            $newData['characters'] = json_encode($data['characters']);
        }
        else {
            $newData['characters'] = json_encode([]);
        }
        if(isset($data['details'])) {
            $newData['details'] = json_encode($data['details']);
        }
        else {
            $newData['details'] = json_encode([]);
        }

        return $newData;
    }

    protected static function dataMetaDecode($data)
    {
        $newData = [];
        $newData['banner'] = $data->banner;
        $newData['gallery'] = json_decode($data->gallery, true);
        $newData['jackpot'] = $data->jackpot;
        $newData['risk_game'] = $data->risk_game;
        $newData['max_gain'] = $data->max_gain;
        $newData['max_bet'] = $data->max_bet;
        $newData['min_bet'] = $data->min_bet;
        $newData['iframe'] = $data->iframe;
        $newData['characters'] = json_decode($data->characters, true);
        $newData['details'] = json_decode($data->details, true);

        return $newData;
    }
}