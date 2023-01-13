<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

class HotelInform extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_inform';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     *
     * (hotel_cd, branch_no) で PK になっているが、
     * Laravel では複合キーに対応していない
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
     * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
     * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
     *
     * @var bool
     */
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';


    protected $fillable = [
        'hotel_cd',
        'branch_no',
        'inform_type',
        'inform',
        'order_no',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_BRANCH_NO = "branch_no";
    public string $COL_INFORM_TYPE = "inform_type";
    public string $COL_INFORM = "inform";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";

    // コンストラクタ
    public function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
        $colBranchNo = new ValidationColumn();
        $colBranchNo->setColumnName($this->COL_BRANCH_NO, "枝番")->require()->length(0, 2)->currencyOnly();
        $ColInformType = new ValidationColumn();
        $ColInformType->setColumnName($this->COL_INFORM_TYPE, "連絡タイプ")->require();
        $colInform = new ValidationColumn();
        $colInform->setColumnName($this->COL_INFORM, "連絡事項")->require()->notHalfKana()->length(0, 800);
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "連絡事項表示順序")->length(0, 2)->intOnly();

        parent::setColumnDataArray([$colHotelCd, $colBranchNo, $ColInformType, $colInform, $colOrderNo]);
    }
}
