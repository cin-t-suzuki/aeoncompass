<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\HotelService;
use App\Http\Requests\HtlHotelServiceRequest;

class HtlHotelServiceController extends _commonController
{
    // 一覧
    public function list(Request $request)
    {
        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // 施設要素マスタを取得
            $a_hotel_elements['values'] = $this->getHotelElements(['element_type' => 'service']);

            // サービス情報取得
            $a_hotel_service['values'] = $this->getHotelServices($target_cd);

            foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
                if (!empty($a_hotel_service['values'])) {
                    foreach ($a_hotel_service['values'] as $servicekey => $servicevalue) {
                        if ($servicevalue->element_id == $elementsvalue->element_id) {
                            $a_hotel_elements['values'][$elementskey]->servicevalue = $servicevalue->element_value_id;
                            break;
                        } else {
                            $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
                        }
                    }
                } else {
                    $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
                }
            }

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            return view('ctl.htlhotelservice.list', [
                'target_cd'               => $target_cd,                // ターゲットコード
                'hotel_service'           => $a_hotel_elements,         // エレメント情報
                'errors'                  => $errors
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
    // 新規登録処理
    public function create(HtlHotelServiceRequest $request)
    {
        // ターゲットコード
        $target_cd = $request->input('target_cd');
        // リクエストパラメータの取得
        $a_chk = $request->input('HotelService');

        // 施設要素マスタを取得
        $a_hotel_elements['values'] = $this->getHotelElements(['element_type' => 'service']);

        // サービス情報取得
        $a_hotel_service['values'] = $this->getHotelServices($target_cd);

        foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
            if (!empty($a_hotel_service['values'])) {
                foreach ($a_hotel_service['values'] as $servicekey => $servicevalue) {
                    if ($servicevalue->element_id == $elementsvalue->element_id) {
                        $a_hotel_elements['values'][$elementskey]->servicevalue = $servicevalue->element_value_id;
                        break;
                    } else {
                        $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
                    }
                }
            } else {
                $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
            }
        }

        try {
            // トランザクション開始
            DB::beginTransaction();

            // 施設サービスモデル
            $Hotel_Service = new HotelService();

            if (is_array($a_chk)) {
                foreach ($a_chk as $key => $value) {
                    $Hotel_Service_value = $Hotel_Service->where([
                        'hotel_cd'         => $target_cd,
                        'element_id'       => $key
                    ])->first();

                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
                    $a_attributes['entry_ts'] = now();
                    $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;
                    $a_attributes['modify_ts'] = now();

                    // データが存在しない場合は新規登録 存在する場合は更新処理
                    if ($Hotel_Service_value == "") {
                        // データ登録の値を設定
                        $Hotel_Service_create = $Hotel_Service->create([
                            'hotel_cd'         => $target_cd,
                            'element_id'       => $key,
                            'element_value_id' => $value,
                            'entry_cd'         => $a_attributes['entry_cd'],
                            'entry_ts'         => $a_attributes['entry_ts'],
                            'modify_cd'        => $a_attributes['modify_cd'],
                            'modify_ts'        => $a_attributes['modify_ts'],
                        ]);
                        // 保存に失敗したときエラーメッセージ表示
                        if (!$Hotel_Service_create) {
                            // ロールバック
                            DB::rollback();
                            // エラーメッセージ
                            return $this->list($request, [
                                'target_cd' => $target_cd,
                                'hotel_service' => $a_hotel_elements,         // エレメント情報
                            ])->with(['errors' => 'ご希望のサービスデータを登録できませんでした。']);
                        }
                    } else {
                        // 更新処理
                        $Hotel_Service_update = $Hotel_Service->where([
                            'hotel_cd'         => $target_cd,
                            'element_id'       => $key
                        ])->update([
                            'element_value_id' => $value,
                            'modify_cd'        => $a_attributes['modify_cd'],
                            'modify_ts'        => $a_attributes['modify_ts'],
                        ]);

                        if (!$Hotel_Service_update) {
                            // ロールバック
                            DB::rollback();
                            // エラーメッセージ
                            return $this->list($request, [
                                'target_cd' => $target_cd,
                                'hotel_service' => $a_hotel_elements,         // エレメント情報
                            ])->with(['errors' => 'ご希望のサービスデータを登録できませんでした。']);
                        }
                    }
                }
            }

            // 施設情報ページの更新依頼
            $Hotel_Service->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            // 更新後のサービス情報取得
            $a_hotel_service['values'] = $this->getHotelServices($target_cd);

            foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
                if (!empty($a_hotel_service['values'])) {
                    foreach ($a_hotel_service['values'] as $servicekey => $servicevalue) {
                        if ($servicevalue->element_id == $elementsvalue->element_id) {
                            $a_hotel_elements['values'][$elementskey]->servicevalue = $servicevalue->element_value_id;
                            break;
                        } else {
                            $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
                        }
                    }
                } else {
                    $a_hotel_elements['values'][$elementskey]->servicevalue = 0;
                }
            }
            return view('ctl.htlhotelservice.list', [
                'target_cd'               => $target_cd,                // ターゲットコード
                'hotel_service'           => $a_hotel_elements,         // エレメント情報
                'guides'    => ['変更しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 施設要素マスタを取得
    //
    //   aa_conditions
    //   element_type 要素タイプ hotel:施設関係 room:部屋関係 amenity:アメニティ service:サービス vicinity:周辺
    //
    public function getHotelElements($aa_conditions)
    {
        try {
            if (!empty($aa_conditions['element_type'])) {
                $s_element_type = 'and	mast_hotel_element.element_type = :element_type';
            }

            $s_sql =
                <<<SQL
				select	q1.element_id,
						q1.element_type,
						q1.element_nm,
						mast_hotel_element_value.element_value_id,
						mast_hotel_element_value.element_value_text
				from	mast_hotel_element_value,
					(
						select	mast_hotel_element.element_id,
								mast_hotel_element.element_type,
								mast_hotel_element.element_nm,
								mast_hotel_element.order_no
						from	mast_hotel_element
						where	null is null
							{$s_element_type}
					) q1
				where	mast_hotel_element_value.element_id = q1.element_id
				order by	q1.order_no, mast_hotel_element_value.order_no
SQL;

            // データの取得
            return DB::select($s_sql, $aa_conditions);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 特定施設のサービス(hotel_service)の取得
    //
    // target_cd 施設コード
    //
    public function getHotelServices($target_cd)
    {
        try {
            $s_sql =
                <<<SQL
					select	q2.element_id,
							q2.element_value_id,
							q2.element_nm,
							mast_hotel_element_value.element_value_text
					from	mast_hotel_element_value,
						(
							select	mast_hotel_element.element_nm,
									mast_hotel_element.order_no,
									q1.element_id,
									q1.element_value_id
							from	mast_hotel_element,
								(
									select	hotel_service.element_id,
											hotel_service.element_value_id
									from	hotel_service
									where	hotel_service.hotel_cd = :hotel_cd
										and	hotel_service.element_value_id != 0
								) q1
							where	mast_hotel_element.element_id = q1.element_id
						) q2
					where	mast_hotel_element_value.element_id = q2.element_id
						and	mast_hotel_element_value.element_value_id = q2.element_value_id
					order by q2.order_no
SQL;

            return DB::select($s_sql, ['hotel_cd' => $target_cd]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
