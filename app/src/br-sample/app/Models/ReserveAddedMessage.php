<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class ReserveAddedMessage extends CommonDBModel
{
    use Traits;

    // カラム

    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * get_ReserveAddedMessage
     * 指定された予約に対する追加メッセージを取得
     *
     * @param string $s_reserve_cd 予約番号
     *
     * @return array
     */
    public function getReserveAddedMessage($s_reserve_cd)
    {
        try {
            $_s_sql =
            <<<SQL
select ram.reserve_cd
     , ram.msg_type
     , ram.msg_for_hotel
     , ram.msg_for_guest
  from
      reserve_added_message ram
where
      ram.reserve_cd = :reserve_cd
order by
      ram.msg_type
SQL;

            $_a_msg_list = DB::select(
                $_s_sql,
                [
                    'reserve_cd'        => $s_reserve_cd
                ]
            );
            $_reserve_added_messagae = [];
            if (!$this->is_empty($_a_msg_list)) {
                foreach ($_a_msg_list as $i => $row) {
                    $_reserve_added_messagae[] = [
                        'reserve_cd'       => $row['reserve_cd'], 'msg_type'                => $row['msg_type'], 'msg_for_hotel'           => $row['msg_for_hotel'], 'msg_for_guest'           => $row['msg_for_guest']
                    ];
                }
            }
            return $_reserve_added_messagae;
        } catch (Exception $e) {
            throw $e;
            return [];
        }
    }
}
