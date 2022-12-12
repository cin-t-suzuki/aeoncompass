<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * ホテル
 */
class Hotel extends CommonDBModel
{
    use Traits;

    protected $table = "hotel";

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
    // public $timestamps = false;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        // 'order_no',
        'hotel_category',
        'hotel_nm',
        'hotel_kn',
        'hotel_old_nm',
        'postal_cd',
        'pref_id',
        'city_id',
        'ward_id',
        'address',
        'tel',
        'fax',
        'room_count',
        'check_in',
        'check_in_end',
        'check_in_info',
        'check_out',
        'midnight_status',
        'accept_status',
        'accept_auto',
        'accept_dtm',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

    // カラム
    public string $COL_HOTEL_CD         = "hotel_cd";
    public string $COL_ORDER_NO         = "order_no";
    public string $COL_HOTEL_CATEGORY   = "hotel_category";
    public string $COL_HOTEL_NM         = "hotel_nm";
    public string $COL_HOTEL_KN         = "hotel_kn";
    public string $COL_HOTEL_OLD_NM     = "hotel_old_nm";
    public string $COL_POSTAL_CD        = "postal_cd";
    public string $COL_PREF_ID          = "pref_id";
    public string $COL_CITY_ID          = "city_id";
    public string $COL_WARD_ID          = "ward_id";
    public string $COL_ADDRESS          = "address";
    public string $COL_TEL              = "tel";
    public string $COL_FAX              = "fax";
    public string $COL_ROOM_COUNT       = "room_count";
    public string $COL_CHECK_IN         = "check_in";
    public string $COL_CHECK_IN_END     = "check_in_end";
    public string $COL_CHECK_IN_INFO    = "check_in_info";
    public string $COL_CHECK_OUT        = "check_out";
    public string $COL_MIDNIGHT_STATUS  = "midnight_status";
    public string $COL_ACCEPT_STATUS    = "accept_status";
    public string $COL_ACCEPT_AUTO      = "accept_auto";
    public string $COL_ACCEPT_DTM       = "accept_dtm";

    // カラム定数
    // 施設区分 (hotel_category)
    public const CATEGORY_CAPSULE_HOTEL   = 'a'; // カプセルホテル
    public const CATEGORY_BUSINESS_HOTEL  = 'b'; // ビジネスホテル
    public const CATEGORY_CITY_HOTEL      = 'c'; // シティホテル
    public const CATEGORY_JAPANESE_INN    = 'j'; // 旅館
    // 予約受付状態
    public const ACCEPT_STATUS_STOPPING     = 0; // 停止中
    public const ACCEPT_STATUS_ACCEPTING    = 1; // 受付中
    // 予約受付状態手動更新
    public const ACCEPT_AUTO_AUTO       = 0; // 自動更新
    public const ACCEPT_AUTO_MANUAL     = 1; // 手動更新

    // 深夜受付すぺーたす
    public const MIDNIGHT_STATUS_STOP   = 0; // 停止中
    public const MIDNIGHT_STATUS_ACCEPT = 1; // 受付中

