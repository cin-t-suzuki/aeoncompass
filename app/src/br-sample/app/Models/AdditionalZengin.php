<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;
use stdClass;
use Exception;

class AdditionalZengin extends CommonDBModel
{
    use Traits;

    protected $table = "additional_zengin";
    // カラム
    const COL_ZENGIN_YM  = "zengin_ym";
    const COL_BRANCH_ID   = "branch_id";
    const COL_HOTEL_CD   = "hotel_cd";
    const COL_HOTEL_NM = "hotel_nm";
    const COL_CUSTOMER_ID = "customer_id";
    const COL_CUSTOMER_NM    = "customer_nm";
    const COL_BILLPAY_YMD    = "billpay_ymd";
    const COL_FACTORING_BANK_CD  = "factoring_bank_cd";
    const COL_FACTORING_BANK_BRANCH_CD  = "factoring_bank_branch_cd";
    const COL_FACTORING_BANK_ACCOUNT_TYPE  = "factoring_bank_account_type";
    const COL_FACTORING_BANK_ACCOUNT_NO   = "factoring_bank_account_no";
    const COL_FACTORING_BANK_ACCOUNT_KN   = "factoring_bank_account_kn";
    const COL_FACTORING_CD = "factoring_cd";
    const COL_REASON = "reason";
    const COL_REASON_INTERNAL    = "reason_internal";
    const COL_ADDITIONAL_CHARGE    = "additional_charge";
    const COL_STAFF_ID  = "staff_id";
    const COL_NOTACTIVE_FLG  = "notactive_flg";
    const COL_ENTRY_TS  = "entry_ts"; //bladeで使うからentry_tsも取得する(共通カラムだがいいか？)


