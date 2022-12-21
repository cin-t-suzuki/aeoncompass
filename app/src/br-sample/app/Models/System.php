<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\DateUtil;
use Exception;

/**
 * System
 */
class System extends CommonDBModel
{
    use Traits;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
    }

    // 管理画面お知らせ一覧を取得
    //
    public function getBroadcastMessages($s_hotel_cd = null)
    {
        try {
            //元ソース上部の非表示部分は削除で問題ないか？
            $s_no_spec_sql =
            <<<SQL
					select id
						, title
						, description
						, header_message
						, accept_s_dtm as accept_s_dtm -- 書き換え問題ないか
						, accept_e_dtm as accept_e_dtm -- 書き換え問題ないか
						, accept_header_s_dtm as accept_header_s_dtm -- 書き換え問題ないか
						, accept_header_e_dtm as accept_header_e_dtm -- 書き換え問題ないか
						from broadcast_message bm
						left outer join  broadcast_messages_hotel bmh 
						  on bm.id = bmh.broadcast_messages_id
						where (
								(now() between bm.accept_header_s_dtm and bm.accept_header_e_dtm)
							 or
								(now() between bm.accept_s_dtm and bm.accept_e_dtm)
							)
						  and bmh.broadcast_messages_id is null
						order by bm.accept_s_dtm desc
SQL;

            $a_no_spec_broadcast_messages = DB::select($s_no_spec_sql, []);

            $s_spec_sql =
            <<<SQL
					select id
                        , title
                        , description
                        , header_message
                        , accept_s_dtm as accept_s_dtm -- 書き換え問題ないか
                        , accept_e_dtm as accept_e_dtm -- 書き換え問題ないか
                        , accept_header_s_dtm as accept_header_s_dtm -- 書き換え問題ないか
                        , accept_header_e_dtm as accept_header_e_dtm -- 書き換え問題ないか
					from broadcast_message bm
					inner join  broadcast_messages_hotel bmh 
					   on bm.id = bmh.broadcast_messages_id
					where (
							(now() between bm.accept_header_s_dtm and bm.accept_header_e_dtm)
						   or
							(now() between bm.accept_s_dtm and bm.accept_e_dtm)
						  )
					  and bmh.hotel_cd = :hotel_cd
					order by bm.accept_s_dtm desc
SQL;

            $a_broadcast_messages = DB::select($s_spec_sql, ['hotel_cd' => $s_hotel_cd]);

            $a_merge_messages = array_merge($a_no_spec_broadcast_messages, $a_broadcast_messages);
            unset($a_no_spec_broadcast_messages);
            unset($a_broadcast_messages);

            $a_accept_s_dtm_sort = [];
            $a_id_sort = [];
            foreach ($a_merge_messages as $idx => $a_merge_message) {
                $a_accept_s_dtm_sort[$idx] = $a_merge_message->accept_s_dtm;
                $a_id_sort[$idx] = $a_merge_message->id;
            }
            array_multisort(
                $a_accept_s_dtm_sort,
                SORT_NUMERIC,
                SORT_DESC,
                $a_id_sort,
                SORT_NUMERIC,
                SORT_ASC,
                $a_merge_messages
            );

            unset($a_accept_s_dtm_sort);
            unset($a_id_sort);

            return [
                'values'     => $a_merge_messages,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            $s_error = $e->getMessage();
            $this->_debug_log($s_error);
            throw $e;
        }
    }

}
