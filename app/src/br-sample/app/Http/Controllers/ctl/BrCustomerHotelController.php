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

        // エラーメッセージの設定
        if ($request->session()->has('errors')) {
            // エラーメッセージ があれば、入力を保持して表示
            $errorList = $request->session()->pull('errors');
            $this->addErrorMessageArray($errorList);
        }

        // ガイドメッセージの設定
        if ($request->session()->has('guide')) {
            // ガイドメッセージ があれば、入力を保持して表示
            $guide = $request->session()->pull('guide');
            $this->addGuideMessage($guide);
        }

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
                $this->addErrorMessage('該当する契約先が見つかりません。');
            }
        }

        // ビュー情報を設定
        $this->addViewData("target_cd", $hotel_cd);
        $this->addViewData("customer_list", $a_customer_list['values']);
        $this->addViewData("customer_hotel", $a_customer_hotel['values'][0]);
        $this->addViewData("limit", ($request->input('limit') ?? $this->defaultCustomerHotelLimit));
        $this->addViewData("hotel", $a_hotel);
        $this->addViewData("keyword", $request->input('keyword'));
        $this->addViewData("like_type", $request->input('like_type'));

        // ビューを表示
        return view("ctl.brCustomerHotel.list", $this->getViewData());
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

        // ビュー情報を設定
        $this->addViewData("target_cd", $request->input('target_cd'));
        $this->addViewData("customer", $a_customer);
        $this->addViewData("customer_hotel", $a_customer_hotel['values']);
        $this->addViewData("limit", ($request->input('limit') ?? $this->defaultCustomerHotelLimit));
        $this->addViewData("keyword", $request->input('keyword'));
        $this->addViewData("like_type", $request->input('like_type'));

        // ビューを表示
        return view("ctl.brCustomerHotel.hotellist", $this->getViewData());
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
            $guide = '精算先を更新しました';

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
            $guide = '精算先を更新しました';
        }
        return redirect()->route('ctl.brCustomerHotel.list', [
            'target_cd' =>  $target_cd
        ])->with([
            'guide' => $guide
        ]);
    }
}
