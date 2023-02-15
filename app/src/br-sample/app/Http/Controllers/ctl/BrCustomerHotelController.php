<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\Customer;
use App\Models\CustomerHotel;
use Exception;

class BrCustomerHotelController extends _commonController
{
    use Traits;

    private $defaultCustomerHotelLimit = 100;

    /**
     * 検索一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $hotel_cd  = $request->input('target_cd');

        //エラー、ガイドメッセージがあれば取得
        $errors = $request->session()->get('errors', []);
        $guides = $request->session()->get('guides', []);


        $o_hotel      = new Hotel();
        $a_hotel      = $o_hotel->selectByKey($hotel_cd); //find→selectByKeyでいいか？

        $o_customer  = new Customer();
        $a_customer_hotel = $o_customer->getCustomer($hotel_cd);

        $a_option = [
            'search_type' => 'name',
            'like_type'   => $request->input('like_type'),
            'key'         => ($request->input('keyword') ?? $a_customer_hotel['values'][0]->customer_id) //customer_cdはない→customer_idでいいか？
        ];

        if (!$this->is_empty($a_customer_hotel['values'][0]->customer_id) || !$this->is_empty($request->input('keyword'))) { //customer_cdはない→customer_idでいいか？
            $a_customer_list  = $o_customer->getCustomerList($a_option);

            //書き替えあっているか？ if ($a_customer_list['values']['cnt'] == 0) {
            if (count($a_customer_list['values']) == 0) {
                $errors[] = '該当する契約先が見つかりません。';
            }
        }

        // ビューを表示
        return view('ctl.brCustomerHotel.list', [
            'target_cd'     => $hotel_cd,
            'customer_list'      => $a_customer_list['values'],
            'customer_hotel' => $a_customer_hotel['values'][0],
            'limit' => ($request->input('limit') ?? $this->defaultCustomerHotelLimit),
            'hotel'     => $a_hotel,
            'keyword' => $request->input('keyword'),
            'like_type'    => $request->input('like_type'),

            //error,guideメッセージがない時は空の配列を返す
            'errors'    => $errors ?? [],
            'guides'    => $guides ?? []
        ]);
    }

    /**
     * 指定施設関連施設一覧
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function hotellist(Request $request)
    {
        $o_customer  = new Customer();
        $a_customer = $o_customer->selectByKey($request->input('customer_id')); //find→selectByKeyでいいか？
        $a_customer_hotel = $o_customer->getCustomerHotel($request->input('customer_id'));

        // ビューを表示
        return view('ctl.brCustomerHotel.hotellist', [
            'target_cd'     => $request->input('target_cd'),
            'customer'      => $a_customer,
            'customer_hotel' => $a_customer_hotel['values'],
            'limit' => ($request->input('limit') ?? $this->defaultCustomerHotelLimit),
            'keyword' => $request->input('keyword'),
            'like_type'    => $request->input('like_type')
        ]);
    }

    /**
     * 精算先を変更
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function setting(Request $request)
    {

        // モデルを取得
        $customerHotelModel      = new CustomerHotel();
        $target_cd = $request->input('target_cd');

        $a_demand_hotel = $customerHotelModel->selectByKey($target_cd);

        // データが無かった場合は登録
        if ($this->is_empty($a_demand_hotel)) {
            $requestCustomerHotel['hotel_cd'] = $target_cd;
            $requestCustomerHotel['customer_id'] = $request->input('customer_id');

            // バリデーション
            //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
            $errorList = $customerHotelModel->validation($requestCustomerHotel);
            if (count($errorList) > 0) {
                $errorList[] = "精算先情報を更新できませんでした。 ";
                return redirect()->route('ctl.brCustomerHotel.list', [
                    'target_cd' =>  $target_cd
                ])->with([
                    'errors' => $errorList
                ]);
            }
            // 共通カラム値設定
            $customerHotelModel->setInsertCommonColumn($requestCustomerHotel);

            // コネクション
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $customerHotelModel, $requestCustomerHotel) {
                    // DB更新
                    $customerHotelModel->insert($con, $requestCustomerHotel);
                    //insertでいいか？
                });
            } catch (Exception $e) {
                $errorList[] = '精算先情報の登録処理でエラーが発生しました。';
            }
            // 更新エラー
            if (count($errorList) > 0 || !empty($dbErr)) {
                //改行いらないのでは？ $errorList[] = "更新エラー<br>トップページよりやり直してください ";
                $errorList[] = "更新エラー：トップページよりやり直してください ";
                return redirect()->route('ctl.brCustomerHotel.list', [
                    'target_cd' =>  $target_cd
                ])->with([
                    'errors' => $errorList
                ]);
            }
            $guides[] = '精算先を更新しました';

        // あった場合は更新
        } else {
            $requestCustomerHotel['hotel_cd'] = $target_cd; //hotel_cdもないとエラーになるので追記していいか？
            $requestCustomerHotel['customer_id'] = $request->input('customer_id');

            // バリデーション
            //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
            $errorList = $customerHotelModel->validation($requestCustomerHotel);
            if (count($errorList) > 0) {
                $errorList[] = "精算先情報を更新できませんでした。 ";
                // edit アクションに転送します
                return redirect()->route('ctl.brCustomerHotel.list', [
                    'target_cd' =>  $target_cd
                ])->with([
                    'errors' => $errorList
                ]);
            }
            // 共通カラム値設定
            $customerHotelModel->setUpdateCommonColumn($requestCustomerHotel);

            // 更新件数
            $dbCount = 0;
            // コネクション
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $customerHotelModel, $requestCustomerHotel, &$dbCount) {
                    // DB更新
                    $dbCount = $customerHotelModel->updateByKey($con, $requestCustomerHotel);
                    //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
                });
            } catch (Exception $e) {
                $errorList[] = '精算先情報の更新処理でエラーが発生しました。';
            }
            // 更新エラー
            if (
                $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
            ) {
                //改行いらないのでは？ $errorList[] = "更新エラー<br>トップページよりやり直してください ";
                $errorList[] = "更新エラー：トップページよりやり直してください ";
                // edit アクションに転送します
                return redirect()->route('ctl.brCustomerHotel.list', [
                    'target_cd' =>  $target_cd
                ])->with([
                    'errors' => $errorList
                ]);
            }
            $guides[] = '精算先を更新しました';
        }
        return redirect()->route('ctl.brCustomerHotel.list', [
            'target_cd' =>  $target_cd
        ])->with([
            'guides' => $guides
        ]);
    }
}
