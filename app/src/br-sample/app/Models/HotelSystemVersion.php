<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelSystemVersion extends CommonDBModel
{
    use HasFactory;

    // テーブル名称
    protected $table = 'hotel_system_version';
    /**
     * テーブルに関連付ける主キー
     *
     * MEMO: (hotel_cd, system_type) で PK になっているが、
     * Laravel では複合キーに対応していない
     *
     * @var string
     */
    // protected $primaryKey = 'hotel_cd';

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
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'system_type',
        'version',
        'entry_cd',
        // 'entry_ts',
        'modify_cd',
        // 'modify_ts',
    ];

    // カラム定数
    // システムページタイプ
    public const SYSTEM_TYPE_PLAN = 'plan'; // プランメンテナンス

    // システムバージョン（複数選択可）
    // ビット列による集合表現で管理
    // public const VERSION_1 = 1; // Ver1 (旧システム) 使用しない
    public const VERSION_2 = 2; // Ver2 (新システム)

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
}
