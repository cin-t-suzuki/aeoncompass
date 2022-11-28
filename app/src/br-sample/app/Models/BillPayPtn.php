<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use App\Util\Models_Cipher;
use Exception;

/**
 * パートナー精算マスタ
 */
class BillPayPtn extends CommonDBModel
{
	use Traits;

	protected $table = "mast_city";
	// カラム
	public string $COL_CITY_ID = "city_id";
	public string $COL_PREF_ID = "pref_id";
	public string $COL_CITY_NM = "city_nm";
	public string $COL_PREF_CITY_NM = "pref_city_nm";
	public string $COL_ORDER_NO = "order_no";
	public string $COL_CITY_CD = "city_cd";
	public string $COL_DELETE_YMD = "delete_ymd";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colCityId = new ValidationColumn();
		$colCityId->setColumnName($this->COL_CITY_ID, "市ID")->require()->length(0,20)->intOnly();

		parent::setColumnDataArray([$colCityId,]);
	}

	//======================================================================
	// パートナー精算対象月の精算データを検索 ※private->publicへ変更
	//======================================================================
	// aa_conditions
	//   as_billpay_ym   精算年月(YYYY-MM)
	//   as_customer_id  パートナー精算先ID
	public function _get_billpayptn($aa_conditions)
	{
		try {

			$s_customer_id = null; //初期化 追記
			$aa_conditions['customer_id'] = 5; //初期化 追記

			// バインドパラメータ設定
			$a_conditions['billpay_ym'] = $aa_conditions['billpay_ym'];
			if (!$this->is_empty($aa_conditions['customer_id'])) {
				$s_customer_id = 'and billpay_ptn_book.customer_id = :customer_id';
				$a_conditions['customer_id'] = $aa_conditions['customer_id'];
			}

			//下記要書き換え
			// to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.book_create_dtm , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.book_create_dtm , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as book_create_dtm,
			// to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.billpay_ym , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.billpay_ym , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as billpay_ym,
			// to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.bill_ymd , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(billpay_ptn_book.bill_ymd , 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as bill_ymd,

			$s_sql =
<<< SQL
				select	billpay_ptn_book.billpay_ptn_cd,
						billpay_ptn_cstmr.customer_id,
						billpay_ptn_cstmr.customer_nm,
						billpay_ptn_cstmr.person_post,
						billpay_ptn_cstmr.person_nm,
						billpay_ptn_cstmr.document_type,
						billpay_ptn_book.billpay_type,
						case
							when billpay_ptn_book.billpay_type = 0 then '請求'
							when billpay_ptn_book.billpay_type = 1 then '支払'
							else '繰越'
						end billpay_type_nm,
						billpay_ptn_book.billpay_charge_total,
						billpay_ptn_book.book_path,
						billpay_ptn_book.book_create_dtm as book_create_dtm,
						billpay_ptn_book.billpay_ym  as billpay_ym,
						billpay_ptn_book.bill_ymd as bill_ymd,
						billpay_ptn_cstmr.billpay_day
				from	billpay_ptn_book,
						billpay_ptn_cstmr
				where	date_format(billpay_ptn_book.billpay_ym, '%Y-%m') = :billpay_ym 
					and	billpay_ptn_book.billpay_ym = billpay_ptn_cstmr.billpay_ym
					and	billpay_ptn_book.customer_id = billpay_ptn_cstmr.customer_id
					{$s_customer_id}
					order by billpay_ptn_cd
SQL;

// 上記where1つ目の文書き換えこれで大丈夫か

			$a_book = DB::select($s_sql, $a_conditions);
			$a_book = json_decode(json_encode($a_book), true); //json~追記しないとviewでエラー

			$o_cipher = new Models_Cipher(config('settings.cipher_key'));
			for ($n_cnt = 0; $n_cnt < count($a_book); $n_cnt++){

				// 原稿ファイルパスの暗号化
				if (!$this->is_empty($a_book[$n_cnt]['book_path'])){
					$a_book[$n_cnt]['book_path_encrypt'] = $o_cipher->encrypt($a_book[$n_cnt]['book_path']);
				}else{
					$a_book[$n_cnt]['book_path_encrypt'] = null;
				}

				// 付属情報設定、予約情報取得
				if (!$this->is_empty($aa_conditions['customer_id'])) {

					// ＮＴＡの場合
					if ($aa_conditions['customer_id'] == 1) {
						$a_book[$n_cnt]['extension_state'] = 0;
						$a_book[$n_cnt]['connect_type'] = 'pool';

					// 出張なびの場合
					}elseif ($aa_conditions['customer_id'] == 2) {
						$a_book[$n_cnt]['extension_state'] = 0;
						$a_book[$n_cnt]['connect_type'] = 'clone';
					} else {

						$s_sql =
<<< SQL
						select	max(partner_control.extension_state) as extension_state,
								max(partner_control.connect_type) as connect_type
						from	billpay_ptn_cstmrsite,
								billpay_ptn_site LEFT OUTER JOIN partner_control ON billpay_ptn_site.partner_cd = partner_control.partner_cd
						where   date_format(billpay_ptn_cstmrsite.billpay_ym, '%Y-%m') = :billpay_ym 
							and	billpay_ptn_cstmrsite.customer_id   = :customer_id
							and	billpay_ptn_cstmrsite.billpay_ym    = billpay_ptn_site.billpay_ym
							and	billpay_ptn_cstmrsite.site_cd       = billpay_ptn_site.site_cd
SQL;

// 上記where1つ目、(+)→LEFT OUTER JOIN～ONの書き換えこれで大丈夫か
//maxだとnullでとってきている…？

						$a_row = DB::select($s_sql, $a_conditions);
						$a_row = json_decode(json_encode($a_row), true); //json~追記しないとviewでエラー

						$a_book[$n_cnt]['extension_state'] = $a_row[0]['extension_state']; //どこで使っているかわからない
						$a_book[$n_cnt]['connect_type']    = $a_row[0]['connect_type'];
					}
				}

			}

			if ($this->is_empty($a_book)) {
				$error[] = 'NotFound';
			}

			return $a_book??array();

		} catch (Exception $e) {
			throw $e;
		}
	}



	

}
