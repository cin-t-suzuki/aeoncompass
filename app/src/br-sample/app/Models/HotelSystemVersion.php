<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelSystemVersion extends CommonDBModel
{
    // テーブル名称
    protected $table = 'hotel_system_version';

    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_SYSTEM_TYPE = "system_type";
    public string $COL_VERSION = "version";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";


    function __construct()
    {
        parent::__construct();

        // 施設コード
        $colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

        // システムページタイプ
        $colSystemType = (new ValidationColumn())->setColumnName($this->COL_SYSTEM_TYPE, "システムページタイプ")->require()->length(0, 12)->notHalfKana();

        // 値ID
        $colVersion = (new ValidationColumn())->setColumnName($this->COL_VERSION, "値ID")->length(0, 9)->intOnly();

        parent::setColumnDataArray([
            $colHotelCd,
            $colSystemType,
            $colVersion
        ]);
    }


    // シングルトンインスタンスを実装
    private static $_o_instance = null;
    public static function getInstance()
    {
        if (null === self::$_o_instance) {
            self::$_o_instance = new self();
        }
        return self::$_o_instance;
    }
}
