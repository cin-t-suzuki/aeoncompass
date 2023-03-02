<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

/**
 * 都道府県マスタ
 */
class MastWardZone extends CommonDBModel
{
    use Traits;

    protected $table = "mast_wardzone";
    // カラム
    const COL_WARDZONE_ID              = "wardzone_id";
    const COL_WARDZONE_NM              = "wardzone_nm";
    const COL_ORDER_NO             = "order_no";

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($wardzone_id)
    {
        $data = $this->where(self::COL_WARDZONE_ID, $wardzone_id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_WARDZONE_ID => $data[0]->wardzone_id,
                self::COL_WARDZONE_NM => $data[0]->wardzone_nm
            ];
        }
        return null;
    }

}
