<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/** 施設管理サイト担当者
 *
 */
class Staff extends CommonDBModel
{
    use Traits;

    protected $table = "staff";

    // カラム
    const COL_STAFF_ID  = "staff_id";
    const COL_STAFF_NM  = "staff_nm";
    const COL_STAFF_CD  = "staff_cd";
    const COL_STAFF_STATUS  = "staff_status";
    const COL_EMAIL  = "email";


    /** コンストラクタ
     */
    public function __construct() //publicでいいか？使用しないが削除するとエラー
    {
        // カラム情報の設定
    }


    /**
     * 主キーで取得
     */
    public function selectByKey($staff_id)
    {
        $data = $this->where("staff_id", $staff_id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_STAFF_ID  => $data[0]->staff_id,
                self::COL_STAFF_NM  => $data[0]->staff_nm,
                self::COL_STAFF_CD  => $data[0]->staff_cd,
                self::COL_STAFF_STATUS  => $data[0]->staff_status,
                self::COL_EMAIL  => $data[0]->email
            ];
        }
        return null;
    }
}
