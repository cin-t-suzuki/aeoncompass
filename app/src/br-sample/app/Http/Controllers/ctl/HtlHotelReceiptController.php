<?php
namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\HotelReceipt;
use App\Http\Requests\HtlHotelReceiptRequest;

class HtlHotelReceiptController extends _commonController
{
    // インデックス
    public function index(Request $request)
    {

        $target_cd = $request->input('target_cd');
        $a_hotel_receipt = $request->input('Hotel_Receipt');

        try {
            // 値があれば取得、無ければ初期表示設定
            $o_hotel_receipt = new HotelReceipt();

            // 情報のセット
            $a_hotel_receipt = $o_hotel_receipt->where(['hotel_cd' => $target_cd])->first();

            if (is_null($a_hotel_receipt)) {
                $a_hotel_receipt['receipt_policy'] = 1;
            };
            // dd($a_hotel_receipt);
            return view('ctl.htlhotelreceipt.index', [
                'hotel_receipt'  => $a_hotel_receipt,
                'target_cd'      => $target_cd
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 更新処理
    public function update(HtlHotelReceiptRequest $request)
    {

        $target_cd = $request->input('target_cd');
        $a_hotel_receipt = $request->input('Hotel_Receipt');

        try {
            // 領収書発行ポリシーを取り出す
            $n_receipt_policy = $a_hotel_receipt['receipt_policy'];

            // トランザクション開始
            DB::beginTransaction();

            // インスタンスの取得
            $o_hotel_receipt = new HotelReceipt();

            // 情報のセット
            $a_hotel_receipt = $o_hotel_receipt->where(['hotel_cd' => $target_cd])->first();
            $a_attributes = [];
            $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd
            $a_attributes['modify_ts'] = now();

            // 値が空の場合は新規
            if (empty($a_hotel_receipt)) {
                // 登録処理
                $hotel_receipt_create = $o_hotel_receipt->insert([
                    'hotel_cd' => $target_cd,
                    'receipt_policy' => $n_receipt_policy,
                    'entry_cd' => $a_attributes['entry_cd'],
                    'entry_ts' => $a_attributes['entry_ts'],
                    'modify_cd' => $a_attributes['modify_cd'],
                    'modify_ts' => $a_attributes['modify_ts'],
                ]);

                if (!$hotel_receipt_create) {
                    DB::rollback();
                    return $this->index($request, [
                        'target_cd' => $target_cd,
                    ])->with(['errors' => ['データの登録に失敗しました。']]);
                }
            } else {
                // 更新処理
                $hotel_receipt_update = $o_hotel_receipt->where([
                    'hotel_cd' => $target_cd
                ])->update([
                    'receipt_policy' => $n_receipt_policy,
                    'modify_cd' => $a_attributes['modify_cd'],
                    'modify_ts' => $a_attributes['modify_ts'],
                ]);

                if ($hotel_receipt_update == 0) {
                    // ロールバック
                    DB::rollback();
                    return $this->index($request, [
                        'target_cd' => $target_cd,
                    ])->with(['errors' => ['データの更新に失敗しました。']]);
                }
            }

            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['receipt_policy'] = $n_receipt_policy;

            // 施設情報ページを更新に設定
            $o_hotel_receipt->hotelModify($a_attributes);

            // コミット
            DB::commit();

            // 最新情報を取得
            $a_hotel_receipt = $o_hotel_receipt->where(['hotel_cd' => $target_cd])->first();

            // 完了メッセージ
            return view('ctl.htlhotelreceipt.index', [
                'hotel_receipt'  => $a_hotel_receipt,
                'target_cd'      => $target_cd,
                'guides'         => ['領収書発行ポリシーの更新が完了しました。']
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
