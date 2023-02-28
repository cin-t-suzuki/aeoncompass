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
    /**
     * 一覧
     */
    public function list(Request $request)
    {
        // ターゲットコード（施設コード）を取得
        $target_cd = $request->input('target_cd');

        $models_hotel_link = new HotelLink();

        // リンクページの情報取得
        $a_hotel_link_type1['values'] = $models_hotel_link->where(['type' => 1, 'hotel_cd' => $target_cd])->get();
        $a_hotel_link_type2['values'] = $models_hotel_link->where(['type' => 2, 'hotel_cd' => $target_cd])->get();
        $a_hotel_link_type3['values'] = $models_hotel_link->where(['type' => 3, 'hotel_cd' => $target_cd])->orderBy('order_no', 'asc')->get();

        return view('ctl.htlhotellink.list', [
            'target_cd' => $target_cd,
            'a_hotel_link_type1' => $a_hotel_link_type1,
            'a_hotel_link_type2' => $a_hotel_link_type2,
            'a_hotel_link_type3' => $a_hotel_link_type3,
        ]);
    }

    /**
     * 入力画面
     */
    public function new(Request $request)
    {
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

        $target_cd = $request->input('target_cd');

        // バリデーションエラー時はエラーメッセージ取得
        $errors = $request->session()->get('errors', []);

        return view('ctl.htlhotellink.new', [
            'target_cd' => $target_cd,
            'a_hotel_link' => $a_request_HotelLink,
            'errors'        => $errors
        ]);
    }

    /**
     *　新規登録処理
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(HtlHotelLinkRequest $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');
        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

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

        $Hotel_Link = new HotelLink();

        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            // データ更新の値を設定
            $link_create = $Hotel_Link->create([
                'hotel_cd'  => $target_cd, //ホテルコード
                'branch_no' => $branch_no, //枝番
                'order_no'  => $order_no,   //オーダーナンバー
                'type'      => $a_request_hotel_link['type'],
                'title'     => $a_request_hotel_link['title'],
                'url'       =>  $a_request_hotel_link['url'],
                'entry_cd'  => $a_attributes['entry_cd'],
                'entry_ts'  => $a_attributes['entry_ts'],
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['modify_ts'],
            ]);

            // 保存に失敗したときエラーメッセージ表示
            if (is_null($link_create)) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // new アクションに転送します
                return $this->new($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['ご希望のデータを登録できませんでした。']]);
            }

            // 施設情報ページの更新依頼
            $Hotel_Link->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
            'guides' => ['登録完了しました。']
        ]);
    }

    /**
     *　編集画面
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');
        $target_cd = $request->input('target_cd');

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
            ])->with(['errors' => ['ご希望のリンクページデータが見つかりませんでした。']]);
        }

        // その他ページのカウント格納
        if (!isset($a_request_hotel_link['othercount'])) {
            $a_request_hotel_link['othercount'] = null;
        }

        return view('ctl.htlhotellink.edit', [
            'target_cd' => $target_cd,
            'a_hotel_link' => $a_request_hotel_link
        ]);
    }

    /**
     * 更新処理
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(HtlHotelLinkRequest $request)
    {
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

        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

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
            return redirect()
                ->route('ctl.htl_hotel_link.list', [
                    'target_cd' => $target_cd,
                    'HotelLink[branch_no]' => $a_request_hotel_link['branch_no'],
                    'HotelLink[type]' => $a_hotel_link[0]['type'],
                    'HotelLink[url]' => $a_hotel_link[0]['url'],
                    'HotelLink[order_no]' => $a_hotel_link[0]['order_no'],
                    'HotelLink[othercount]' => $a_request_hotel_link['othercount'],
                    'HotelLink[title]' => $a_request_hotel_link['title'],
                    'HotelLink[url]' => $a_request_hotel_link['url'],
                ])->with(['errors' => ['ご希望のリンクページデータが見つかりませんでした。']]);
        }

        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            // データ更新
            $link_update = $Hotel_Link->where([
                'hotel_cd'  => $target_cd,                        // ホテルコード
                'branch_no' => $a_request_hotel_link['branch_no'] // 枝番
            ])->update([
                'title'     => $a_request_hotel_link['title'],
                'url'       =>  $a_request_hotel_link['url'],
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['modify_ts'],
            ]);

            // 更新処理
            if ($link_update == 0) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // new アクションに転送します
                return redirect()
                    ->route('ctl.htl_hotel_link.edit', [
                        'target_cd' => $target_cd,
                        'HotelLink[branch_no]' => $a_request_hotel_link['branch_no'],
                        'HotelLink[type]' => $a_hotel_link[0]['type'],
                        'HotelLink[url]' => $a_hotel_link[0]['url'],
                        'HotelLink[order_no]' => $a_hotel_link[0]['order_no'],
                        'HotelLink[othercount]' => $a_request_hotel_link['othercount'],
                        'HotelLink[title]' => $a_request_hotel_link['title'],
                        'HotelLink[url]' => $a_request_hotel_link['url'],
                    ])->with(['errors' => ['ご希望のリンクページデータを更新できませんでした。']]);
            }

            // 施設情報ページの更新依頼
            $Hotel_Link->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
            'guides' => ['更新が完了しました。']
        ]);
    }

    /**
     * 削除処理
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');
        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

        $Hotel_Link = new HotelLink();

        // キーに関連付くデータを取得
        $a_hotel_link = $Hotel_Link->where(
            [
                'hotel_cd' => $target_cd,
                'branch_no' => $a_request_hotel_link['branch_no']
            ]
        )->get();

        // ホテルコードに絡むデータを設定
        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['branch_no'] = $a_request_hotel_link['branch_no'];
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            if (count($a_hotel_link) > 0) {
                $Hotel_Link->destroyAction($a_attributes);
            } else {
                // エラーメッセージ
                // list アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['ご希望のリンクページデータが見つかりませんでした。']]);
            }

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
    }

    /**
     * オーダーNO変更処理
     *
     * @param HtlHotelLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function changeorderno(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_hotel_link = $request->input('HotelLink');
        $target_cd = $request->input('target_cd');
        $order = $request->input('order');
        $actionCd = $this->getActionCd();

        // Hotel_Link モデルを取得
        $Hotel_Link = new HotelLink();
        $a_hotel_link_type3['values'] = $Hotel_Link->where([
            'type' => 3,
            'hotel_cd' => $target_cd
        ])->get();

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

        // 更新データ設定
        $a_attributes['hotel_cd'] = $target_cd;
        $a_attributes['entry_cd'] = $actionCd;
        $a_attributes['entry_ts'] = now();
        $a_attributes['modify_cd'] = $actionCd;
        $a_attributes['modify_ts'] = now();

        try {
            // トランザクション開始
            DB::beginTransaction();

            $after_link_update = $Hotel_Link->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_hotel_link['branch_no']
                ]
            )->update([
                'order_no'  => $after_order_no,
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['entry_ts']
            ]);
            if ($after_link_update == 0) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['並べ変える事が出来ませんでした。']]);
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
                    'modify_cd' => $a_attributes['modify_cd'],
                    'modify_ts' => $a_attributes['entry_ts']
                ]);
            } else {
                $now_link_update = $Hotel_Link->where(
                    [
                        'hotel_cd'  => $target_cd,
                        'order_no' => $after_order_no
                    ]
                )->update([
                    'order_no'  => $now_order_no,
                    'modify_cd' => $a_attributes['modify_cd'],
                    'modify_ts' => $a_attributes['entry_ts']
                ]);
            }

            if ($now_link_update  == 0) {
                // ロールバック
                DB::rollback();
                // エラーメッセージ
                // edit アクションに転送します
                return $this->list($request, [
                    'target_cd' => $target_cd
                ])->with(['errors' => ['並べ変える事が出来ませんでした。']]);
            }

            // 施設情報ページの更新依頼
            $Hotel_Link->hotelModify($a_attributes);

            // コミット
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
            'guides' => ['並び替えが完了しました。']
        ]);
    }

    /**
     * コントローラ名とアクション名を取得して、ユーザーIDと連結
     * ユーザーID取得は暫定の為、書き換え替えが必要です。
     *
     * MEMO: app/Models/common/CommonDBModel.php から移植したもの
     * HACK: 適切に共通化したいか。
     * @return string
     */
    private function getActionCd()
    {
        $path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
        $pathList = explode('\\', $path[0]);
        $controllerName = str_replace("Controller", "", end($pathList)); // コントローラ名
        $actionName = $path[1]; // アクション名
        $userId = \Illuminate\Support\Facades\Session::get("user_id");   // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;

        return $actionCd;
    }
}
