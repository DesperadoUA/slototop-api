<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;
use App\Models\Cash;

class AdminPaymentController extends BaseController
{
    const POST_TYPE = 'payment';
    const MAIN_TABLE = 'payments';
    const META_TABLE = 'payment_meta';
    const CATEGORY_TABLE = 'payment_category';
    const CATEGORY_RELATIVE = 'payment_category_relative';

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
            $response['body']['payment_currency'] = self::relativePostPost($id, $this->tables['PAYMENT'],
                                                                                $this->tables['CURRENCY'],
                                                                                $this->tables['PAYMENT_CURRENCY_RELATIVE']);
            $response['body']['payment_type_payment'] = self::relativePostPost($id, $this->tables['PAYMENT'],
                                                                                    $this->tables['TYPE_PAYMENT'],
                                                                                    $this->tables['PAYMENT_TYPE_PAYMENT_RELATIVE']);
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
        self::updatePostPost($data_request['id'], $data_request['payment_currency'], $this->tables['PAYMENT'],
                                                                                     $this->tables['CURRENCY'],
                                                                                     $this->tables['PAYMENT_CURRENCY_RELATIVE']);
        self::updatePostPost($data_request['id'], $data_request['payment_type_payment'], $this->tables['PAYMENT'],
                                                                                         $this->tables['TYPE_PAYMENT'],
                                                                                         $this->tables['PAYMENT_TYPE_PAYMENT_RELATIVE']);

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
        if (isset($data['commission'])) {
            $newData['commission'] = $data['commission'];
        } else {
            $newData['commission'] = '';
        }
        if (isset($data['withdrawal_period'])) {
            $newData['withdrawal_period'] = $data['withdrawal_period'];
        } else {
            $newData['withdrawal_period'] = '';
        }
        return $newData;
    }

    protected static function dataMetaDecode($data)
    {
        $newData = [];
        $newData['site'] = $data->site;
        $newData['withdrawal'] = $data->withdrawal;
        $newData['commission'] = $data->commission;
        $newData['withdrawal_period'] = $data->withdrawal_period;
        return $newData;
    }
}