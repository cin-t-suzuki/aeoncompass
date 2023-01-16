<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\HotelLink;
use App\Http\Requests\HtlHotelLinkRequest;

class HtlHotelLinkController extends _commonController
{
    //一覧
    public function list(Request $request)
    {
        try {
            // ターゲットコード
            $target_cd = $request->input('target_cd');

            $models_hotel_link = new HotelLink();
            // リンクページの情報取得
            $a_hotel_link_type1['values'] = $models_hotel_link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type2['values'] = $models_hotel_link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type3['values'] = $models_hotel_link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

            //アサイン登録
            return view('ctl.htlhotellink.list', [
                'target_cd' => $target_cd,
                'a_hotel_link_type1' => $a_hotel_link_type1,
                'a_hotel_link_type2' => $a_hotel_link_type2,
                'a_hotel_link_type3' => $a_hotel_link_type3,
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

            $a_request_HotelLink = $request->input('HotelLink');
            if (isset($a_request_HotelLink['title'])) {
                $tmp_title = str_replace("<", "＜", $a_request_HotelLink['title']);
                $a_request_HotelLink['title'] = str_replace(">", "＞", $tmp_title);
            } else {
                $a_request_HotelLink['title'] = '';
            }

            if (!isset($a_request_HotelLink['url'])) {
                $a_request_HotelLink['url'] = '';
            }

            // ターゲットコード
            $target_cd = $request->input('target_cd');

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            return view('ctl.htlhotellink.new', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_HotelLink,
                'errors'        => $errors
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */

    //新規登録処理
    public function create(HtlHotelLinkRequest $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            //マスタ共通オブジェクト取得
            $models_hotel = new Hotel();
            $branch_no = $models_hotel->fill_counter('hotel_link', 'branch_no', $target_cd);

            // オーダーナンバー取得
            if (intval($a_request_hotel_link['type']) == 3) {
                $order_no = $models_hotel->incrementCounter('Hotel_Link', 'order_no', $target_cd);
                if ($order_no < 3) {
                    $order_no = 3;
                }
            } elseif (intval($a_request_hotel_link['type']) == 2) {
                $order_no = 2;
            } elseif (intval($a_request_hotel_link['type']) == 1) {
                $order_no = 1;
            }

            // ホテルコードインスタンス生成
            $Hotel_Link = new HotelLink();

            // データ更新の値を設定
            $link_create = $Hotel_Link->create([
                'hotel_cd'  => $target_cd, //ホテルコード
                'branch_no' => $branch_no, //枝番
                'order_no'  => $order_no,   //オーダーナンバー
                'type'      => $a_request_hotel_link['type'], //枝番
                'title'     => $a_request_hotel_link['title'], //メールアドレス
                'url'       =>  $a_request_hotel_link['url'], //備考
                'entry_cd'  => 'entry_cd',  // TODO $this->box->info->env->action_cd
                'entry_ts'  => now(),
                'modify_cd' => 'modify_cd', // TODO $this->box->info->env->action_cd
                'modify_ts' => now(),
            ]);

            // 保存に失敗したときエラーメッセージ表示
            if (!$link_create) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // new アクションに転送します
                return $this->new($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のデータを登録できませんでした。']);
            }

            // コミット
            DB::commit();

            // リンクページの情報取得
            $a_hotel_link_type1['values'] = $Hotel_Link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type2['values'] = $Hotel_Link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type3['values'] = $Hotel_Link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

            return view('ctl.htlhotellink.list', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_hotel_link,
                'a_hotel_link_type1' => $a_hotel_link_type1,
                'a_hotel_link_type2' => $a_hotel_link_type2,
                'a_hotel_link_type3' => $a_hotel_link_type3,
                'guides' => ['登録完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function edit(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // Hotel_Linkインスタンス生成
            $Hotel_Link = new HotelLink();

            // キーに関連付くデータを取得
            $a_hotel_link_count = $Hotel_Link->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->get();

            if (count($a_hotel_link_count) == 0) {
                // list アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のリンクページデータが見つかりませんでした。']);
            }

            // その他ページのカウント格納
            if (!isset($a_request_hotel_link['othercount'])) {
                $a_request_hotel_link['othercount'] = null;
            }

            return view('ctl.htlhotellink.edit', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_hotel_link
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
    //更新処理
    public function update(HtlHotelLinkRequest $request)
    {
        try {
            $a_request_hotel_link = $request->input('HotelLink');
            if (isset($a_request_hotel_link['title'])) {
                $tmp_title = str_replace("<", "＜", $a_request_hotel_link['title']);
                $a_request_hotel_link['title'] = str_replace(">", "＞", $tmp_title);
            } else {
                $a_request_hotel_link['title'] = '';
            }

            if (!isset($a_request_hotel_link['url'])) {
                $a_request_hotel_link['url'] = '';
            }

            // ターゲットコード
            $target_cd = $request->input('target_cd');

            // トランザクション開始
            DB::beginTransaction();

            // Hotel_Link モデル取得
            $Hotel_Link = new HotelLink();

            // キーに関連付くデータを取得
            $a_hotel_link = $Hotel_Link->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->get();

            // 更新対象のテーブルがない場合、一覧画面へ戻る。
            if (count($a_hotel_link) == 0) {
                // エラーメッセージ
                // edit アクションに転送します
                return $this->edit($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のリンクページデータが見つかりませんでした。']);
            }

            // データ更新の値を設定
            $link_update = $Hotel_Link->where([
                'hotel_cd'  => $target_cd,                        // ホテルコード
                'branch_no' => $a_request_hotel_link['branch_no'] // 枝番
            ])->update([
                'title'     => $a_request_hotel_link['title'],    // メールアドレス
                'url'       =>  $a_request_hotel_link['url'],     // 備考
                'modify_cd' => 'modify_cd',                       // TODO $this->box->info->env->action_cd
                'modify_ts' => now(),
            ]);


            // 更新処理
            if (!$link_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // new アクションに転送します
                return $this->edit($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のリンクページデータを更新できませんでした。']);
            }

            // コミット
            DB::commit();

            // リンクページの情報取得
            $a_hotel_link_type1['values'] = $Hotel_Link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type2['values'] = $Hotel_Link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type3['values'] = $Hotel_Link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

            return view('ctl.htlhotellink.list', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_hotel_link,
                'a_hotel_link_type1' => $a_hotel_link_type1,
                'a_hotel_link_type2' => $a_hotel_link_type2,
                'a_hotel_link_type3' => $a_hotel_link_type3,
                'guides' => ['更新が完了しました。']
            ]);

            // バリデート失敗
        } catch (Exception $e) {
            throw $e;
        }
    }

    //削除処理
    public function delete(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            // ホテルコードインスタンス生成
            $Hotel_Link = new HotelLink();

            // キーに関連付くデータを取得
            $a_hotel_link = $Hotel_Link->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->get();

            if (count($a_hotel_link) > 0) {
                //ホテルコードに絡むデータ全削除
                $Hotel_Link->where(
                    [
                        'hotel_cd'   => $target_cd,
                        'branch_no'  => $a_request_hotel_link['branch_no']
                    ]
                )->delete();


                // 削除データのorder_noよりもorder_noが大きいデータは、order_noを-1する。（飛び番号を無くすため）
                if ($a_request_hotel_link["type"] == 3) {
                    $Hotel_Link->where('type', 3)->where('order_no', '>', $a_hotel_link[0]['order_no'])
                        ->decrement('order_no');
                }

                // コミット
                DB::commit();
            } else {
                // エラーメッセージ
                // list アクションに転送します

                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => 'ご希望のリンクページデータが見つかりませんでした。']);
            }

            // リンクページの情報取得
            $a_hotel_link_type1['values'] = $Hotel_Link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type2['values'] = $Hotel_Link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type3['values'] = $Hotel_Link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

            return view('ctl.htlhotellink.list', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_hotel_link,
                'a_hotel_link_type1' => $a_hotel_link_type1,
                'a_hotel_link_type2' => $a_hotel_link_type2,
                'a_hotel_link_type3' => $a_hotel_link_type3,
                'guides' => ['削除が完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    // オーダーNO変更処理
    public function changeorderno(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');
        // ターゲットコード
        $target_cd = $request->input('target_cd');
        $order = $request->input('order');

        try {
            // Hotel_Link モデル
            $Hotel_Link = new HotelLink();
            $a_hotel_link_type3['values'] = $Hotel_Link->where(['type' => 3, 'hotel_cd' => $target_cd])->get();

            // トランザクション開始
            DB::beginTransaction();

            // キーに関連付くデータを取得
            $a_hotel_link = $Hotel_Link->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->first();

            //現在のオーダーナンバーを取得
            $now_order_no = $a_hotel_link['order_no'];

            //オーダーナンバーの増減後の値を取得
            if (isset($order['up']) && $order['up'] != "") {
                $after_order_no = $now_order_no - 1;
            } elseif (isset($order['down']) && $order['down'] != "") {
                $after_order_no = $now_order_no + 1;
            }

            //タイプ３のリストからホテルコード・after_order_noの値が同じものブランチコードを取得
            foreach ($a_hotel_link_type3['values'] as $key => $value) {
                if ($after_order_no == $value->order_no) {
                    $after_branch_no = $value->branch_no;
                }
            }
            // 更新処理
            $after_link_update = $Hotel_Link->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->update([
                'order_no'  => $after_order_no,
                'modify_cd' => 'modify_cd', // TODO $this->box->info->env->action_cd
                'modify_ts' => now()
            ]);
            if (!$after_link_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // 変更される位置にいるレコードのオーダー番号を現在の位置へ戻す
            if (isset($after_branch_no)) {
                $now_link_update = $Hotel_Link->where(
                    [
                        'hotel_cd'  => $target_cd,
                        'branch_no' => $after_branch_no
                    ]
                )->update([
                    'order_no'  => $now_order_no,
                    'modify_cd' => 'modify_cd', // TODO $this->box->info->env->action_cd
                    'modify_ts' => now()
                ]);
            } else {
                $now_link_update = $Hotel_Link->where(
                    [
                        'hotel_cd'  => $target_cd,
                        'order_no' => $after_order_no
                    ]
                )->update([
                    'order_no'  => $now_order_no,
                    'modify_cd' => 'modify_cd', // TODO $this->box->info->env->action_cd
                    'modify_ts' => now()
                ]);
            }

            if (!$now_link_update) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => '並べ変える事が出来ませんでした。']);
            }

            // コミット
            DB::commit();

            // リンクページの情報取得
            $a_hotel_link_type1['values'] = $Hotel_Link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type2['values'] = $Hotel_Link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
            $a_hotel_link_type3['values'] = $Hotel_Link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

            return view('ctl.htlhotellink.list', [
                'target_cd' => $target_cd,
                'a_hotel_link' => $a_request_hotel_link,
                'a_hotel_link_type1' => $a_hotel_link_type1,
                'a_hotel_link_type2' => $a_hotel_link_type2,
                'a_hotel_link_type3' => $a_hotel_link_type3,
                'guides' => ['並び替えが完了しました。']
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
