<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Common\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\HotelInform;

class HtlHotelInformController extends _commonController
{
    use Traits;

    //一覧
    public function list(Request $request)
    {
        // エラーメッセージの設定
        if ($request->session()->has('errors')) {
            // エラーメッセージ があれば、入力を保持して表示
            $errorList = $request->session()->pull('errors');
            $this->addErrorMessageArray($errorList);
        }

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            //ホテルコードに絡むinform_typeが0のものを取得
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);

            //ホテルコードに絡むinform_typeが1のものを取得
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function new(Request $request)
    {
        try {
            // リクエストパラメータの取得
            $a_request_Hotel_inform = $request->input('HotelInform');
            if (isset($a_request_Hotel_inform['inform'])) {
                $tmp_inform = str_replace("<", "＜", $a_request_Hotel_inform['inform']);
                $a_request_Hotel_inform['inform'] = str_replace(">", "＞", $tmp_inform);
            } else {
                $a_request_Hotel_inform['inform'] = '';
            }

            // ターゲットコード
            $target_cd = $request->input('target_cd');

            return view('ctl.htlHotelInform.new', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_Hotel_inform
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //新規登録処理
    public function create(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_Hotel_inform = $request->input('HotelInform');
        $tmp_inform = str_replace("<", "＜", $a_request_Hotel_inform['inform']);
        $a_request_Hotel_inform['inform'] = str_replace(">", "＞", $tmp_inform);

        // 「連絡事項」が空欄だった場合、null (バリデーションでrequire判定させるため)
        if (empty($a_request_Hotel_inform['inform'])) {
            $a_request_Hotel_inform['inform'] = null;
        }

        // 「連絡タイプ」が空欄だった場合、null (バリデーションでrequire判定させるため)
        if (!isset($a_request_Hotel_inform['inform_type'])) {
            $a_request_Hotel_inform['inform_type'] = null;
        }

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            //マスタ共通オブジェクト取得
            $models_hotel = new Hotel();
            //ブランチ番号取得
            $branch_no = $models_hotel->fill_counter('Hotel_Inform', 'branch_no', $target_cd);

            // オーダーナンバー取得
            // ホテルコードに絡むinform_typeが0のものはキャンセルを取得
            if ($a_request_Hotel_inform['inform_type'] == 0) {
                $a_hotel_inform = $this->getHotelInformCancel($target_cd);
            } else {
                $a_hotel_inform = $this->getHotelInformFree($target_cd);
            }
            $order_no = count($a_hotel_inform['values']);
            $order_no++;

            $Hotel_Inform = new HotelInform();

            $a_attributes = [];
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['branch_no'] = $branch_no;
            $a_attributes['inform_type'] = $a_request_Hotel_inform['inform_type'];
            $a_attributes['inform'] = $a_request_Hotel_inform['inform'];
            $a_attributes['order_no'] = $order_no;

            // バリデート結果を判断
            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);

            if (count($errorList) > 0) {
                DB::rollback();
                return $this->new($request, [
                    'target_cd' => $target_cd,
                ])->with(['errors' => $errorList]);
            }

            $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // データ登録
            $hotel_inform_insert = $Hotel_Inform->create([
                'hotel_cd' => $target_cd,
                'branch_no' => $branch_no,
                'inform_type' => $a_request_Hotel_inform['inform_type'],
                'inform' => $a_request_Hotel_inform['inform'],
                'order_no' => $order_no,
                'entry_cd' => $a_attributes['entry_cd'],   // TODO $this->box->info->env->action_cd
                'entry_ts' => $a_attributes['entry_ts'],
                'modify_cd' => $a_attributes['modify_cd'], // TODO $this->box->info->env->action_cd
                'modify_ts' => $a_attributes['modify_ts']
            ]);

            // 保存に失敗したときエラーメッセージ表示
            if (!$hotel_inform_insert) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                return $this->new($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のデータを登録できませんでした。']);
            }

            // 施設情報ページの更新依頼
            $Hotel_Inform->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            //branch_noとorder_noの整形
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                //オーダーNoの整形
                if ($order_no < $value->order_no) {
                    $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            //branch_noとorder_noの整形
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                //オーダーNoの整形
                if ($order_no < $value->order_no) {
                    $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            // list アクションに転送します
            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_Hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['登録完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function edit(Request $request)
    {
        // エラーメッセージの設定
        if ($request->session()->has('errors')) {
            // エラーメッセージ があれば、入力を保持して表示
            $errorList = $request->session()->pull('errors');
            $this->addErrorMessageArray($errorList);
        }

        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $tmp_inform = str_replace("<", "＜", $a_request_hotel_inform['inform']);
        $a_request_hotel_inform['inform'] = str_replace(">", "＞", $tmp_inform);

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // Hotel_Informインスタンス生成
            $Hotel_Inform = new HotelInform();

            // 件数チェック用　キーに関連付くデータを取得
            $a_hotel_inform_count = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->get();

            if (count($a_hotel_inform_count) == 0) {
                // エラーメッセージ
                return $this->list(
                    $request,
                    ['target_cd' => $target_cd]
                )->with(['errors' => 'ご希望の施設連絡事項データが見つかりませんでした。']);
            }

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->first();

            return view('ctl.htlHotelInform.edit', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_hotel_inform
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
    //更新処理
    public function update(Request $request)
    {
        // エラーメッセージの設定
        if ($request->session()->has('errors')) {
            // エラーメッセージ があれば、入力を保持して表示
            $errorList = $request->session()->pull('errors');
            $this->addErrorMessageArray($errorList);
        }

        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $tmp_inform = str_replace("<", "＜", $a_request_hotel_inform['inform']);
        $a_request_hotel_inform['inform'] = str_replace(">", "＞", $tmp_inform);

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // Hotel_Inform モデル の インスタンスを取得
            $Hotel_Inform = new HotelInform();

            // 件数チェック用　キーに関連付くデータを取得
            $a_hotel_inform_count = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->get();

            // 更新対象のテーブルがない場合、一覧画面へ戻る。
            if (count($a_hotel_inform_count) == 0) {
                // エラーメッセージ
                return $this->edit($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望の施設連絡事項データが見つかりませんでした。']);
            }

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->first();

            //inform_typeの変更判断
            if (intval($a_request_hotel_inform['inform_type']) != $a_hotel_inform['inform_type']) {
                //変更があった場合オーダーNoをType変更後方の最大数にする
                if (intval($a_request_hotel_inform['inform_type']) == 0) {
                    //ホテルコードセット
                    //注意事項の方のオーダーNoの最大値を取得
                    $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
                    $order_no = count($a_hotel_inform_cancel['values']);
                    $order_no++;
                } elseif ($a_request_hotel_inform['inform_type'] == 1) {
                    //ホテルコードセット
                    //その他記入欄の方のオーダーNoの最大値を取得
                    $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                    $order_no = count($a_hotel_inform_free['values']);
                    $order_no++;
                }
            } else {
                $order_no = $a_hotel_inform['order_no'];
            }
            // バリデートを実行
            $a_attributes = [];
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['branch_no'] = $a_request_hotel_inform['branch_no'];
            $a_attributes['inform_type'] = $a_request_hotel_inform['inform_type'];
            $a_attributes['order_no'] = $order_no;
            if ($a_request_hotel_inform['inform'] == "") {
                $a_attributes['inform'] = null;
            } else {
                $a_attributes['inform'] = $a_request_hotel_inform['inform'];
            }

            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);
            if (count($errorList) > 0) {
                DB::rollback();
                return $this->edit($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => $errorList]);
            }

            $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // inform_typeに変更がある場合 delete insert
            if ($a_request_hotel_inform['inform_type'] != $a_hotel_inform['inform_type']) {
                //inform_typeを注意事項からその他に変更した場合
                if (intval($a_request_hotel_inform['inform_type']) == 1) {
                    //ホテルコードセット
                    $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
                    //ホテルコードに絡むデータキャンセル部分のみ削除
                    foreach ($a_hotel_inform_cancel['values'] as $key => $hotel_inform_value) {
                        $Hotel_Inform->where([
                            'hotel_cd'   => $target_cd,
                            'branch_no'  => $hotel_inform_value->branch_no
                        ])->delete();
                    }

                    //branch_noとorder_noの整形
                    foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                        //オーダーNoの整形

                        if (intval($a_request_hotel_inform['order_no']) < $value->order_no) {
                            $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }

                    // 変更するデータ以外の物を登録
                    foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                        // 登録処理
                        $Hotel_Inform->create([
                            'hotel_cd'    => $target_cd,                    // ホテル
                            'branch_no'   => $value->branch_no,             // 枝番
                            'order_no'    => $value->order_no,              // 枝番
                            'inform_type' => '0',                           // 種別
                            'inform'      => $value->inform,                // コメント
                            'entry_cd'    => $a_attributes['entry_cd'],
                            'entry_ts'    => $a_attributes['entry_ts'],
                            'modify_cd'   => $a_attributes['modify_cd'],
                            'modify_ts'   => $a_attributes['modify_ts'],
                        ]);
                    }
                    // 変更するデータを更新
                    $Hotel_Inform->where(
                        [
                            'hotel_cd'  => $target_cd,
                            'branch_no' => $a_request_hotel_inform['branch_no']
                        ]
                    )
                        ->update(
                            [
                                'inform_type' => $a_request_hotel_inform['inform_type'],
                                'inform'      => $a_request_hotel_inform['inform'],
                                'order_no'    => $order_no,
                                'modify_cd'   => $a_attributes['modify_cd'],
                                'modify_ts'   => $a_attributes['modify_ts']
                            ]
                        );
                    $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
                    //branch_noとorder_noの整形
                    foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                        //オーダーNoの整形
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }

                    $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                    //branch_noとorder_noの整形
                    foreach ($a_hotel_inform_free['values'] as $key => $value) {
                        //オーダーNoの整形
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }
                } elseif (intval($a_request_hotel_inform['inform_type']) == 0) {
                    // その他に変更した場合
                    // ホテルコードセット
                    $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                    // ホテルコードに絡むデータキャンセル部分のみ削除
                    foreach ($a_hotel_inform_free['values'] as $key => $hotel_inform_value) {
                        $Hotel_Inform->where([
                            'hotel_cd'   => $target_cd,
                            'branch_no'  => $hotel_inform_value->branch_no
                        ])->delete();
                    }

                    // branch_noとorder_noの整形
                    foreach ($a_hotel_inform_free['values'] as $key => $value) {
                        //オーダーNoの整形
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }

                    //変更するデータ以外の物を登録
                    foreach ($a_hotel_inform_free['values'] as $key => $value) {
                        //登録処理
                        $Hotel_Inform->create([
                            'hotel_cd'    => $target_cd,                // ホテル
                            'branch_no'   => $value->branch_no,         // 枝番
                            'order_no'    => $value->order_no,          // 枝番
                            'inform_type' => '1',                       // 種別
                            'inform'      => $value->inform,            // コメント
                            'entry_cd'    => $a_attributes['entry_cd'],
                            'entry_ts'    => $a_attributes['entry_ts'],
                            'modify_cd'   => $a_attributes['modify_cd'],
                            'modify_ts'   => $a_attributes['modify_ts'],
                        ]);
                    }
                    // 変更するデータを更新
                    $Hotel_Inform->where(
                        [
                            'hotel_cd'  => $target_cd,
                            'branch_no' => $a_request_hotel_inform['branch_no']
                        ]
                    )
                        ->update(
                            [
                                'inform_type' => $a_request_hotel_inform['inform_type'],
                                'inform'      => $a_request_hotel_inform['inform'],
                                'order_no'    => $order_no,
                                'modify_cd'   => $a_attributes['modify_cd'],
                                'modify_ts'   => $a_attributes['modify_ts']
                            ]
                        );
                    $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
                    //branch_noとorder_noの整形
                    foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                        //オーダーNoの整形
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }

                    $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                    //branch_noとorder_noの整形
                    foreach ($a_hotel_inform_free['values'] as $key => $value) {
                        //オーダーNoの整形
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }
                }
            } else {
                // inform_typeに変更が無い場合 update
                $hotel_inform_update = $Hotel_Inform->where(
                    [
                        'hotel_cd'  => $target_cd,
                        'branch_no' => $a_request_hotel_inform['branch_no']
                    ]
                )
                    ->update(
                        [
                            'inform_type' => $a_request_hotel_inform['inform_type'],
                            'inform'      => $a_request_hotel_inform['inform'],
                            'order_no'    => $order_no,
                            'modify_cd'   => $a_attributes['modify_cd'],
                            'modify_ts'   => $a_attributes['modify_ts']
                        ]
                    );

                if (!$hotel_inform_update) {
                    // // ロールバック
                    DB::rollback();
                    // エラーメッセージ
                    return $this->edit($request, [
                        'target_cd' => $target_cd
                    ])->with(['errors' => 'ご希望の施設連絡事項データを更新できませんでした。']);
                }


                $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
                //branch_noとorder_noの整形
                foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                    //オーダーNoの整形
                    if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                        $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                    }
                }

                $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                //branch_noとorder_noの整形
                foreach ($a_hotel_inform_free['values'] as $key => $value) {
                    //オーダーNoの整形
                    if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                        $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                    }
                }
            }

            // 施設情報ページの更新依頼
            $Hotel_Inform->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['変更完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    //削除処理 注意事項
    public function deleteinform(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // ホテルコードインスタンス生成
            $Hotel_Inform = new HotelInform();

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->get();

            $a_attributes['branch_no'] = $a_request_hotel_inform['branch_no'];
            $a_attributes['order_no'] = $a_request_hotel_inform['order_no'];
            $a_attributes['inform'] = $a_request_hotel_inform['inform'];
            $a_attributes['hotel_cd'] = $target_cd;
            $a_attributes['entry_cd'] = 'entry_cd';        // TODO $this->box->info->env->action_cd,;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd';      // TODO $this->box->info->env->action_cd,;
            $a_attributes['modify_ts'] = now();

            if (count($a_hotel_inform) > 0) {
                $Hotel_Inform->destroyAction($a_attributes);
            } else {
                // エラーメッセージ
                // list アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ('ご希望の施設連絡事項データが見つかりませんでした。')]);
            }
            // コミット
            DB::commit();

            //branch_noとorder_noの整形
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                //オーダーNoの整形
                if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                    $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            //branch_noとorder_noの整形
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                //オーダーNoの整形
                if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                    $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['削除致しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    //削除処理 その他
    public function deleteother(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // ホテルコードインスタンス生成
            $Hotel_Inform = new HotelInform();

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->get();

            if (count($a_hotel_inform) > 0) {
                //ホテルコードに関連付くデータを全件取得
                $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
                $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);

                // ホテルコードに絡むデータキャンセル部分のみ削除（その他記入欄情報）
                foreach ($a_hotel_inform_free['values'] as $key => $hotel_inform_value) {
                    $a_attributes['branch_no'] = $hotel_inform_value->branch_no;
                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['entry_cd'] = 'entry_cd';        // TODO $this->box->info->env->action_cd,;
                    $a_attributes['entry_ts'] = now();
                    $a_attributes['modify_cd'] = 'modify_cd';      // TODO $this->box->info->env->action_cd,;
                    $a_attributes['modify_ts'] = now();

                    $Hotel_Inform->destroyAction($a_attributes);
                }

                // ホテルコードに絡むデータキャンセル部分のみ削除（注意事項情報）
                foreach ($a_hotel_inform_cancel['values'] as $key => $hotel_inform_value) {
                    $a_attributes['branch_no'] = $hotel_inform_value->branch_no;
                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['entry_cd'] = 'entry_cd';        // TODO $this->box->info->env->action_cd,;
                    $a_attributes['entry_ts'] = now();
                    $a_attributes['modify_cd'] = 'modify_cd';      // TODO $this->box->info->env->action_cd,;
                    $a_attributes['modify_ts'] = now();

                    $Hotel_Inform->destroyAction($a_attributes);
                }

                // 削除したいブランチの要素番号
                $branchKey = "";

                foreach ($a_hotel_inform_free['values'] as $key => $value) {
                    // 削除したいものは省く ブランチＮＯ
                    if ($a_request_hotel_inform['branch_no'] != $value->branch_no) {
                        if ($a_request_hotel_inform['branch_no'] < $value->branch_no) {
                            $a_hotel_inform_free['values'][$key]->branch_no = $value->branch_no - 1;
                        }
                    } else {
                        $branchKey = $key;
                    }

                    // 削除したいものは省く order_no
                    if ($a_request_hotel_inform['order_no'] != $value->order_no) {
                        if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                            $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                        }
                    }
                }
                // 削除したいデータ以外の物を登録（その他記入欄情報）
                foreach ($a_hotel_inform_free['values'] as $key => $value) {
                    if ($branchKey != $key) {
                        // バリデートを実行
                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $target_cd;
                        $a_attributes['branch_no'] = $value->branch_no;
                        $a_attributes['inform_type'] = '1';
                        $a_attributes['order_no'] = $value->order_no;
                        $a_attributes['inform'] = $value->inform;

                        $errorList = [];
                        $errorList = $Hotel_Inform->validation($a_attributes);
                        if (count($errorList) > 0) {
                            DB::rollback();
                            return $this->list($request, [
                                'target_cd' => $target_cd
                            ])->with(['errors' => $errorList]);
                        }

                        $Hotel_Inform->create([
                            'hotel_cd'    => $target_cd,        // ホテル
                            'branch_no'   => $value->branch_no, // 枝番
                            'order_no'    => $value->order_no,  // 表示順
                            'inform_type' => '1',               // 種別
                            'inform'      => $value->inform,    // コメント
                            'entry_cd'    => 'entry_cd',        // TODO $this->box->info->env->action_cd,
                            'entry_ts'    => now(),
                            'modify_cd'   => 'modify_cd',       // TODO $this->box->info->env->action_cd,
                            'modify_ts'   => now(),

                        ]);
                    }
                }
                foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                    // 削除したいものは省く ブランチＮＯ
                    if ($a_request_hotel_inform['branch_no'] != $value->branch_no) {
                        if ($a_request_hotel_inform['branch_no'] < $value->branch_no) {
                            $a_hotel_inform_cancel['values'][$key]->branch_no = $value->branch_no - 1;
                        }
                    }
                }
                // 削除したいデータ以外の物を登録（注意事項情報）
                foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                    // バリデートを実行
                    $a_attributes = [];
                    $a_attributes['hotel_cd'] = $target_cd;
                    $a_attributes['branch_no'] = $value->branch_no;
                    $a_attributes['inform_type'] = '0';
                    $a_attributes['order_no'] = $value->order_no;
                    $a_attributes['inform'] = $value->inform;

                    $errorList = [];
                    $errorList = $Hotel_Inform->validation($a_attributes);
                    if (count($errorList) > 0) {
                        DB::rollback();
                        return $this->list($request, [
                            'target_cd' => $target_cd
                        ])->with(['errors' => $errorList]);
                    }

                    $Hotel_Inform->create([
                        'hotel_cd'    => $target_cd,        //ホテル
                        'branch_no'   => $value->branch_no, //枝番
                        'order_no'    => $value->order_no,  //表示順
                        'inform_type' => '0',               //種別
                        'inform'      => $value->inform,    //コメント
                        'entry_cd'    => 'entry_cd',        // TODO $this->box->info->env->action_cd,
                        'entry_ts'    => now(),
                        'modify_cd'   => 'modify_cd',       // TODO $this->box->info->env->action_cd,
                        'modify_ts'   => now(),
                    ]);
                }

                // コミット
                DB::commit();
            } else {
                // エラーメッセージ
                // list アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ('ご希望の施設連絡事項データが見つかりませんでした。')]);
            }

            // branch_noとorder_noの整形（その他記入欄情報）
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                //オーダーNoの整形
                if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                    $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            // branch_noとorder_noの整形（注意事項情報）
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                //オーダーNoの整形
                if ($a_request_hotel_inform['order_no'] < $value->order_no) {
                    $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['削除致しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // オーダーNO変更処理 注意事項
    public function changeinformorder(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $target_cd = $request->input('target_cd');
        $order = $request->input('order');

        try {
            // ホテルコードセット
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);

            // トランザクション開始
            DB::beginTransaction();

            // Hotel_Inform モデル の インスタンスを取得
            $Hotel_Inform = new HotelInform();

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->first();

            // 現在のオーダーナンバーを取得
            $now_order_no = $a_hotel_inform['order_no'];

            // オーダーナンバーの増減後の値を取得
            if (isset($order['up']) && $order['up'] != "") {
                $after_order_no = $now_order_no - 1;
            } elseif ($order['down'] != "") {
                $after_order_no = $now_order_no + 1;
            }

            // タイプ３のリストからホテルコード・after_order_noの値が同じものブランチコードを取得
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                if ($after_order_no == $value->order_no) {
                    $after_branch_no = $value->branch_no;
                }
            }

            // バリデート
            $a_attributes = [];
            $a_attributes['order_no'] = $after_order_no; // オーダーナンバー
            $a_attributes['hotel_cd'] = $target_cd;

            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);
            if (count($errorList) > 0) {
                DB::rollback();
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => $errorList]);
            }

            $a_attributes['entry_cd'] = 'entry_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // 更新処理
            $after_order_no_update = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->update([
                'order_no'  => $after_order_no,
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['modify_ts']
            ]);

            if (!$after_order_no_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $after_branch_no
                ]
            )->first();

