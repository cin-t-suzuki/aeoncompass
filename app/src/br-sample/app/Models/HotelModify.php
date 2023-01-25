<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelModify extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_modify';
    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'hotel_cd';
    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';
    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'modify_status',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];
    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_MODIFY_STATUS = "modify_status";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";

    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
        $colModifyStatus = new ValidationColumn();
        $colModifyStatus->setColumnName($this->COL_MODIFY_STATUS, "更新ステータス")->require();


        parent::setColumnDataArray([$colHotelCd, $colModifyStatus]);
    }




}