    public function __construct() //publicでいいのか？
    {
        // カラム情報の設定
        $colZenginYm = new ValidationColumn();
        $colZenginYm->setColumnName(self::COL_ZENGIN_YM, '処理年月コード');
        $colBranchId = new ValidationColumn();
        $colBranchId->setColumnName(self::COL_BRANCH_ID, '連番ID');
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName(self::COL_HOTEL_CD, '施設コード');
        $colHotelNm = new ValidationColumn();
        $colHotelNm->setColumnName(self::COL_HOTEL_NM, '施設名称');
        $colCustomerId = new ValidationColumn();
        $colCustomerId->setColumnName(self::COL_CUSTOMER_ID, '精算先ID');
        $colCustomerNm = new ValidationColumn();
        $colCustomerNm->setColumnName(self::COL_CUSTOMER_NM, '精算先名称');
        $colBillpayYmd = new ValidationColumn();
        $colBillpayYmd->setColumnName(self::COL_BILLPAY_YMD, '請求支払処理年月日');
        $colFactoringBankcd = new ValidationColumn();
        $colFactoringBankcd->setColumnName(self::COL_FACTORING_BANK_CD, '引落銀行コード');
        $colFactoringBankBranchcd = new ValidationColumn();
        $colFactoringBankBranchcd->setColumnName(self::COL_FACTORING_BANK_BRANCH_CD, '引落支店コード');
        $colFactoringBankAccountType = new ValidationColumn();
        $colFactoringBankAccountType->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_TYPE, '引落口座種別');
        $colFactoringBankAccountNo = new ValidationColumn();
        $colFactoringBankAccountNo->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_NO, '引落口座番号');
        $colFactoringBankAccountKn = new ValidationColumn();
        $colFactoringBankAccountKn->setColumnName(self::COL_FACTORING_BANK_ACCOUNT_KN, '引落口座名義（カナ）');
        $colFactoringcd = new ValidationColumn();
        $colFactoringcd->setColumnName(self::COL_FACTORING_CD, '引落顧客コード');
        $colReason = new ValidationColumn();
        $colReason->setColumnName(self::COL_REASON, '理由');
        $colReasonInternal = new ValidationColumn();
        $colReasonInternal->setColumnName(self::COL_REASON_INTERNAL, '備考（内部のみ）');
        $colAdditionalCharge = new ValidationColumn();
        $colAdditionalCharge->setColumnName(self::COL_ADDITIONAL_CHARGE, '追加金額');
        $colStaffId = new ValidationColumn();
        $colStaffId->setColumnName(self::COL_STAFF_ID, 'スタッフID');
        $colNotactiveFlg = new ValidationColumn();
        $colNotactiveFlg->setColumnName(self::COL_NOTACTIVE_FLG, '削除フラグ');
        $colEntryTs = new ValidationColumn();
        $colEntryTs->setColumnName(self::COL_ENTRY_TS, '登録日'); //bladeで使うからentry_tsも取得する(共通カラムだがいいか？)

        // バリデーションルール
        // 処理年月コード
        $colZenginYm->require();     // 必須入力チェック
        $colZenginYm->notHalfKana(); // 半角カナチェック
        $colZenginYm->length(0, 6); // 長さチェック

        // 連番ID
        $colBranchId->notHalfKana(); // 半角カナチェック
        $colBranchId->length(0, 10); // 長さチェック
        $colBranchId->intOnly(); // 数字：数値チェック

        // 施設コード
        $colHotelCd->notHalfKana(); // 半角カナチェック
        $colHotelCd->length(0, 10); // 長さチェック

        // 施設名称
        $colHotelNm->notHalfKana(); // 半角カナチェック
        $colHotelNm->length(0, 50); // 長さチェック

        // 精算先ID
        $colCustomerId->length(0, 10); // 長さチェック
        $colCustomerId->intOnly(); // 数字：数値チェック

        // 精算先名称
        $colCustomerNm->notHalfKana(); // 半角カナチェック
        $colCustomerNm->length(0, 50); // 長さチェック

        // 請求支払処理年月日
        $colBillpayYmd->correctDate();  // 日付チェック

        // 引落銀行コード
        $colFactoringBankcd->notHalfKana(); // 半角カナチェック
        $colFactoringBankcd->length(0, 4); // 長さチェック

        // 引落支店コード
        $colFactoringBankBranchcd->notHalfKana(); // 半角カナチェック
        $colFactoringBankBranchcd->length(0, 3); // 長さチェック

        // 引落口座種別
        $colFactoringBankAccountType->length(0, 1); // 長さチェック
        $colFactoringBankAccountType->intOnly(); // 数字：数値チェック

        // 引落口座番号
        $colFactoringBankAccountNo->notHalfKana(); // 半角カナチェック
        $colFactoringBankAccountNo->length(0, 7); // 長さチェック

        // 引落口座名義（カナ）
        $colFactoringBankAccountKn->notHalfKana(); // 半角カナチェック
        $colFactoringBankAccountKn->length(0, 30); // 長さチェック
        //ひらがなのみチェック　TODO 別ブランチで作成済。マージ後に適用する

        // 引落顧客コード
        $colFactoringcd->notHalfKana(); // 半角カナチェック
        $colFactoringcd->length(0, 12); // 長さチェック

        // 理由
        $colReason->notHalfKana(); // 半角カナチェック
        $colReason->length(0, 333); // 長さチェック

        // 備考（内部のみ）
        $colReasonInternal->notHalfKana(); // 半角カナチェック
        $colReasonInternal->length(0, 333); // 長さチェック

        // 追加金額
        $colAdditionalCharge->length(0, 10); // 長さチェック
        $colAdditionalCharge->intOnly(); // 数字：数値チェック

        // スタッフID
        $colStaffId->length(0, 8); // 長さチェック
        $colStaffId->intOnly(); // 数字：数値チェック

        // 削除フラグ
        $colNotactiveFlg->notHalfKana(); // 半角カナチェック
        $colNotactiveFlg->length(0, 1); // 長さチェック


        parent::setColumnDataArray([
            $colZenginYm, $colBranchId, $colHotelCd, $colHotelNm, $colCustomerId,
            $colCustomerNm  , $colBillpayYmd , $colFactoringBankcd, $colFactoringBankBranchcd ,
            $colFactoringBankAccountType  , $colFactoringBankAccountNo , $colFactoringBankAccountKn, $colFactoringcd ,
            $colReason  , $colReasonInternal , $colAdditionalCharge, $colStaffId ,$colNotactiveFlg,$colEntryTs
        ]);
    }

    //============================================
    //引落入金予定日の取得 201804
    //============================================
    public function getPaymentSchedule()
    {
        try {
            $s_sql =
            <<< SQL
		select date_ymd,
			   ym
		from money_schedule
		where money_schedule_id = 5
		and date_ymd >= '2017/08/01'
		order by date_ymd desc
SQL;

            return DB::select($s_sql, []);
        } catch (Exception $e) {
            throw $e;
        }
    }

    //============================================
    // 引落追加額データの取得
    //============================================
    public function getBranchId($zengin_ym)
    {
        try {
            $s_sql =
            <<< SQL
		select
			max(branch_id) as branch_id
		from	additional_zengin
		where	zengin_ym = :zengin_ym
SQL;
            $result = DB::select($s_sql, [$zengin_ym]);

            if ($this->is_empty($result)) {
                return 1;
            } else {
                return $result[0]->branch_id + 1;
            }

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //============================================
    // 引落追加額データの取得
    //============================================
    public function getAdditionalZengin($aa_conditions)
    {
        try {
            $s_conditions = '';
            $a_conditions = [];

            // 検索条件：請求対象年月を設定
            if (($aa_conditions['unuse_check']) == 0) {
                $s_conditions .= ' and	zengin_ym = :zengin_ym ';
                $zengin_ym = $aa_conditions['year'] . $aa_conditions['month'];
                $a_conditions = ['zengin_ym' => (int)$zengin_ym]; //文字列だと拾えない
            }

            //検索条件：引落入金予定日を設定
            if (($aa_conditions['unuse_check']) == 1) {
                $s_conditions .= ' and	zengin_ym = :ym ';
                $ym = $aa_conditions['ym'];
                $a_conditions += ['ym' => $ym];
            }

            // 検索条件：キーワードを設定
            if (!$this->is_empty($aa_conditions['keywords'])) {
                if (ctype_digit($aa_conditions['keywords'])) {
                    $s_conditions .= ' and	(hotel_cd  = :hotel_cd or customer_id = :customer_id) ';
                    $a_conditions += [
                        'hotel_cd' => $aa_conditions['keywords'],
                        'customer_id' => $aa_conditions['keywords']
                    ];
                } else {
                    $s_conditions .= ' and	(hotel_nm  like :hotel_nm or customer_nm like :customer_nm) ';
                    $a_conditions += [
                        'hotel_nm' => '%' . $aa_conditions['keywords'] . '%',
                        'customer_nm' => '%' . $aa_conditions['keywords'] . '%'
                    ];
                }
            }

            $s_sql =
            <<< SQL
		select
			a.zengin_ym,
			a.branch_id,
			a.billpay_ymd,
			a.hotel_cd,
			a.hotel_nm,
			a.customer_id,
			a.customer_nm,
			a.factoring_bank_cd,
			a.factoring_bank_branch_cd,
			a.factoring_bank_account_type,
			a.factoring_bank_account_no,
			a.factoring_bank_account_kn,
			a.factoring_cd,
			a.reason,
			a.reason_internal,
			a.additional_charge,
			a.staff_id,
			s.staff_nm,
			a.entry_ts,
			m.date_ymd
		from	additional_zengin a
		left join staff s on (s.staff_id = a.staff_id) 
		left join money_schedule m on (a.zengin_ym = date_format(m.ym,'%Y%m')) -- to_cahr→dateformatでいいか？
		 where	a.notactive_flg = 0
		 and m.money_schedule_id = 5
				{$s_conditions}
		order by a.hotel_cd,a.billpay_ymd desc

SQL;

            return DB::select($s_sql, $a_conditions);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /** 主キーで取得
     */
    public function selectByKey($zengin_ym, $branch_id)
    {
        $data = $this->where([self::COL_ZENGIN_YM => $zengin_ym, self::COL_BRANCH_ID => $branch_id])->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_ZENGIN_YM  => $data[0]->zengin_ym,
                self::COL_BRANCH_ID   => $data[0]->branch_id,
                self::COL_HOTEL_CD   => $data[0]->hotel_cd,
                self::COL_HOTEL_NM => $data[0]->hotel_nm,
                self::COL_CUSTOMER_ID => $data[0]->customer_id,
                self::COL_CUSTOMER_NM    => $data[0]->customer_nm,
                self::COL_BILLPAY_YMD    => $data[0]->billpay_ymd,
                self::COL_FACTORING_BANK_CD  => $data[0]->factoring_bank_cd,
                self::COL_FACTORING_BANK_BRANCH_CD  => $data[0]->factoring_bank_branch_cd,
                self::COL_FACTORING_BANK_ACCOUNT_TYPE  => $data[0]->factoring_bank_account_type,
                self::COL_FACTORING_BANK_ACCOUNT_NO   => $data[0]->factoring_bank_account_no,
                self::COL_FACTORING_BANK_ACCOUNT_KN   => $data[0]->factoring_bank_account_kn,
                self::COL_FACTORING_CD => $data[0]->factoring_cd,
                self::COL_REASON => $data[0]->reason,
                self::COL_REASON_INTERNAL    => $data[0]->reason_internal,
                self::COL_ADDITIONAL_CHARGE    => $data[0]->additional_charge,
                self::COL_STAFF_ID  => $data[0]->staff_id,
                self::COL_NOTACTIVE_FLG  => $data[0]->notactive_flg,
                self::COL_ENTRY_TS  => $data[0]->entry_ts
            ];
        }
        return null;
    }

    /** キーで取得
     */
    public function selectBy3Key($zengin_ym, $hotelCd, $notactive_flg)
    {
        $data = $this->where([
            self::COL_ZENGIN_YM => $zengin_ym,
            self::COL_HOTEL_CD => $hotelCd,
            self::COL_NOTACTIVE_FLG => $notactive_flg
            ])->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_ZENGIN_YM  => $data[0]->zengin_ym,
                self::COL_BRANCH_ID   => $data[0]->branch_id,
                self::COL_HOTEL_CD   => $data[0]->hotel_cd,
                self::COL_HOTEL_NM => $data[0]->hotel_nm,
                self::COL_CUSTOMER_ID => $data[0]->customer_id,
                self::COL_CUSTOMER_NM    => $data[0]->customer_nm,
                self::COL_BILLPAY_YMD    => $data[0]->billpay_ymd,
                self::COL_FACTORING_BANK_CD  => $data[0]->factoring_bank_cd,
                self::COL_FACTORING_BANK_BRANCH_CD  => $data[0]->factoring_bank_branch_cd,
                self::COL_FACTORING_BANK_ACCOUNT_TYPE  => $data[0]->factoring_bank_account_type,
                self::COL_FACTORING_BANK_ACCOUNT_NO   => $data[0]->factoring_bank_account_no,
                self::COL_FACTORING_BANK_ACCOUNT_KN   => $data[0]->factoring_bank_account_kn,
                self::COL_FACTORING_CD => $data[0]->factoring_cd,
                self::COL_REASON => $data[0]->reason,
                self::COL_REASON_INTERNAL    => $data[0]->reason_internal,
                self::COL_ADDITIONAL_CHARGE    => $data[0]->additional_charge,
                self::COL_STAFF_ID  => $data[0]->staff_id,
                self::COL_NOTACTIVE_FLG  => $data[0]->notactive_flg
            ];
        }
        return null;
    }

    /**  新規登録
     *
     * @param [type] $con
     * @param [type] $data
     * @return
    */
    public function insert($con, $data)
    {
        $result = $con->table($this->table)->insert($data);
        return  $result;
    }

    /**  キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)
            ->where([
                self::COL_ZENGIN_YM => $data[self::COL_ZENGIN_YM],
                self::COL_BRANCH_ID => $data[self::COL_BRANCH_ID]
                    ])->update($data);
        return $result;
    }
}
