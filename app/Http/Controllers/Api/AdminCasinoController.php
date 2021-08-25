<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;

class AdminCasinoController extends BaseController
{
    const POST_TYPE = 'casino';
    const MAIN_TABLE = 'casinos';
    const META_TABLE = 'casino_meta';

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
        $data_save = self::dataValidateInsert($request->input('data'), self::META_TABLE, self::META_TABLE);
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
        //self::updateCategory($data_request['id'], $data_request['category']);

        return response()->json($response);
    }

    public function delete(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $data = $request->input('data');
        $post->deleteById($data->id);
        return response()->json($response);
    }

    protected static function dataValidateMetaSave($data)
    {
        $newData = [];
        if(isset($data['faq'])) {
            $newData['faq'] = json_encode($data['faq']);
        }
        else {
            $newData['faq'] = json_encode([]);
        }
        if(isset($data['reviews'])) {
            $newData['reviews'] = json_encode($data['reviews']);
        }
        else {
            $newData['reviews'] = json_encode([]);
        }
        if (isset($data['close'])) {
            $newData['close'] = $data['close'];
        } else {
            $newData['close'] = 0;
        }
        if (isset($data['rating'])) {
            $newData['rating'] = (int)$data['rating'];
        } else {
            $newData['rating'] = 0;
        }
        if (isset($data['ref'])) {
            $newData['ref'] = json_encode($data['ref']);
        } else {
            $newData['ref'] = json_encode([]);
        }
        if (isset($data['phone'])) {
            $newData['phone'] = $data['phone'];
        } else {
            $newData['phone'] = '';
        }
        if (isset($data['min_deposit'])) {
            $newData['min_deposit'] = $data['min_deposit'];
        } else {
            $newData['min_deposit'] = '';
        }
        if (isset($data['min_payments'])) {
            $newData['min_payments'] = $data['min_payments'];
        } else {
            $newData['min_payments'] = '';
        }
        if (isset($data['email'])) {
            $newData['email'] = $data['email'];
        } else {
            $newData['email'] = '';
        }
        if (isset($data['chat'])) {
            $newData['chat'] = $data['chat'];
        } else {
            $newData['chat'] = '';
        }
        if (isset($data['year'])) {
            $newData['year'] = $data['year'];
        } else {
            $newData['year'] = '';
        }
        if (isset($data['site'])) {
            $newData['site'] = $data['site'];
        } else {
            $newData['site'] = '';
        }
        if (isset($data['withdrawal'])) {
            $newData['withdrawal'] = $data['withdrawal'];
        } else {
            $newData['withdrawal'] = '';
        }
        if (isset($data['number_games'])) {
            $newData['number_games'] = $data['number_games'];
        } else {
            $newData['number_games'] = '';
        }

        return $newData;
    }

    protected static function dataMetaDecode($data)
    {
        $newData = [];
        $newData['faq'] = json_decode($data->faq, true);
        $newData['reviews'] = json_decode($data->reviews, true);
        $newData['close'] = $data->close;
        $newData['rating'] = (int)$data->rating;
        $newData['ref'] = $data->ref;
        $newData['phone'] = $data->phone;
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payments'] = $data->min_payments;
        $newData['email'] = $data->email;
        $newData['chat'] = $data->chat;
        $newData['year'] = $data->year;
        $newData['site'] = $data->site;
        $newData['withdrawal'] = $data->withdrawal;
        $newData['number_games'] = $data->number_games;
        return $newData;
    }
}