<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\HotelControl;
use App\Http\Requests\HtlHotelChargeRoundRequest;

class HtlhotelChargeRoundController extends _commonController
{

    // インデックス
    public function index(Request $request)
    {
        try {
            // ターゲットコード
            $target_cd = $request->input('target_cd');

            // インスタンスの取得
            $o_hotel_control = new HotelControl();

            // 情報のセット
            $a_hotel_control = $o_hotel_control->where(['hotel_cd' => $target_cd])->first();

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            return view('ctl.htlhotelchargeround.index', [
                'target_cd'     => $target_cd,
                'hotel_control' => $a_hotel_control,
                'errors'        => $errors
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 丸め単位設定 の更新
    public function update(HtlHotelChargeRoundRequest $request)
    {
        $a_hotel_control = $request->input('Hotel_Control');
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // インスタンスの取得
            $o_hotel_control = new HotelControl();

            // 情報のセット
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;

            $hotel_chargeround_update = $o_hotel_control->where([
                'hotel_cd' => $target_cd
            ])->update([
                'charge_round' => $a_hotel_control['charge_round'],
                'modify_cd'    => $a_attributes['modify_cd'],
            ]);

            if (!$hotel_chargeround_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // 更新後失敗した場合indexアクションへ
                return $this->index($request, [
                    'target_cd'     => $target_cd,
                    'hotel_control' => $a_hotel_control
                ])->with(['errors' => 'ご希望のデータを登録できませんでした。']);
            }


            // 施設情報ページの更新依頼
            $o_hotel_control->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            return view('ctl.htlhotelchargeround.index', [
                'target_cd'     => $target_cd,
                'hotel_control' => $a_hotel_control,
                'guides'        => ['変更が完了しました。']
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
