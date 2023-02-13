<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設管理サイト担当者
 * 
 */
class HotelPerson extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_person";
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
     * 使えるところでは自動入力を有効にするため、 true に設定。
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
        'person_post',
        'person_nm',
        'person_tel',
        'person_fax',
        'person_email',
        'entry_cd',
        // 'entry_ts',
        'modify_cd',
        // 'modify_ts',
    ];

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_PERSON_POST = "person_post";
	public string $COL_PERSON_NM = "person_nm";
	public string $COL_PERSON_TEL = "person_tel";
	public string $COL_PERSON_FAX = "person_fax";
	public string $COL_PERSON_EMAIL = "person_email";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); //TODO 独自チェック
		$colPersonPost = (new ValidationColumn())->setColumnName($this->COL_PERSON_POST, "担当者役職");//TODO チェック
		$colPersonNm = (new ValidationColumn())->setColumnName($this->COL_PERSON_NM, "担当者名称"); //TODO チェック
        $colPersonTel = (new ValidationColumn())->setColumnName($this->COL_PERSON_TEL, "担当者電話番号"); //TODO チェック
        $colPersonFax = (new ValidationColumn())->setColumnName($this->COL_PERSON_FAX, "担当者ファックス番号"); //TODO チェック
        $colPersonEmail = (new ValidationColumn())->setColumnName($this->COL_PERSON_EMAIL, "担当者電子メールアドレス"); //TODO チェック

        // 施設コード
        $colHotelCd->require();         // 必須入力チェック
        $colHotelCd->notHalfKana();     // 半角カナチェック
        $colHotelCd->length(0, 10);     // 長さチェック

        // 担当者役職
        $colPersonPost->notHalfKana();  // 半角カナチェック
        $colPersonPost->length(0, 32);  // 長さチェック

        // 担当者名称
        $colPersonNm->require();        // 必須入力チェック
        $colPersonNm->notHalfKana();    // 半角カナチェック
        $colPersonNm->length(0, 32);    // 長さチェック

        // 担当者電話番号
        $colPersonTel->require();       // 必須入力チェック
        $colPersonTel->notHalfKana();   // 半角カナチェック
        $colPersonTel->phoneNumber();   // 電話番号チェック
        $colPersonTel->length(0, 15);   // 長さチェック

        // 担当者ファックス番号
        $colPersonFax->notHalfKana();   // 半角カナチェック
        $colPersonFax->phoneNumber();   // 電話番号チェック
        $colPersonFax->length(0, 15);   // 長さチェック

        // 担当者電子メールアドレス
        $colPersonEmail->notHalfKana(); // 半角カナチェック
        $colPersonEmail->email();       // メールアドレスチェック
        $colPersonEmail->length(0, 128); // 長さチェック

		parent::setColumnDataArray([$colHotelCd, $colPersonPost, $colPersonNm, $colPersonTel, $colPersonFax, $colPersonEmail]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_PERSON_POST => $data[0]->person_post,
				$this->COL_PERSON_NM => $data[0]->person_nm,
				$this->COL_PERSON_TEL => $data[0]->person_tel,
				$this->COL_PERSON_FAX => $data[0]->person_fax,
				$this->COL_PERSON_EMAIL => $data[0]->person_email
			);
		}
		return null;
	}

}
