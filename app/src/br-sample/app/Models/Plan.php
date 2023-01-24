<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\common\CommonDBModel;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

/**
 * @プランマスタ
 */
class Plan extends CommonDBModel
{
    use Traits;

    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'plan';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, plan_id
     * MEMO: Laravel は複合主キーに対応していない
     *
     * @var string
     */
    // protected $primaryKey = 'hotel_cd';

    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_PLAN_ID = "plan_id";
    public string $COL_PLAN_TYPE = "plan_type";
    public string $COL_PLAN_NM = "plan_nm";
    public string $COL_CHARGE_TYPE = "charge_type";
    public string $COL_CAPACITY = "capacity";
    public string $COL_PAYMENT_WAY = "payment_way";
    public string $COL_STAY_LIMIT = "stay_limit";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_ACTIVE_STATUS = "active_status";
    public string $COL_DISPLAY_STATUS = "display_status";
    public string $COL_LABEL_CD = "label_cd";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";
    public string $COL_ACCEPT_S_YMD = "accept_s_ymd";
    public string $COL_ACCEPT_E_YMD = "accept_e_ymd";
    public string $COL_ACCEPT_E_DAY = "accept_e_day";
    public string $COL_ACCEPT_E_HOUR = "accept_e_hour";
    public string $COL_ACCEPT_STATUS = "accept_status";
    public string $COL_CHECK_IN = "check_in";
    public string $COL_CHECK_IN_END = "check_in_end";
    public string $COL_CHECK_OUT = "check_out";
    public string $COL_STAY_CAP = "stay_cap";
    public string $COL_USER_ORDER_NO = "user_side_order_no";

    /**
     * コンストラクタ
     */
    public function __construct() //function→public fuctionでいいか,使っていない
    {
        // カラム情報の設定
    }

    // 重点表示部屋プラン一覧取得
    //
    // aa_conditions
    //   pref_id 都道府県ID
    //   span    宿泊対象期間 0:検索日から0-6日後 7:検索日から7-35日後
    public function getRoomPlanPriorities($aa_conditions)
    {
        try {
            // if (zap_is_empty($aa_conditions['pref_id'])){
            //  throw new Exception('都道府県IDを設定してください。');
            // }
            // if (zap_is_empty($aa_conditions['span'])){
            //  throw new Exception('宿泊対象期間を設定してください。');
            // }

            //一旦上記をis_emptyに変えてみる（zap~は今後使わないと元ソースで追記アリ）
            if ($this->is_empty($aa_conditions['pref_id'])) {
                throw new Exception('都道府県IDを設定してください。');
            }
            if ($this->is_empty($aa_conditions['span'])) {
                throw new Exception('宿泊対象期間を設定してください。');
            }

            $a_conditions['pref_id'] = $aa_conditions['pref_id'];
            $a_conditions['span']    = $aa_conditions['span'];

            $s_sql =
                <<< SQL
				select	pref_id,
						span,
						wday,
						priority,
						hotel_cd,
						room_cd,
						plan_cd,
						display_status
				from	room_plan_priority
				where	pref_id  = :pref_id
					and	span     = :span
SQL;

            //データ取得
            $data = DB::select($s_sql, $a_conditions);

            //取得結果を返す
            $result = [];
            if (!is_null($data) && count($data) > 0) {
                foreach ($data as $row) {
                    $result[] = array(
                        "pref_id" => $row->pref_id,
                        "span" => $row->span,
                        "wday" => $row->wday,
                        "priority" => $row->priority,
                        "hotel_cd" => $row->hotel_cd,
                        "room_cd" => $row->room_cd,
                        "plan_cd" => $row->plan_cd,
                        "display_status" => $row->display_status
                    );
                }
            }
            return array('values' => $result);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
