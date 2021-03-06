<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;
use App\Models\Cash;

class AdminBonusController extends BaseController
{
    const POST_TYPE = 'bonus';
    const MAIN_TABLE = 'bonuses';
    const META_TABLE = 'bonus_meta';
    const CATEGORY_TABLE = 'bonus_category';
    const CATEGORY_RELATIVE = 'bonus_category_relative';
    const TYPE_BONUS_TABLE = 'type_bonuses';
    const BONUS_TYPE_BONUS_RELATIVE = 'bonus_type_bonus_relative';
    const COUNTRY_TABLE = 'countries';
    const BONUS_COUNTRY_RELATIVE = 'bonus_country_relative';
    const BONUS_CASINO_RELATIVE = 'bonus_casino_relative';
    const CASINO_TABLE = 'casinos';

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
            $response['body']['bonus_type_bonus'] = self::relativePostPost($id, self::MAIN_TABLE,
                                                                                self::TYPE_BONUS_TABLE,
                                                                            self::BONUS_TYPE_BONUS_RELATIVE);
            $response['body']['bonus_country'] = self::relativePostPost($id, self::MAIN_TABLE,
                                                                                self::COUNTRY_TABLE,
                                                                                self::BONUS_COUNTRY_RELATIVE);
            $response['body']['bonus_casino'] = self::relativePostPost($id, self::MAIN_TABLE,
                                                                            self::CASINO_TABLE,
                                                                        self::BONUS_CASINO_RELATIVE);
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
        self::updatePostPost($data_request['id'], $data_request['bonus_type_bonus'], self::MAIN_TABLE,
                                                                                      self::TYPE_BONUS_TABLE,
                                                                                  self::BONUS_TYPE_BONUS_RELATIVE);
        self::updatePostPost($data_request['id'], $data_request['bonus_country'], self::MAIN_TABLE,
                                                                                  self::COUNTRY_TABLE,
                                                                              self::BONUS_COUNTRY_RELATIVE);
        self::updatePostPost($data_request['id'], $data_request['bonus_casino'], self::MAIN_TABLE,
                                                                                 self::CASINO_TABLE,
                                                                             self::BONUS_CASINO_RELATIVE);

        Cash::deleteAll();
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

        if (isset($data['close'])) {
            $newData['close'] = $data['close'];
        } else {
            $newData['close'] = 0;
        }
        if (isset($data['ref'])) {
            $newData['ref'] = json_encode($data['ref']);
        } else {
            $newData['ref'] = json_encode([]);
        }
        if (isset($data['wager'])) {
            $newData['wager'] = $data['wager'];
        } else {
            $newData['wager'] = '';
        }
        if (isset($data['number_use'])) {
            $newData['number_use'] = $data['number_use'];
        } else {
            $newData['number_use'] = '';
        }
        if (isset($data['value_bonus'])) {
            $newData['value_bonus'] = $data['value_bonus'];
        } else {
            $newData['value_bonus'] = '';
        }

        return $newData;
    }

    protected static function dataMetaDecode($data)
    {
        $newData = [];
        $newData['close'] = $data->close;
        $newData['ref'] = json_decode($data->ref, true);
        $newData['wager'] = $data->wager;
        $newData['number_use'] = $data->number_use;
        $newData['value_bonus'] = $data->value_bonus;

        return $newData;
    }
}