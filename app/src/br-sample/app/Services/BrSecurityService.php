<?php

namespace App\Services;

// use App\Models\DenyList;
// use App\Models\Hotel;
// use App\Models\HotelAccount;
// use App\Models\HotelControl;
// use App\Models\HotelInsuranceWeather;
// use App\Models\HotelNotify;
// use App\Models\HotelPerson;
// use App\Models\HotelStatus;
// use App\Models\HotelSystemVersion;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;


class BrSecurityService
{

    	// セキュリティログ一覧取得
		//
		// aa_conditions
		//   account_class アカウントクラス
		//   account_key   アカウント認証キー
		//   request_dtm   リクエスト日時
		//     after  =      日付 YYYY-MM-DD HH24:MI:SS
		//     before =      日付 YYYY-MM-DD HH24:MI:SS
		//


		public function get_log_securities($aa_conditions = array()){
			try {

                // dd($aa_conditions);
		// 		// $_oracle   = _Oracle::getInstance();

		// 		$a_conditions = array();

		// 		// アカウントクラスを設定
		// 		if (!is_empty($aa_conditions['account_class'])){
		// 			$a_conditions['account_class'] = $aa_conditions['account_class'];
		// 			$s_account_class = '	and	account_class = :account_class';
		// 		}

		// 		// アカウント認証キーを設定
		// 		if (!is_empty($aa_conditions['account_key'])){
		// 			$a_conditions['account_key'] = $aa_conditions['account_key'];
		// 			$s_account_key = '	and	account_key = :account_key';
		// 		}

		// 		// リクエスト日時を設定
		// 		if (!is_empty($aa_conditions['request_dtm']['after'])){
		// 			$s_after_request_dtm = "	and	request_dtm >= to_date(:after_request_dtm, 'YYYY-MM-DD HH24:MI:SS')";
		// 			$a_conditions['after_request_dtm'] = $aa_conditions['request_dtm']['after'];
		// 		}

		// 		if (!is_empty($aa_conditions['request_dtm']['before'])){
		// 			$s_before_request_dtm = "	and	request_dtm <= to_date(:before_request_dtm, 'YYYY-MM-DD HH24:MI:SS')";
		// 			$a_conditions['before_request_dtm'] = $aa_conditions['request_dtm']['before'];
		// 		}

		// 		$o_after = new Br_Models_Date($aa_conditions['request_dtm']['after']);
		// 		$o_before = new Br_Models_Date($aa_conditions['request_dtm']['before']);

				while ($o_after->to_format('Ym') <= $o_before->to_format('Ym')){

					// 最低料金を取得
					$s_sql =<<< SQL
						select	security_cd,
								session_id,
								to_char(request_dtm, 'YYYY-MM-DD HH24:MI:SS') as request_dtm,
								account_class,
								account_key,
								ip_address,
								uri
						from	log_security_{$o_after->to_format('m')}
						where	null is null
							{$s_account_class}
							{$s_account_key}
							{$s_after_request_dtm}
							{$s_before_request_dtm}
						order by request_dtm
                    SQL;


                                        // $resultHotelInfo = DB::select($sql, ['hotel_cd' => $hotelCd]);
                                        // if (count($resultHotelInfo) > 0) {
                                        //     return $resultHotelInfo[0];
                                        // } else {
                                        //     // データがヒットしないときは、必要なプロパティを設定した空の stdClass を返す
                                        //     // MEMO: 設定しておかないと、 undefined array key で処理が止まる
                                        //     return (object)[
                                        //         'hotel_cd'  => null,
                                        //         'hotel_nm'  => null,
                                        //         'postal_cd' => null,
                                        //         'pref_nm'   => null,
                                        //         'address'   => null,
                                        //         'tel'       => null,
                                        //         'fax'       => null,
                                        //     ];
                                        // }




					$a_row = $_oracle->find_by_sql($s_sql, $a_conditions);

					$staff            = Staff::getInstance();
					$hotel            = Hotel::getInstance();
					$partner          = Partner::getInstance();
					$hotel_supervisor = Hotel_Supervisor::getInstance();
					$member_free      = Member_Free::getInstance();
					$models_member    = new models_Member();

					for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++){

						switch ($a_row[$n_cnt]['account_class']) {
						case 'staff':
							$a_row[$n_cnt]['staff'] = $staff->find(array('staff_id' => $a_row[$n_cnt]['account_key']));
							break;
						case 'hotel':
							$a_row[$n_cnt]['hotel'] = $hotel->find(array('hotel_cd' => $a_row[$n_cnt]['account_key']));
							break;
						case 'partner':
							$a_row[$n_cnt]['partner'] = $partner->find(array('partner_cd' => $a_row[$n_cnt]['account_key']));
							break;
						case 'supervisor':
							$a_row[$n_cnt]['hotel_supervisor'] = $hotel_supervisor->find(array('supervisor_cd' => $a_row[$n_cnt]['account_key']));
							break;
						case 'member':
							$a_row[$n_cnt]['member'] = $models_member->get_member($a_row[$n_cnt]['account_key']);
							break;
						case 'member_free':
							$a_row[$n_cnt]['member_free'] = $member_free->find(array('member_cd' => $a_row[$n_cnt]['account_key']));
							break;
						}

						$as_result[] = $a_row[$n_cnt];

					}

					$o_after->add('m', 1);
				}

				// return array(
				// 			'values'     => $as_result,
				// 			'reference' => $this->set_reference('セキュリティログ一覧取得', __METHOD__)
				// 		);

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
    }