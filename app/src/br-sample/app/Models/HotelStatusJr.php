<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * 施設状況
 */
class HotelStatusJr extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_status_jr";
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
     * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
     * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
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
        'active_status',
        'judge_status',
        'last_modify_dtm',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
		'judge_s_dtm',
		'judge_dtm'	
    ];

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ACTIVE_STATUS = "active_status";
	public string $COL_JUDGE_STATUS = "judge_status";
	public string $COL_LAST_MODIFY_DTM = "last_modify_dtm";
	public string $COL_ENTRY_CD = "entry_cd";
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd";
	public string $COL_MODIFY_TS = "modify_ts";
	public string $COL_JUDGE_S_DTM = "judge_s_dtm";
	public string $COL_JUDGE_DTM = "judge_dtm";

    // カラム定数
    const JUDGE_STATUS_JUDGE = 0; // 審査中
    const JUDGE_STATUS_OK  	 = 1; // 審査OK
    const JUDGE_STATUS_NG    = 2; // 審査NG

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colActiveStatus = new ValidationColumn();
		$colActiveStatus->setColumnName($this->COL_ACTIVE_STATUS, "システム取扱状態")->length(0, 2)->intOnly();
		$colJudgeStatus = new ValidationColumn();
		$colJudgeStatus->setColumnName($this->COL_JUDGE_STATUS, "施設審査ステータス")->length(0, 2)->intOnly();
		$colLastModifyDtm = new ValidationColumn();
		$colLastModifyDtm->setColumnName($this->COL_LAST_MODIFY_DTM, "施設データ連携項目最終更新日時")->correctDate(); 
		$colJudgeSDtm = new ValidationColumn();
		$colJudgeSDtm->setColumnName($this->COL_JUDGE_S_DTM, "施設審査開始日時")->correctDate(); 
		$colJudgeDtm = new ValidationColumn();
		$colJudgeDtm->setColumnName($this->COL_JUDGE_DTM, "NTA審査日時")->correctDate(); 

		parent::setColumnDataArray([$colHotelCd, $colActiveStatus, $colJudgeStatus, $colLastModifyDtm, $colJudgeSDtm,$colJudgeDtm]);
	}
}