            // 変更される位置にいるレコードのオーダー番号を現在の位置へ戻す
            // バリデートを実行
            $a_attributes = [];
            $a_attributes['order_no'] = $now_order_no; // オーダーナンバー
            $a_attributes['hotel_cd'] = $target_cd;

            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);
            if (count($errorList) > 0) {
                DB::rollback();
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => $errorList]);
            };
            $a_attributes['entry_cd'] = 'entry_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            $now_order_no_update = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $after_branch_no
                ]
            )->update([
                'order_no'  => $now_order_no,
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['modify_ts']
            ]);

            // 更新処理
            if (!$now_order_no_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // 施設情報ページの更新依頼
            $Hotel_Inform->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            //branch_noとorder_noの整形
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                //オーダーNoの整形
                if ($now_order_no < $value->order_no) {
                    $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            //branch_noとorder_noの整形
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                //オーダーNoの整形
                if ($now_order_no < $value->order_no) {
                    $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['並び替えが完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }


    //オーダーNO変更処理 その他
    public function changeotherorder(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_inform = $request->input('HotelInform');
        $target_cd = $request->input('target_cd');
        $order = $request->input('order');

        try {
            //ホテルコードセット
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);

            // トランザクション開始
            DB::beginTransaction();

            // Hotel_Inform モデル の インスタンスを取得
            $Hotel_Inform = new HotelInform();

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->first();

            // 現在のオーダーナンバーを取得
            $now_order_no = $a_hotel_inform['order_no'];

            // オーダーナンバーの増減後の値を取得
            if (isset($order['up']) && $order['up'] != "") {
                $after_order_no = $now_order_no - 1;
            } elseif ($order['down'] != "") {
                $after_order_no = $now_order_no + 1;
            }

            // タイプ３のリストからホテルコード・after_order_noの値が同じものブランチコードを取得
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                if ($after_order_no == $value->order_no) {
                    $after_branch_no = $value->branch_no;
                }
            }

            // バリデートを実行
            $a_attributes = [];
            $a_attributes['order_no'] = $after_order_no; // オーダーナンバー
            $a_attributes['hotel_cd'] = $target_cd;

            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);
            if (count($errorList) > 0) {
                DB::rollback();
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => $errorList]);
            }
            $a_attributes['entry_cd'] = 'entry_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // 更新処理
            $after_order_no_update = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_inform['branch_no']
                ]
            )->update([
                'order_no'  => $after_order_no,
                'modify_cd' => $a_attributes['modify_cd'], // TODO $this->box->info->env->action_cd
                'modify_ts' => $a_attributes['modify_ts']
            ]);

            if (!$after_order_no_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // キーに関連付くデータを取得
            $a_hotel_inform = $Hotel_Inform->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $after_branch_no
                ]
            )->first();


            // 変更される位置にいるレコードのオーダー番号を現在の位置へ戻す
            // バリデートを実行
            $a_attributes = [];
            $a_attributes['order_no'] = $now_order_no; // オーダーナンバー
            $a_attributes['hotel_cd'] = $target_cd;

            $errorList = [];
            $errorList = $Hotel_Inform->validation($a_attributes);
            if (count($errorList) > 0) {
                DB::rollback();
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => $errorList]);
            };
            $a_attributes['entry_cd'] = 'entry_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd'; // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // バリデート結果を判断
            $now_order_no_update = $Hotel_Inform->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $after_branch_no
                ]
            )->update([
                'order_no'  => $now_order_no,
                'modify_cd' => $a_attributes['modify_cd'], // TODO $this->box->info->env->action_cd
                'modify_ts' => $a_attributes['modify_ts']
            ]);

            // 更新処理
            if (!$now_order_no_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // 施設情報ページの更新依頼
            $Hotel_Inform->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            //branch_noとorder_noの整形
            $a_hotel_inform_cancel = $this->getHotelInformCancel($target_cd);
            foreach ($a_hotel_inform_cancel['values'] as $key => $value) {
                //オーダーNoの整形
                if ($now_order_no < $value->order_no) {
                    $a_hotel_inform_cancel['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            //branch_noとorder_noの整形
            $a_hotel_inform_free = $this->getHotelInformFree($target_cd);
            foreach ($a_hotel_inform_free['values'] as $key => $value) {
                //オーダーNoの整形
                if ($now_order_no < $value->order_no) {
                    $a_hotel_inform_free['values'][$key]->order_no = $value->order_no - 1;
                }
            }

            return view('ctl.htlHotelInform.list', [
                'target_cd' => $target_cd,
                'a_hotel_inform' => $a_request_hotel_inform,
                'a_hotel_inform_cancel' => $a_hotel_inform_cancel,
                'a_hotel_inform_free' => $a_hotel_inform_free,
                'guides' => ['並び替えが完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 施設注意事項情報の取得
    //
    // this->_s_hotel_cd 施設コード
    //
    public function getHotelInformCancel($hotel_cd)
    {
        try {
            $s_sql =
                <<<SQL
					select	hotel_inform.hotel_cd,
							hotel_inform.branch_no,
							hotel_inform.inform,
							hotel_inform.order_no
					from	hotel_inform
					where	hotel_inform.hotel_cd = :hotel_cd
						and	hotel_inform.inform_type = 0
					order by hotel_inform.order_no
SQL;

            // データの取得
            $a_getHotelInformCancel['values'] = ['values' => DB::select($s_sql, ['hotel_cd' => $hotel_cd])];
            return $a_getHotelInformCancel['values'];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 施設その他記入欄情報の取得
    //
    // this->_s_hotel_cd 施設コード
    //
    public function getHotelInformFree($hotel_cd)
    {
        try {
            $s_sql =
                <<<SQL
					select	hotel_inform.hotel_cd,
							hotel_inform.branch_no,
							hotel_inform.inform,
							hotel_inform.order_no
					from	hotel_inform
					where	hotel_inform.hotel_cd = :hotel_cd
						and	hotel_inform.inform_type = 1
					order by hotel_inform.order_no
SQL;

            // データの取得
            $a_getHotelInformFree['values'] = ['values' => DB::select($s_sql, ['hotel_cd' => $hotel_cd])];
            return $a_getHotelInformFree['values'];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
