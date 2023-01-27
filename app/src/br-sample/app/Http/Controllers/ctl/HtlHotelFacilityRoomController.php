<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\HotelFacilityRoom;
use App\Http\Requests\HtlHotelFacilityRoomRequest;

class HtlHotelFacilityRoomController extends _commonController
{
    // 一覧
    public function list(Request $request)
    {
        // ターゲットコード
        $target_cd = $request->input('target_cd');

        // リクエストパラメータの取得
        $a_request_hotel_facility_room = $request->input('HotelFacilityRoom');

        try {
            // 施設要素マスタを取得
            $a_hotel_elements['values'] = $this->getHotelElements(['element_type' => 'room']);

            // 施設部屋設備情報取得
            $a_hotel_facility_room['values'] = $this->getHotelFacilityRooms($target_cd);

            if (is_array($a_hotel_elements['values'])) {
                foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
                    if (!empty($a_hotel_facility_room['values'])) {
                        foreach ($a_hotel_facility_room['values'] as $facilityroomvalue) {
                            if ($facilityroomvalue->element_id == $elementsvalue->element_id) {
                                $a_hotel_elements['values'][$elementskey]->facilityroomvalue = $facilityroomvalue->element_value_id;
                                break;
                            } else {
                                $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                            }
                        }
                    } else {
                        $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                    }
                }
            }
            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            return view('ctl.htlhotelfacilityroom.list', [
                'target_cd'                         => $target_cd,                             // ターゲットコード
                'hotel_facility_room'               => $a_hotel_elements,                      // エレメント情報
                'a_request_hotel_facility_room'     => $a_request_hotel_facility_room,         // リクエスト情報
                'errors'                            => $errors
            ]);
            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
    //新規登録処理
    public function create(HtlHotelFacilityRoomRequest $request)
    {
        // ターゲットコード
        $target_cd = $request->input('target_cd');

        // リクエストパラメータの取得
        $a_chk = $request->input('HotelFacilityRoom');

        // 施設要素マスタを取得
        $a_hotel_elements['values'] = $this->getHotelElements(['element_type' => 'room']);

        // 施設部屋設備情報取得
        $a_hotel_facility_room['values'] = $this->getHotelFacilityRooms($target_cd);

        if (is_array($a_hotel_elements['values'])) {
            foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
                if (!empty($a_hotel_facility_room['values'])) {
                    foreach ($a_hotel_facility_room['values'] as $facilityroomvalue) {
                        if ($facilityroomvalue->element_id == $elementsvalue->element_id) {
                            $a_hotel_elements['values'][$elementskey]->facilityroomvalue = $facilityroomvalue->element_value_id;
                            break;
                        } else {
                            $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                        }
                    }
                } else {
                    $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                }
            }
        }

        try {
            // トランザクション開始
            DB::beginTransaction();

            // ホテルコードインスタンス生成
            $Hotel_Facility_Room = new HotelFacilityRoom();

            if (is_array($a_chk)) {
                foreach ($a_chk as $key => $value) {
                    $Hotel_Facility_Room_value = $Hotel_Facility_Room->where([
                        'hotel_cd'         => $target_cd,
                        'element_id'       => $key
                    ])->first();

                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
                    $a_attributes['entry_ts'] = now();
                    $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;
                    $a_attributes['modify_ts'] = now();

                    //データが存在しない場合は新規登録 存在する場合は更新処理
                    if ($Hotel_Facility_Room_value == "") {

                        // データ登録の値を設定
                        $Hotel_Facility_Room_create = $Hotel_Facility_Room->create([
                            'hotel_cd'         => $target_cd,
                            'element_id'       => $key,
                            'element_value_id' => $value,
                            'entry_cd'         => $a_attributes['entry_cd'],
                            'entry_ts'         => $a_attributes['entry_ts'],
                            'modify_cd'        => $a_attributes['modify_cd'],
                            'modify_ts'        => $a_attributes['modify_ts'],
                        ]);

                        // 保存に失敗したときエラーメッセージ表示
                        if (!$Hotel_Facility_Room_create) {

                            // ロールバック
                            DB::rollback();
                            // エラーメッセージ
                            return $this->list($request, [
                                'target_cd'           => $target_cd,
                                'hotel_facility_room' => $a_hotel_elements,         // エレメント情報
                            ])->with(['errors' => 'ご希望の施設部屋設備データを登録できませんでした。']);
                        }
                    } else {
                        // 更新処理
                        $Hotel_Facility_Room_update = $Hotel_Facility_Room->where([
                            'hotel_cd'         => $target_cd,
                            'element_id'       => $key
                        ])->update([
                            'element_value_id' => $value,
                            'modify_cd'        => $a_attributes['modify_cd'],
                            'modify_ts'        => $a_attributes['modify_ts']
                        ]);


                        // 更新処理（ここでもバリデートが行われる。）
                        if (!$Hotel_Facility_Room_update) {
                            // ロールバック
                            DB::rollback();
                            // エラーメッセージ
                            return $this->list($request, [
                                'target_cd'           => $target_cd,
                                'hotel_facility_room' => $a_hotel_elements,         // エレメント情報
                            ])->with(['errors' => 'ご希望の施設部屋設備データを登録できませんでした。']);
                        }
                    }
                }
            }

            // 施設情報ページの更新依頼
            $Hotel_Facility_Room->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            // 更新後の施設部屋設備情報取得
            $a_hotel_facility_room['values'] = $this->getHotelFacilityRooms($target_cd);

            if (is_array($a_hotel_elements['values'])) {
                foreach ($a_hotel_elements['values'] as $elementskey => $elementsvalue) {
                    if (!empty($a_hotel_facility_room['values'])) {
                        foreach ($a_hotel_facility_room['values'] as $facilityroomvalue) {
                            if ($facilityroomvalue->element_id == $elementsvalue->element_id) {
                                $a_hotel_elements['values'][$elementskey]->facilityroomvalue = $facilityroomvalue->element_value_id;
                                break;
                            } else {
                                $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                            }
                        }
                    } else {
                        $a_hotel_elements['values'][$elementskey]->facilityroomvalue = 0;
                    }
                }
            }

            return view('ctl.htlhotelfacilityroom.list', [
                'target_cd'               => $target_cd,                             // ターゲットコード
                'hotel_facility_room'     => $a_hotel_elements,                      // エレメント情報
                'guides'                  => ['変更しました。']
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

    // 特定施設の部屋設備(hotel_facility_room)の取得
    //
    // this->_s_hotel_cd 施設コード
    //
    public function getHotelFacilityRooms($target_cd)
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
									select	hotel_facility_room.element_id,
											hotel_facility_room.element_value_id
									from	hotel_facility_room
									where	hotel_facility_room.hotel_cd = :hotel_cd
										and	hotel_facility_room.element_value_id != 0
								) q1
							where	mast_hotel_element.element_id = q1.element_id
						) q2
					where	mast_hotel_element_value.element_id = q2.element_id
						and	mast_hotel_element_value.element_value_id = q2.element_value_id
					order by q2.order_no
SQL;

            // データの取得
            return DB::select($s_sql, ['hotel_cd' => $target_cd]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
