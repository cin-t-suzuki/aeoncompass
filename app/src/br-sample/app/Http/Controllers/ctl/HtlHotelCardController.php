<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class HtlHotelCardController extends _commonController
{
    use Traits;

    //詳細
    public function show(Request $request)
    {
        try {

            // エラーメッセージの設定
            if ($request->session()->has('errors')) {
                // エラーメッセージ があれば、入力を保持して表示
                $errorList = $request->session()->pull('errors');
                $this->addErrorMessageArray($errorList);
            }

            //リクエストの取得
            $targetCd = $request->input('target_cd');
            $a_request_card = $request->input('HotelCard');
            $a_request_chk = $request->input('chk');

            //カード情報マスタの取得
            $a_mast_cards = $this->get_mast_cards();

            //施設に割り当てられたカード情報の取得
            $a_hotle_card = $this->getHotelCards($targetCd);

            if (is_array($a_mast_cards['values'])) {
                foreach ($a_mast_cards['values'] as $key => $value) {
                    foreach ($a_hotle_card['values'] as $valuechk) {
                        if ($value->card_id == $valuechk->card_id) {
                            $a_chk[$key] = true;
                            break;
                        } else {
                            $a_chk[$key] = false;
                        }
                    }
                }
            }

            if (isset($a_chk)) {
                return view('ctl.htlHotelCard.show', [
                    'target_cd' => $targetCd,
                    'a_hotelcard' => $a_mast_cards,
                    'a_chk' => $a_chk
                ]);
            } else {
                return view('ctl.htlHotelCard.show', [
                    'target_cd' => $targetCd,
                    'a_hotelcard' => $a_mast_cards,
                ]);
            }

            //テンプレートへ遷移
            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //更新
    public function update(Request $request)
    {
        try {
            //リクエストの取得
            $target_cd = $request->input('target_cd');
            $a_request_chk = $request->input('chk');

            //カード情報マスタの取得
            $a_mast_cards = $this->get_mast_cards();

            // ホテルコードインスタンス生成
            $hotel_card = new HotelCard();

            // トランザクション開始
            DB::beginTransaction();

            if (is_array($a_mast_cards['values'])) {
                foreach ($a_mast_cards['values'] as $key => $value) {
                    $a_attributes = [];
                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['card_id'] = $value->card_id;

                    // バリデート結果を判断
                    $errorList = [];
                    $errorList = $hotel_card->validation($a_attributes);
                    if (count($errorList) > 0) {
                        return $this->edit($request, [
                            'target_cd' => $target_cd,
                            'a_hotelcard' => $a_mast_cards,
                        ])->with(['errors' => 'ご希望の利用可能カードデータを登録できませんでした。']);
                    }

                    //ホテルコードとカードIDに絡むデータを削除
                    $hotel_card->destroyAction([
                        'hotel_cd' => $target_cd,
                        'card_id'  => $value->card_id,
                        'entry_cd' => 'entry_cd',   // $this->box->info->env->action_cd,
                        'entry_ts' => now(),
                        'modify_cd' => 'modify_cd', // $this->box->info->env->action_cd,
                        'modify_ts' => now()
                    ]);
                }
            }
            if (is_array($a_request_chk)) {
                foreach ($a_request_chk as $value) {
                    $a_attributes = [];
                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['card_id'] = $value;

                    // バリデート結果を判断
                    $errorList = [];
                    $errorList = $hotel_card->validation($a_attributes);
                    if (count($errorList) > 0) {
                        return $this->edit($request, [
                            'target_cd' => $target_cd,
                            'a_hotelcard' => $a_mast_cards,
                        ])->with(['errors' => 'ご希望の利用可能カードデータを登録できませんでした。']);
                    }

                    // データ更新
                    $hotel_card_insert = $hotel_card->saveAction([
                        'hotel_cd' => $target_cd,
                        'card_id'  => $value,
                        'entry_cd' => 'entry_cd',   // $this->box->info->env->action_cd,
                        'entry_ts' => now(),
                        'modify_cd' => 'modify_cd', // $this->box->info->env->action_cd,
                        'modify_ts' => now()        
                    ]);

                    // 保存に失敗したときエラーメッセージ表示
                    if (!$hotel_card_insert) {
                        // ロールバック
                        DB::rollback();
                        // エラーメッセージ
                        // edit アクションに転送します
                        return $this->edit($request, [
                            'target_cd' => $target_cd,
                            'a_hotelcard' => $a_mast_cards,
                        ])->with(['errors' => 'ご希望の利用可能カードデータを登録できませんでした。']);
                    }
                }
            }

            // コミット
            DB::commit();

            //施設に割り当てられたカード情報の取得
            $a_hotle_card = $this->getHotelCards($target_cd);

            if (is_array($a_mast_cards['values'])) {
                foreach ($a_mast_cards['values'] as $key => $value) {
                    foreach ($a_hotle_card['values'] as $valuechk) {
                        if ($value->card_id == $valuechk->card_id) {
                            $a_chk[$key] = true;
                            break;
                        } else {
                            $a_chk[$key] = false;
                        }
                    }
                }
            }

            // show アクションに転送
            if (isset($a_chk)) {
                return view('ctl.htlHotelCard.show', [
                    'target_cd' => $target_cd,
                    'a_hotelcard' => $a_mast_cards,
                    'a_chk' => $a_chk,
                    'guides' => ['登録完了しました。']
                ]);
            } else {
                return view('ctl.htlHotelCard.show', [
                    'target_cd' => $target_cd,
                    'a_hotelcard' => $a_mast_cards,
                    'guides' => ['登録完了しました。']
                ]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }




    // カードマスタを取得
    //
    // aa_conditions
    public function get_mast_cards($aa_conditions = [])
    {
        try {

            $s_sql =
                <<<SQL
						select	mast_card.card_id,
								mast_card.card_type,
								mast_card.card_nm
						from	mast_card
						where	null is null
						order by	mast_card.card_type, mast_card.card_id
SQL;

            // データの取得
            return ['values' => DB::select($s_sql, $aa_conditions)];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    private function getHotelCards($targetCd)
    {
        $sql = <<<SQL
                select
                    q1.hotel_cd,
                    mast_card.card_id,
                    mast_card.card_type,
                    mast_card.card_nm
                from
                    mast_card
                    inner join (
                        select
                            hotel_card.hotel_cd,
                            hotel_card.card_id
                        from
                            hotel_card
                        where
                            hotel_card.hotel_cd = :hotel_cd
                    ) q1
                        on mast_card.card_id = q1.card_id
                order by
                    mast_card.card_type,
                    mast_card.card_id
            SQL;
        return ['values' => collect(DB::select($sql, ['hotel_cd' => $targetCd]))];
    }
}