    /**
     * コンストラクタ
     */
    function __construct(){
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "ホテルコード")->require()->length(0, 10)->notHalfKana();
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "表示順序")->length(0, 10)->intOnly();
        //'a' => 'カプセルホテル','b' => 'ビジネスホテル','c' => 'シティホテル','j' => '旅館'
        $colHotelCategory = new ValidationColumn();
        $colHotelCategory->setColumnName($this->COL_HOTEL_CATEGORY, "施設区分")->notHalfKana(); //プルダウンなのでチェック不要
        $colHotelNm = new ValidationColumn();
        $colHotelNm->setColumnName($this->COL_HOTEL_NM, "ホテル名")->require()->length(0, 50)->notHalfKana();
        $colHotelKn = new ValidationColumn();
        $colHotelKn->setColumnName($this->COL_HOTEL_KN, "ホテル名称かな")->require()->length(0, 150)->notHalfKana()->kanaOnly();

        $colHotelOldNm = new ValidationColumn();
        $colHotelOldNm->setColumnName($this->COL_HOTEL_OLD_NM, "旧ホテル名称")->length(0, 50)->notHalfKana();
        $colPostalCd = new ValidationColumn();// activeRecordでは「〒」だが、チェックで漢字名
        $colPostalCd->setColumnName($this->COL_POSTAL_CD, "郵便番号")->require()->length(0, 8)->notHalfKana()->postal();
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県")->require()->length(0, 2)->intOnly();
        $colCityId = new ValidationColumn();
        $colCityId->setColumnName($this->COL_CITY_ID, "市")->require()->length(0, 20)->intOnly();
        $colWardId = new ValidationColumn();
        $colWardId->setColumnName($this->COL_WARD_ID, "区")->length(0, 20)->intOnly();

        $colAddress = new ValidationColumn();
        $colAddress->setColumnName($this->COL_ADDRESS, "住所")->require()->length(0, 100)->notHalfKana();
        $colTel = new ValidationColumn();
        $colTel->setColumnName($this->COL_TEL, "TEL")->require()->length(0, 15)->notHalfKana()->phoneNumber();
        $colFax = new ValidationColumn();
        $colFax->setColumnName($this->COL_FAX, "FAX")->length(0, 15)->notHalfKana()->phoneNumber();
        $colRoomCount = new ValidationColumn();
        $colRoomCount->setColumnName($this->COL_ROOM_COUNT, "部屋数")->length(0, 4)->intOnly();
        $colCheckIn = new ValidationColumn();
        $colCheckIn->setColumnName($this->COL_CHECK_IN, "チェックイン")->require()->length(0, 5)->notHalfKana()->checkInOutTime();
            // チェックイン時刻 独自チェック→checkInTimeFromTo

        $colCheckInEnd = new ValidationColumn();
        $colCheckInEnd->setColumnName($this->COL_CHECK_IN_END, "チェックイン終了時刻")->length(0, 5)->notHalfKana()->checkInOutTime();
        $colCheckInInfo = new ValidationColumn();
        $colCheckInInfo->setColumnName($this->COL_CHECK_IN_INFO, "チェックイン時刻コメント")->length(0, 75)->notHalfKana();
        $colCheckOut = new ValidationColumn();
        $colCheckOut->setColumnName($this->COL_CHECK_OUT, "チェックアウト")->require()->length(0, 5)->notHalfKana()->checkInOutTime();
        $colMidnightStatus = new ValidationColumn();
        $colMidnightStatus->setColumnName($this->COL_MIDNIGHT_STATUS, "深夜受付状態")->require();//TODO 独自チェック→ラジオボタンで不要
        $colAcceptStatus = new ValidationColumn();
        $colAcceptStatus->setColumnName($this->COL_ACCEPT_STATUS, "予約受付状態");//TODO 独自チェック 条件必須 更新では不要、深夜受付状態と同一→更新画面にない

        $colAcceptAuto = new ValidationColumn();
        $colAcceptAuto->setColumnName($this->COL_ACCEPT_AUTO, "予約受付状態自動更新有無");//TODO 条件必須 更新では不要 、独自チェック
        $colAcceptDtm = new ValidationColumn();
        $colAcceptDtm->setColumnName($this->COL_ACCEPT_DTM, "予約受付状態更新日時")->correctDate(); //TODO 更新画面にない

        parent::setColumnDataArray([
            $colHotelCd     , $colOrderNo       , $colHotelCategory , $colHotelNm       , $colHotelKn,
            $colHotelOldNm  , $colPostalCd      , $colPrefId        , $colCityId        , $colWardId,
            $colAddress     , $colTel           , $colFax           , $colRoomCount     , $colCheckIn,
            $colCheckInEnd  , $colCheckInInfo   , $colCheckOut      , $colMidnightStatus, $colAcceptStatus,
            $colAcceptAuto  , $colAcceptDtm
        ]);
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($hotelCd){
        $data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
        if(!is_null($data) && count($data) > 0){
            return array(
                $this->COL_HOTEL_CD         => $data[0]->hotel_cd,
                $this->COL_ORDER_NO         => $data[0]->order_no,
                $this->COL_HOTEL_CATEGORY   => $data[0]->hotel_category,
                $this->COL_HOTEL_NM         => $data[0]->hotel_nm,
                $this->COL_HOTEL_KN         => $data[0]->hotel_kn,

                $this->COL_HOTEL_OLD_NM     => $data[0]->hotel_old_nm,
                $this->COL_POSTAL_CD        => $data[0]->postal_cd,
                $this->COL_PREF_ID          => $data[0]->pref_id,
                $this->COL_CITY_ID          => $data[0]->city_id,
                $this->COL_WARD_ID          => $data[0]->ward_id,

                $this->COL_ADDRESS          => $data[0]->address,
                $this->COL_TEL              => $data[0]->tel,
                $this->COL_FAX              => $data[0]->fax,
                $this->COL_ROOM_COUNT       => $data[0]->room_count,
                $this->COL_CHECK_IN         => $data[0]->check_in,

                $this->COL_CHECK_IN_END     => $data[0]->check_in_end,
                $this->COL_CHECK_IN_INFO    => $data[0]->check_in_info,
                $this->COL_CHECK_OUT        => $data[0]->check_out,
                $this->COL_MIDNIGHT_STATUS  => $data[0]->midnight_status,
                $this->COL_ACCEPT_STATUS    => $data[0]->accept_status,

                $this->COL_ACCEPT_AUTO      => $data[0]->accept_auto,
                $this->COL_ACCEPT_DTM       => $data[0]->accept_dtm
            );
        }
        return null;
    }

	/**  キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByKey($con, $data){
		$result = $con->table($this->table)->where($this->COL_HOTEL_CD, $data[$this->COL_HOTEL_CD])->update($data);
		if(!$result){
				return "更新に失敗しました";
		}
		return "";
    }

// 条件キーの初期化と値設定
	public function getConditionsForSearch(
		$keywords, $pref_id, $entry_status, $stock_type
	){
		$conditions = array();
		$conditions['keywords'] = null;
		$conditions['pref_id'] = null;
		$conditions['entry_status'] = null;
		$conditions['stock_type'] = null;
		if(isset($keywords)){
			$conditions['keywords'] = $keywords;
		}

		if(isset($pref_id)){
			$conditions['pref_id'] = $pref_id;
		}

		if(isset($entry_status)){
			$conditions['entry_status'] = $entry_status;
		}

		if(isset($stock_type)){
			$conditions['stock_type'] = $stock_type;
		}
		return $conditions;
	}

	/** チェックイン時刻のFromToチェック
	 *
	 * @param [type] $timeFrom
	 * @param [type] $timeTo
	 * @return boolean 
	 */
	public function checkInTimeFromTo(&$errorList ,$timeFrom, $timeTo)
	{
		if (!($this->is_empty($timeTo))){
			if ($timeTo <= $timeFrom){
				$errorList[] = 'チェックイン終了時刻は、チェックインより後の時刻を設定してください。';
				return false;
			}
		}
		return true;
	}

	/**  表示順序番号などを求めます
	*   現在登録されている最大値 + 1 を取得します。
	*
	*  CoreHotel->hotel_cd 施設コード
	*  as_table_name       テーブル名称
	*/
	public function incrementCounter($as_table_name, $as_column_nm, $hotelCd, $aa_conditions = array()){
		try {

			// テーブル名称
			if ($this->is_empty($as_table_name)){
				throw new \Exception('テーブルを設定してください。');
			}

			// カラム名称
			if ($this->is_empty($as_column_nm)){
				throw new \Exception('カラムを設定してください。');
			}

			$a_conditions['hotel_cd'] = $hotelCd;

			// 条件
			$s_where = "";
			if (!($this->is_empty($aa_conditions))){
				foreach ($aa_conditions as $key => $value){
					$s_where .= '	and	' . $key . ' = :' . $key;
					$a_conditions[$key] = $value;
				}
			}

			$s_sql =<<< SQL
				select	max({$as_column_nm}) as value
				from	{$as_table_name}
				where	hotel_cd = :hotel_cd
				{$s_where}
				SQL;

			$a_row = DB::select($s_sql, $a_conditions);

			if ($this->is_empty($a_row[0]->value)){
				return 1;
			}

			return intval($a_row[0]->value) + 1;

		} catch (\Exception $e) {
				throw $e;
		}
	}

	/** 施設を検索
	 * ・keywordにより2種類の検索パターン
	 * ・施設管理を検索し、上記検索結果とマージ
	 *	aa_conditions
	 *    entry_status 登録状態
	 *    pref_id      都道府県ID
	 *    stock_type   仕入タイプ
	 *    keywords     キーワード
	 * @param [type] $errrorArr
	 * @param [type] $aa_conditions
	 * @return void
	 */
	public function search(&$errrorArr,  $aa_conditions){
	
		$resultArr[] = []; 
		// 条件のキーをチェックしなければ定義
		$s_pref_id = null;
		$s_hotel_status = null;
		$s_stock_type = null;

		// キーワード
		if($this->is_empty($aa_conditions['keywords'])){
			$errrorArr[] = 'キーワードを設定してください。';
			return $resultArr;
		}

		// 検索条件の初期化
		$a_conditions = array();

		// 登録状態
		if (!($this->is_empty($aa_conditions['entry_status']))){
			$s_hotel_status  = '	and	hotel_status.entry_status = :entry_status';
			$a_conditions['entry_status'] = $aa_conditions['entry_status'];
		}

		// 仕入タイプ  // TODO 使用機能があれば確認
		if (!($this->is_empty($aa_conditions['stock_type']))){
			$s_stock_type = '	and	hotel_control.stock_type = :stock_type';
			$a_conditions['stock_type'] = $aa_conditions['stock_type'];
		}

		// keywordsがemailアドレスだった場合、emailで検索
		if ($this->is_mail(trim($aa_conditions['keywords']))){
		/* TODO このケースになる機能を未実装
			// 都道府県ID　※emailで検索の場合はandではなくwhereへ変更
			if (!($this->is_empty($aa_conditions['pref_id']))){
				$s_pref_id = '	where	hotel.pref_id = :pref_id';
				$a_conditions['pref_id'] = $aa_conditions['pref_id'];
			}

			// モデルの取得
			$o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);

			// エンコード後、検索条件へ
			$a_conditions['email'] = $o_cipher->encrypt(trim($aa_conditions['keywords']));

			// emailアドレスで検索された場合
			$s_sql = <<< SQL
			select	q4.hotel_cd,
					q4.hotel_nm,
					q4.hotel_old_nm,
					q4.accept_status,
					q4.entry_status,
					q4.stock_type,
					mast_pref.pref_nm
			from	mast_pref,
				(
					select	q3.hotel_cd,
							q3.hotel_nm,
							q3.hotel_old_nm,
							q3.accept_status,
							q3.entry_status,
							q3.stock_type,
							q3.pref_id
					from	hotel_person,
						(
							select	q2.hotel_cd,
									q2.hotel_nm,
									q2.hotel_old_nm,
									q2.accept_status,
									q2.entry_status,
									hotel_control.stock_type,
									q2.pref_id
							from	hotel_control,
									(
										select	q1.hotel_cd,
												q1.hotel_nm,
												q1.hotel_old_nm,
												q1.accept_status,
												q1.pref_id,
												hotel_status.entry_status
										from	hotel_status,
											(
												select	hotel.hotel_cd,
														hotel.hotel_nm,
														hotel.hotel_old_nm,
														hotel.accept_status,
														hotel.pref_id
												from	hotel
													{$s_pref_id}
											) q1
										where	hotel_status.hotel_cd(+) = q1.hotel_cd
											{$s_hotel_status}
									) q2
							where	hotel_control.hotel_cd(+) = q2.hotel_cd
								{$s_stock_type}
						) q3
					where	hotel_person.hotel_cd(+) = q3.hotel_cd
					and person_email = :email
				) q4
			where	mast_pref.pref_id = q4.pref_id
			order by q4.hotel_cd
			SQL;
		*/
		} else {
		// emailアドレスで検索されなかった場合、keywordsで検索

			// 都道府県ID
			if (!($this->is_empty($aa_conditions['pref_id']))){
				$s_pref_id = '	and	hotel.pref_id = :pref_id';
				$a_conditions['pref_id'] = $aa_conditions['pref_id'];
			}

			$keyword1 = '%' . trim($aa_conditions['keywords']) . '%';
			$keyword1_kn = '%' . mb_convert_kana(trim($aa_conditions['keywords']), "KVC") . '%';
			$keyword2 = '%' . trim(str_replace('-', '', $aa_conditions['keywords'])) . '%';

			// keywordsで検索  sql文字列上限があるので注意
			$s_sql = <<< SQL
select	q3.hotel_cd,
		q3.hotel_nm,
		q3.hotel_old_nm,
		q3.accept_status,
		q3.entry_status,
		q3.stock_type,
		mast_pref.pref_nm
from	mast_pref,
	(
	select	q2.hotel_cd,
			q2.hotel_nm,
			q2.hotel_old_nm,
			q2.accept_status,
			q2.entry_status,
			hotel_control.stock_type,
			q2.pref_id
	from	hotel_control right outer join
		(
		select	q1.hotel_cd,
				q1.hotel_nm,
				q1.hotel_old_nm,
				q1.accept_status,
				q1.pref_id,
				hotel_status.entry_status
		from	hotel_status  right outer join
			(
			select	hotel.hotel_cd,
					hotel.hotel_nm,
					hotel.hotel_old_nm,
					hotel.accept_status,
					hotel.pref_id
			from	hotel
			where	(hotel_cd                       like '{$keyword1}' or
					postal_cd                       like '{$keyword1}' or
					address                         like '{$keyword1}' or
					hotel_nm                        like '{$keyword1}' or
					hotel_kn                        like '{$keyword1_kn}' or
					replace(tel,       '-', '')     like '{$keyword2}' or
					replace(fax,       '-', '')     like '{$keyword2}' or
					replace(postal_cd, '-', '')     like '{$keyword2}' )
					{$s_pref_id}
			) q1
				on	hotel_status.hotel_cd = q1.hotel_cd
			where	1=1
				{$s_hotel_status}
		) q2
		on	hotel_control.hotel_cd = q2.hotel_cd
		{$s_stock_type}
	) q3
where	mast_pref.pref_id = q3.pref_id
order by q3.hotel_cd
SQL;
		}

		// 施設の情報を取得
		$a_hotel = DB::select($s_sql, $a_conditions);

		/* 施設統括 検索
		*/
		// 	検索条件の初期化
		$a_conditions = array();

		// 登録状態
		if (!($this->is_empty($aa_conditions['entry_status']))){
			$s_hotel_status  = '	and	hotel_status.entry_status = :entry_status';
			$a_conditions['entry_status'] = $aa_conditions['entry_status'];
		}

		// 仕入タイプ
		if (!($this->is_empty($aa_conditions['stock_type']))){
			$s_stock_type = '	and	hotel_control.stock_type = :stock_type';
			$a_conditions['stock_type'] = $aa_conditions['stock_type'];
		}

		// キーワード
		if (!($this->is_empty($aa_conditions['keywords']))){
			$a_conditions['keyword'] = '%' . trim($aa_conditions['keywords']) . '%';
		}

		// 都道府県ID
		if (!($this->is_empty($aa_conditions['pref_id']))){
			$s_pref_id = '	and	hotel.pref_id = :pref_id';
			$a_conditions['pref_id'] = $aa_conditions['pref_id'];
		}
		// 施設統括名称で検索
		$s_sql = <<< SQL
select
	q5.hotel_cd,
	q5.hotel_nm,
	q5.hotel_old_nm,
	q5.accept_status,
	q5.entry_status,
	q5.stock_type,
	mast_pref.pref_nm,
	q5.supervisor_cd,
	q5.supervisor_nm
from	mast_pref ,
	(
	select	q4.hotel_cd,
		q4.hotel_nm,
		q4.hotel_old_nm,
		q4.accept_status,
		q4.entry_status,
		hotel_control.stock_type,
		q4.pref_id,
		q4.supervisor_cd,
		q4.supervisor_nm
	from	hotel_control right outer join
		(
		select	q3.hotel_cd,
			q3.hotel_nm,
			q3.hotel_old_nm,
			q3.accept_status,
			q3.pref_id,
			hotel_status.entry_status,
			q3.supervisor_cd,
			q3.supervisor_nm
		from	hotel_status right outer join
			(
			select	hotel.hotel_cd,
				hotel.hotel_nm,
				hotel.hotel_old_nm,
				hotel.accept_status,
				hotel.pref_id,
				q2.supervisor_cd,
				q2.supervisor_nm
			from	hotel right outer join
				(
				select	hotel_cd,
					q1.supervisor_cd,
					q1.supervisor_nm
				from	hotel_supervisor_hotel right outer join
					(
						select	supervisor_cd,
							supervisor_nm
						from	hotel_supervisor
						where	supervisor_nm like :keyword
					)q1
					on	hotel_supervisor_hotel.supervisor_cd = q1.supervisor_cd
				) q2
				on	hotel.hotel_cd = q2.hotel_cd
				where 1=1
					{$s_pref_id}
			) q3
			on	hotel_status.hotel_cd = q3.hotel_cd
		where 1=1
			{$s_hotel_status}
		) q4
		on	hotel_control.hotel_cd = q4.hotel_cd
			{$s_stock_type}
	) q5
where	mast_pref.pref_id = q5.pref_id
order by q5.hotel_cd
SQL;
		// 施設統括名称で検索した施設の情報を取得
		$a_hotel_supervisor = DB::select($s_sql, $a_conditions);

		// 検索結果を結合
		$a_result = array_merge($a_hotel, $a_hotel_supervisor);

		return array(
			'values'    => $a_result
		);

	}

}
