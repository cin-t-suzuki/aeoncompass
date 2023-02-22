<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Common\DateUtil;

class Reserve extends CommonDBModel
{
    use Traits;

    // カラム

    public function __construct()
    {
        // カラム情報の設定
    }

    // 予約一覧
    private $a_reserves = [];

    /**
     * ある時点でのシステム利用料を取得
     * @param string $as_hotel_cd 施設コード
     * @param array{
     *  after?: string 開始対象日
     *  before?: string 終了対象日
     * } $aa_date_ymd 宿泊対象
     * @return int
     */
    public function getBillChargeTotal($as_hotel_cd, $aa_date_ymd)
    {
        try {
            // 施設コードを設定
            $a_conditions['hotel_cd'] = $as_hotel_cd;

            // 宿泊日を設定
            $a_conditions['after_date_ymd'] = $aa_date_ymd['after'];
            $a_conditions['before_date_ymd'] = $aa_date_ymd['before'];


            $s_sql =
            <<< SQL
					select	q2.reserve_cd,
							date_format(q2.date_ymd, '%Y-%m-%d') as date_ymd, -- to_char→date_formatでいいか
							q2.reserve_status,
							date_format(q2.reserve_dtm, '%Y-%m-%d') as reserve_ymd, -- to_char→date_formatでいいか
							q2.payment_way,
							reserve_charge.sales_charge - reserve_charge.tax_charge - reserve_charge.stay_tax_charge as no_tax_sales_charge,
							reserve_charge.cancel_charge,
							reserve_charge.system_rate
					from	reserve_charge,
						(
							select	q1.reserve_cd,
									q1.date_ymd,
									q1.reserve_status,
									q1.reserve_dtm,
									reserve_plan.payment_way
							from	reserve_plan,
								(
									select	reserve_cd,
											date_ymd,
											reserve_status,
											reserve_dtm
									from	reserve
									where	hotel_cd = :hotel_cd
										and	date_ymd between date_format(:after_date_ymd, '%Y-%m-%d') and date_format(:before_date_ymd, '%Y-%m-%d') -- 書き替えあっているか？
								) q1
							where	reserve_plan.reserve_cd = q1.reserve_cd
						) q2
					where	reserve_charge.reserve_cd = q2.reserve_cd
						and	reserve_charge.date_ymd = q2.date_ymd
SQL;

            // データの取得
            $a_row = DB::select($s_sql, $a_conditions);

            // $hotelModel = new Hotel();
            // $hotelModel->set_hotel_cd($as_hotel_cd); //いる？

            $chargeModel = new Charge();

            //初期化
            $n_bill_charge = null; //nullでいいか

            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                // 2009-07-01 以降に予約手続きが行われた予約は予約日時を元にシステム利用料を算出
                $o_date = new DateUtil($a_row[$n_cnt]->reserve_ymd); //Br_Models_Date→DateUtilでいいか
                if ($o_date->to_format('Ymd') < '20090701') {
                    $o_date = new DateUtil($a_row[$n_cnt]->date_ymd); //Br_Models_Date→DateUtilでいいか
                }

                if ($a_row[$n_cnt]->reserve_status == 0) {
                    $n_bill_charge = $n_bill_charge + floor($a_row[$n_cnt]->no_tax_sales_charge * ($a_row[$n_cnt]->system_rate / 100));
                } else {
                    $a_tax_rate  = $chargeModel->getTaxRate($a_row[$n_cnt]->date_ymd);
                    $n_notax_cancel_charge = ceil($a_row[$n_cnt]->cancel_charge / ($a_tax_rate['values'] / 100 + 1));
                    $n_bill_charge = $n_bill_charge + floor($n_notax_cancel_charge * ($a_row[$n_cnt]->system_rate  / 100));
                }
            }

            return $n_bill_charge;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 予約情報と宿泊料金の情報
     *
     * @param array{
     *  reserve_cd?: string,        予約コード
     *  partner_ref?: string,       予約参照コード
     *  partner_cd?: string,        提携先コード
     *  common_cd?: string,         予約コードまたは予約参照コード
     *  member_cd?: string,         会員コード
     *  member_cds?: array,         会員コード array('xxx', 'xxx')
     *  member_nm?: string,         会員名称（漢字・かな 両方可）
     *  hotel_cd?: string,          施設コード
     *  hotel_cds?: array,          施設コード array('xxx', 'xxx')
     *  supervisor_cd?: string,     施設統括コード
     *  hotel_nm?: string,          施設名称
     *  guest_nm?: string,          宿泊代表者氏名
     *  email?: string,             予約者メールアドレス
     *  reserve_system?: string,    予約システム
     *  affiliate_cd?: string,      アフィリエイトコード
     *  affiliate_cd_sub?: string,  アフィリエイトコード枝番
     *  auth_type?: string,         認証タイプ
     *  stock_type?: array,         仕入タイプ array(0 , 1)
     *  payment_way?: array,        決済方法   array(0 , 1, 2)
     *  insurance_weather?: string, お天気保険
     *  pref_id?: string,           都道府県コード
     *  capacity?: array{            利用人数
     *    people,          人数
     *    type,            タイプ large:人数以上の場合  same:人数が同じ場合
     *  },
     *  reserve_status?: int,    予約ステータス 0:含まない 1:キャンセルのみ
     *  date_ymd?: array{
     *    after,           日付 > 日付以降宿泊の予約 YYYY-MM-DD
     *    before          日付 > 日付以前宿泊の予約 YYYY-MM-DD
     *  },
     *  reserve_dtm?: array{       予約日の予約
     *    after,           日付 > 日付以降の予約 YYYY-MM-DD HH24:MI:SS
     *    before,          日付 > 日付以前の予約 YYYY-MM-DD HH24:MI:SS
     *  },
     *  cancel_dtm?: array{        取り消し日の予約
     *    after,           日付 > 日付以降取り消しの予約 YYYY-MM-DD HH24:MI:SS
     *    before,          日付 > 日付以前取り消しの予約 YYYY-MM-DD HH24:MI:SS
     *    reserve_type,       0:手配旅行 1:募集型企画旅行（1がJRコレクションに該当）
     *  }
     * } $aa_conditions
     *
     * @return bool
     */
    public function reserves($aa_conditions)
    {

        try {
            $s_pref = '';
            $s_member_cd = '';
            $s_member_cds = '';
            $s_reserve_status = '';
            $s_reserve_cd = '';
            $s_partner_ref = '';
            $s_partner_cd = '';
            $s_common_cd = '';
            $s_date_ymd = '';
            $s_reserve_system = '';
            $s_affiliate_cd = '';
            $s_affiliate_cd_sub = '';
            $s_auth_type = '';
            $s_after_date_ymd = '';
            $s_before_date_ymd = '';
            $s_reserve_dtm = '';
            $s_after_reserve_dtm = '';
            $s_before_reserve_dtm = '';
            $s_cancel_dtm = '';
            $s_after_cancel_dtm = '';
            $s_before_cancel_dtm = '';
            $s_hotel_cd = '';
            $s_hotel_cds = '';
            $s_reserve_type = '';
            $s_supervisor_cd = '';
            $s_hotel_nm = '';
            $s_stock_types = '';
            $s_capacity = '';
            $s_guest_nm = '';
            $s_member_nm = '';
            $s_email = '';
            $s_insurance_weather = '';
            $s_payment_ways = '';
            $s_use_point = '';

            $a_conditions = []; // 初期化

            // 予約コードを設定
            if (!$this->is_empty($aa_conditions['reserve_cd'] ?? null)) { //null追記でいいか
                $s_reserve_cd = '	and	reserve.reserve_cd = :reserve_cd';
                $a_conditions['reserve_cd'] = trim($aa_conditions['reserve_cd']);
            }

            // 予約参照コードを設定
            if (!$this->is_empty($aa_conditions['partner_ref'] ?? null)) { //null追記でいいか
                $s_partner_ref = '	and	reserve.partner_ref = :partner_ref';
                $a_conditions['partner_ref'] = trim($aa_conditions['partner_ref']);
            }

            // 予約コードまたは予約参照コードを設定
            if (!$this->is_empty($aa_conditions['common_cd'] ?? null)) { //null追記でいいか
                $s_common_cd = '	and	(reserve.reserve_cd = :common_cd or reserve.partner_ref = :common_cd or reserve.order_cd = :common_cd)';
                $a_conditions['common_cd'] = trim($aa_conditions['common_cd']);
            }

            // 提携先コードを設定
            if (!$this->is_empty($aa_conditions['partner_cd'] ?? null)) { //null追記でいいか
                $s_partner_cd = '	and	reserve.partner_cd = :partner_cd';
                $a_conditions['partner_cd'] = $aa_conditions['partner_cd'];
            }

            // 会員コードを設定
            if (!$this->is_empty($aa_conditions['member_cd'] ?? null)) { //null追記でいいか
                $s_member_cd = '	and	reserve.member_cd = :member_cd';
                $a_conditions['member_cd'] = trim($aa_conditions['member_cd']);
            }

            // 複数会員コードを設定
            if (!($this->is_empty($aa_conditions['member_cds'] ?? null))) { //null追記でいいか
                for ($n_cnt = 0; $n_cnt < count($aa_conditions['member_cds']); $n_cnt++) {
                    if (!($this->is_empty($aa_conditions['member_cds'][$n_cnt]))) {
                        if ($this->is_empty($s_member_cds)) {
                            $s_member_cds = 'and	reserve.member_cd in (';
                        }

                        $s_member_cds .= ':member_cd' . $n_cnt . ', ';

                        $a_conditions['member_cd' . $n_cnt] = $aa_conditions['member_cds'][$n_cnt];
                    }
                }
                if (!($this->is_empty($s_member_cds))) {
                    $s_member_cds = substr($s_member_cds, 0, -2) . ')';
                }
            }

            // 予約者氏名を設定
            if (!($this->is_empty($aa_conditions['member_nm'] ?? null))) { //null追記でいいか
                // 全角 半角スペースを除いた文字列で部分一致検索をする
                $a_conditions['member_nm'] = mb_ereg_replace(' ', '', mb_ereg_replace('　', '', $aa_conditions['member_nm']));
                $s_member_nm =
                <<< SQL
					and	(
							exists (
									select	member_cd
									from	member_detail
									where	member_detail.member_cd = q3.member_cd
										and (
												member_detail.family_nm || member_detail.given_nm like '%' || :member_nm || '%'
											or  member_detail.family_kn || member_detail.given_kn like '%' || :member_nm || '%'
											)
									)
						or (
									auth_type = 'free'
								and	reserve_guest.guest_nm like '%' || :member_nm || '%'
							)
						)
SQL;
            }

            // 施設コードを設定
            if (!$this->is_empty($aa_conditions['hotel_cd'] ?? null)) { //null追記でいいか
                $s_hotel_cd = '	and	reserve.hotel_cd = :hotel_cd';
                $a_conditions['hotel_cd'] = trim($aa_conditions['hotel_cd']);
            }

            // 複数施設コードを設定
            if (1 < count($aa_conditions['hotel_cds'] ?? [])) {  //[]追記でいいか
                for ($n_cnt = 0; $n_cnt < count($aa_conditions['hotel_cds']); $n_cnt++) {
                    if (!($this->is_empty($aa_conditions['hotel_cds'][$n_cnt]))) {
                        if ($this->is_empty($s_hotel_cds)) {
                            $s_hotel_cds = 'and	reserve.hotel_cd in (';
                        }

                        $s_hotel_cds .= ':hotel_cd' . $n_cnt . ', ';

                        $a_conditions['hotel_cd' . $n_cnt] = $aa_conditions['hotel_cds'][$n_cnt];
                    }
                }
                if (!($this->is_empty($s_hotel_cds))) {
                    $s_hotel_cds = substr($s_hotel_cds, 0, -2) . ')';
                }
            }

            // 施設統括コードを設定
            if (!$this->is_empty($aa_conditions['supervisor_cd'] ?? null)) { //null追記でいいか
                $s_supervisor_cd = '	and	reserve.hotel_cd in (select hotel_cd from hotel_supervisor_hotel where supervisor_cd = :supervisor_cd)';
                $a_conditions['supervisor_cd'] = trim($aa_conditions['supervisor_cd']);
            }

            // 施設名を設定
            if (!$this->is_empty($aa_conditions['hotel_nm'] ?? null)) { //null追記でいいか
                $s_hotel_nm = '	and	reserve_plan.hotel_nm like :hotel_nm';
                $a_conditions['hotel_nm'] =  '%' . $aa_conditions['hotel_nm'] . '%';
            }

            // 宿泊代表者氏名を設定
            if (!$this->is_empty($aa_conditions['guest_nm'] ?? null)) { //null追記でいいか
                $s_guest_nm = '	and	reserve_guest.guest_nm like :guest_nm';
                $a_conditions['guest_nm'] = '%' . $aa_conditions['guest_nm'] . '%';
            }

            // 予約者メールアドレス(予約時の連絡先メールアドレス)を設定
            if (!$this->is_empty($aa_conditions['email'] ?? null)) { //null追記でいいか
                $s_email = '	and	reserve_contact.email like :email';
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $a_conditions['email'] = $cipher->encrypt($aa_conditions['email']);
            }

            // 予約システム
            if (!$this->is_empty($aa_conditions['reserve_system'] ?? null)) { //null追記でいいか
                $s_reserve_system = '	and	reserve.reserve_system = :reserve_system';
                $a_conditions['reserve_system'] = $aa_conditions['reserve_system'];
            }

            // アフィリエイトコード
            if (!$this->is_empty($aa_conditions['affiliate_cd'] ?? null)) { //null追記でいいか
                $s_affiliate_cd = '	and	reserve.affiliate_cd = :affiliate_cd';
                $a_conditions['affiliate_cd'] = $aa_conditions['affiliate_cd'];
            }

            // アフィリエイトコード枝番
            if (!$this->is_empty($aa_conditions['affiliate_cd_sub'] ?? null)) { //null追記でいいか
                $s_affiliate_cd_sub = '	and	reserve.affiliate_cd_sub = :affiliate_cd_sub';
                $a_conditions['affiliate_cd_sub'] = $aa_conditions['affiliate_cd_sub'];
            }

            // 認証タイプ
            if (!$this->is_empty($aa_conditions['auth_type'] ?? null)) { //null追記でいいか
                $s_auth_type = '	and	reserve.auth_type = :auth_type';
                $a_conditions['auth_type'] = $aa_conditions['auth_type'];
            }

            // 仕入タイプを設定
            if (0 < count($aa_conditions['stock_type'] ?? [])) { //[]追記でいいか
                for ($n_cnt = 0; $n_cnt < count($aa_conditions['stock_type']); $n_cnt++) {
                    if (!($this->is_empty($aa_conditions['stock_type'][$n_cnt]))) {
                        if ($this->is_empty($s_stock_types)) {
                            $s_stock_types = 'and	reserve_plan.stock_type in (';
                        }

                        $s_stock_types .= ':stock_type' . $n_cnt . ', ';

                        $a_conditions['stock_type' . $n_cnt] = $aa_conditions['stock_type'][$n_cnt];
                    }
                }

                if (!($this->is_empty($s_stock_types))) {
                    $s_stock_types = substr($s_stock_types, 0, -2) . ')';
                }
            }

            // 決済方法を設定
            if (0 < count($aa_conditions['payment_way'] ?? [])) { //[]追記でいいか
                // 判断用
                $is_locale = false;

                for ($n_cnt = 0; $n_cnt < count($aa_conditions['payment_way']); $n_cnt++) {
                    if (!($this->is_empty($aa_conditions['payment_way'][$n_cnt]))) {
                        if ($this->is_empty($s_payment_ways)) {
                            $s_payment_ways = 'and	reserve_charge.payment_way in (';
                        }

                        $s_payment_ways .= ':payment_way' . $n_cnt . ', ';

                        $a_conditions['payment_way' . $n_cnt] = $aa_conditions['payment_way'][$n_cnt];

                        // payment_wayに2（現地決済）がくれば0（旧現地決済）を追加
                        if ($aa_conditions['payment_way'][$n_cnt] == 2) {
                            // 現地決済があったかの判断
                            $is_locale = true;
                        }
                    }
                }

                // payment_wayに2（現地決済）がくれば0（旧現地決済）を追加
                if ($is_locale == true) {
                    $n_cnt++;
                    $s_payment_ways .= ':payment_way' . $n_cnt . ', ';
                    $a_conditions['payment_way' . $n_cnt] = 0;
                }

                if (!($this->is_empty($s_payment_ways))) {
                    $s_payment_ways = substr($s_payment_ways, 0, -2) . ')';
                }
            }

            // お天気保険
            if (!$this->is_empty($aa_conditions['insurance_weather'] ?? null)) { //null追記でいいか
                $s_insurance_weather = '	and	reserve_insurance_weather.status = 1';
            }

            // 都道府県
            if (!($this->is_empty($aa_conditions['pref_id'] ?? null))) { //null追記でいいか
                // 利用人数の設定
                $a_conditions['pref_id'] = $aa_conditions['pref_id'];

                $s_pref = '	and	reserve.hotel_cd in (select hotel_cd from hotel where hotel.pref_id = :pref_id)';
            }

            // 利用人数
            if (!($this->is_empty($aa_conditions['capacity'] ?? null))) { //null追記でいいか
                // 利用人数の設定
                $a_conditions['capacity'] = $aa_conditions['capacity']['people'];

                // タイプが人数が同じ場合を選択されていた場合
                if ($aa_conditions['capacity']['type'] == 'same') {
                    $s_capacity = 'and	reserve_plan.capacity = :capacity';

                    // タイプが人数以上を選択されていた場合
                } else if ($aa_conditions['capacity']['type'] == 'large') {
                    $s_capacity = 'and	reserve_plan.capacity >= :capacity';
                }
            }

            // キャンセル状態
            if (!($this->is_empty($aa_conditions['reserve_status'] ?? null))) { //null追記でいいか
                // キャンセルを含まない
                if ($aa_conditions['reserve_status'] == 0) {
                    $s_reserve_status = '	and	reserve.reserve_status = 0';

                    //  キャンセルのみ
                } elseif ($aa_conditions['reserve_status'] == 1) {
                    $s_reserve_status = '	and	reserve.reserve_status <> 0';
                }
            }

            // 宿泊日を設定
            if (!$this->is_empty($aa_conditions['date_ymd']['after'] ?? null)) { //null追記でいいか
                $s_after_date_ymd = "	and	reserve.date_ymd >= date_format(:after_date_ymd, '%Y-%m-%d')";
                $a_conditions['after_date_ymd'] = $aa_conditions['date_ymd']['after'];
            }

            if (!$this->is_empty($aa_conditions['date_ymd']['before'] ?? null)) { //null追記でいいか
                $s_before_date_ymd = "	and	reserve.date_ymd <= date_format(:before_date_ymd, '%Y-%m-%d')";
                $a_conditions['before_date_ymd'] = $aa_conditions['date_ymd']['before'];
            }

            // 予約日を設定
            if (!$this->is_empty($aa_conditions['reserve_dtm']['after'] ?? null)) { //null追記でいいか
                $s_after_reserve_dtm = "	and	reserve.reserve_dtm >= date_format(:after_reserve_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_reserve_dtm'] = $aa_conditions['reserve_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['reserve_dtm']['before'] ?? null)) { //null追記でいいか
                $s_before_reserve_dtm = "	and	reserve.reserve_dtm <= date_format(:before_reserve_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_reserve_dtm'] = $aa_conditions['reserve_dtm']['before'];
            }

            // 取り消し日を設定
            if (!$this->is_empty($aa_conditions['cancel_dtm']['after'] ?? null)) { //null追記でいいか
                $s_after_cancel_dtm = "	and	reserve.cancel_dtm >= date_format(:after_cancel_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_cancel_dtm'] = $aa_conditions['cancel_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['cancel_dtm']['before'] ?? null)) { //null追記でいいか
                $s_before_cancel_dtm = "	and	reserve.cancel_dtm <= date_format(:before_cancel_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_cancel_dtm'] = $aa_conditions['cancel_dtm']['before'];
            }

            // reserve_type 0:手配旅行 1:募集型企画旅行（1がJRコレクションに該当）
            if (!$this->is_empty($aa_conditions['reserve_type'] ?? null)) { //null追記でいいか
                $s_reserve_type = "        and    reserve.reserve_type = " . $aa_conditions['reserve_type'];
            }

            // ポイント利用可否
            if (!$this->is_empty($aa_conditions['use_point'] ?? null)) { //null追記でいいか
                $s_use_point =
                <<< SQL
and	exists(
							select	sum(case
										when discount_factor_id = 2 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
										when discount_factor_id = 3 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
										when discount_factor_id = 4 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
									end)
							from	reserve_charge_discount
							where	reserve_charge_discount.reserve_cd = reserve_charge.reserve_cd
								and	reserve_charge_discount.date_ymd   = reserve_charge.date_ymd
								and	reserve_charge_discount.discount_charge > 0
							having sum(case
										when discount_factor_id = 2 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
										when discount_factor_id = 3 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
										when discount_factor_id = 4 then
											case
												when	reserve_charge.payment_way = 2 then
													1
												else
													0
											end
									end) = 1
		)
SQL;
            }

            // 部屋名称のサイズ拡張に伴い、互換維持のため
            // インクエリ、外部連携は、短縮版を使用する。
            // if (in_array($this->box->info->env->module, array('inq', 'pol'))) {
            if (in_array('', ['inq', 'pol'])) { //TODO 書き換えどうすればいい？
                $s_clm_room_nm = 'room_nm';
            } else {
                $s_clm_room_nm = 'room_nl as room_nm';
            }


            $s_sql =
            <<< SQL
					select	q8.reserve_cd,
							q8.date_ymd as date_ymd, -- 書き替えあっているか？
							q8.order_cd,
							q8.transaction_cd,
							q8.partner_ref,
							q8.partner_cd,
							q8.affiliate_cd,
							q8.affiliate_cd_sub,
							q8.member_cd,
							q8.guests,
							q8.auth_type,
							q8.reserve_system,
							q8.reserve_status,
							q8.reserve_dtm as reserve_dtm, -- 書き替えあっているか？
							q8.cancel_dtm as cancel_dtm, -- 書き替えあっているか？
							q8.reserve_type,
							q8.hotel_cd,
							q8.room_cd,
							q8.plan_cd,
							q8.hotel_nm,
							q8.room_nm,
							q8.plan_nm,
							q8.premium_status,
							q8.room_type,
							q8.floorage_min,
							q8.floorage_max,
							q8.floor_unit,
							q8.stock_type,
							q8.network,
							q8.rental,
							q8.connector,
							q8.network_note,
							q8.plan_type,
							q8.charge_type,
							q8.capacity,
							q8.payment_way as plan_payment_way,
							q8.stay_limit,
							q8.check_in,
							q8.guest_nm,
							q8.guest_last_nm,
							q8.guest_first_nm,
							q8.guest_last_nm_kn,
							q8.guest_first_nm_kn,
							q8.guest_tel,
							q8.guest_pref_id,
							q8.guest_address,
							q8.guest_group,
							q8.smoke,
							q8.male,
							q8.female,
							q8.guest_note,
							q8.info,
							q8.extension_value,
							q8.contact_status,
							q8.weather_status,
							q8.weather_condition,
							q8.weather_insurance_charge,
							q8.weather_present_charge,
							reserve_charge.partner_group_id,
							reserve_charge.usual_charge,
							reserve_charge.discount_type,
							reserve_charge.before_sales_charge,
							reserve_charge.sales_charge,
							reserve_charge.tax_charge,
							reserve_charge.stay_tax_charge,
							reserve_charge.cancel_charge,
							ifNull(reserve_charge.base_cancel_charge, reserve_charge.cancel_charge) base_cancel_charge,
							reserve_charge.payment_way,
							reserve_charge.later_payment,
							reserve_charge.system_rate
					from	reserve_charge,
						(
							select	q7.reserve_cd,
									q7.date_ymd,
									q7.order_cd,
									q7.transaction_cd,
									q7.partner_ref,
									q7.partner_cd,
									q7.affiliate_cd,
									q7.affiliate_cd_sub,
									q7.member_cd,
									q7.guests,
									q7.auth_type,
									q7.reserve_system,
									q7.reserve_status,
									q7.reserve_dtm,
									q7.cancel_dtm,
									q7.reserve_type,
									q7.hotel_cd,
									q7.room_cd,
									q7.plan_cd,
									q7.hotel_nm,
									q7.room_nm,
									q7.plan_nm,
									q7.premium_status,
									q7.room_type,
									q7.floorage_min,
									q7.floorage_max,
									q7.floor_unit,
									q7.stock_type,
									q7.network,
									q7.rental,
									q7.connector,
									q7.network_note,
									q7.plan_type,
									q7.charge_type,
									q7.capacity,
									q7.payment_way,
									q7.stay_limit,
									q7.check_in,
									q7.guest_nm,
									q7.guest_last_nm,
									q7.guest_first_nm,
									q7.guest_last_nm_kn,
									q7.guest_first_nm_kn,
									q7.guest_tel,
									q7.guest_pref_id,
									q7.guest_address,
									q7.guest_group,
									q7.smoke,
									q7.male,
									q7.female,
									q7.guest_note,
									q7.info,
									q7.extension_value,
									q7.contact_status,
									reserve_insurance_weather.status as weather_status,
									reserve_insurance_weather.condition as weather_condition,
									reserve_insurance_weather.insurance_charge as weather_insurance_charge,
									reserve_insurance_weather.present_charge as weather_present_charge
							from	reserve_insurance_weather
                            right outer join
								(
									select	q6.reserve_cd,
											q6.date_ymd,
											q6.order_cd,
											q6.transaction_cd,
											q6.partner_ref,
											q6.partner_cd,
											q6.affiliate_cd,
											q6.affiliate_cd_sub,
											q6.member_cd,
											q6.guests,
											q6.auth_type,
											q6.reserve_system,
											q6.reserve_status,
											q6.reserve_dtm,
											q6.cancel_dtm,
											q6.reserve_type,
											q6.hotel_cd,
											q6.room_cd,
											q6.plan_cd,
											q6.hotel_nm,
											q6.room_nm,
											q6.plan_nm,
											q6.premium_status,
											q6.room_type,
											q6.floorage_min,
											q6.floorage_max,
											q6.floor_unit,
											q6.stock_type,
											q6.network,
											q6.rental,
											q6.connector,
											q6.network_note,
											q6.plan_type,
											q6.charge_type,
											q6.capacity,
											q6.payment_way,
											q6.stay_limit,
											q6.check_in,
											q6.guest_nm,
											q6.guest_last_nm,
											q6.guest_first_nm,
											q6.guest_last_nm_kn,
											q6.guest_first_nm_kn,
											q6.guest_tel,
											q6.guest_pref_id,
											q6.guest_address,
											q6.guest_group,
											q6.smoke,
											q6.male,
											q6.female,
											q6.guest_note,
											q6.info,
											q6.extension_value,
											case when reserve_contact.email is null then 0 else 1 end as contact_status
									from	reserve_contact
                                    right outer join
										(
											select	q5.reserve_cd,
													q5.date_ymd,
													q5.order_cd,
													q5.transaction_cd,
													q5.partner_ref,
													q5.partner_cd,
													q5.affiliate_cd,
													q5.affiliate_cd_sub,
													q5.member_cd,
													q5.guests,
													q5.auth_type,
													q5.reserve_system,
													q5.reserve_status,
													q5.reserve_dtm,
													q5.cancel_dtm,
													q5.reserve_type,
													q5.hotel_cd,
													q5.room_cd,
													q5.plan_cd,
													q5.hotel_nm,
													q5.room_nm,
													q5.plan_nm,
													q5.premium_status,
													q5.room_type,
													q5.floorage_min,
													q5.floorage_max,
													q5.floor_unit,
													q5.stock_type,
													q5.network,
													q5.rental,
													q5.connector,
													q5.network_note,
													q5.plan_type,
													q5.charge_type,
													q5.capacity,
													q5.payment_way,
													q5.stay_limit,
													q5.check_in,
													q5.guest_nm,
													q5.guest_last_nm,
													q5.guest_first_nm,
													q5.guest_last_nm_kn,
													q5.guest_first_nm_kn,
													q5.guest_tel,
													q5.guest_pref_id,
													q5.guest_address,
													q5.guest_group,
													q5.smoke,
													q5.male,
													q5.female,
													q5.guest_note,
													q5.info,
													reserve_extension.extension_value
											from	reserve_extension
                                            right outer join
												(
													select	q4.reserve_cd,
															q4.date_ymd,
															q4.order_cd,
															q4.transaction_cd,
															q4.partner_ref,
															q4.partner_cd,
															q4.affiliate_cd,
															q4.affiliate_cd_sub,
															q4.member_cd,
															q4.guests,
															q4.auth_type,
															q4.reserve_system,
															q4.reserve_status,
															q4.reserve_dtm,
															q4.cancel_dtm,
															q4.reserve_type,
															q4.hotel_cd,
															q4.room_cd,
															q4.plan_cd,
															q4.hotel_nm,
															q4.room_nm,
															q4.plan_nm,
															q4.premium_status,
															q4.room_type,
															q4.floorage_min,
															q4.floorage_max,
															q4.floor_unit,
															q4.stock_type,
															q4.network,
															q4.rental,
															q4.connector,
															q4.network_note,
															q4.plan_type,
															q4.charge_type,
															q4.capacity,
															q4.payment_way,
															q4.stay_limit,
															q4.check_in,
															q4.guest_nm,
															q4.guest_last_nm,
															q4.guest_first_nm,
															q4.guest_last_nm_kn,
															q4.guest_first_nm_kn,
															q4.guest_tel,
															q4.guest_pref_id,
															q4.guest_address,
															q4.guest_group,
															q4.smoke,
															q4.male,
															q4.female,
															q4.guest_note,
															reserve_plan_info.info
													from	reserve_plan_info
                                                    right outer join
														(
															select	q3.reserve_cd,
																	q3.date_ymd,
																	q3.order_cd,
																	q3.transaction_cd,
																	q3.partner_ref,
																	q3.partner_cd,
																	q3.affiliate_cd,
																	q3.affiliate_cd_sub,
																	q3.member_cd,
																	q3.guests,
																	q3.auth_type,
																	q3.reserve_system,
																	q3.reserve_status,
																	q3.reserve_dtm,
																	q3.cancel_dtm,
																	q3.reserve_type,
																	q3.hotel_cd,
																	q3.room_cd,
																	q3.plan_cd,
																	q3.hotel_nm,
																	q3.room_nm,
																	q3.plan_nm,
																	q3.premium_status,
																	q3.room_type,
																	q3.floorage_min,
																	q3.floorage_max,
																	q3.floor_unit,
																	q3.stock_type,
																	q3.network,
																	q3.rental,
																	q3.connector,
																	q3.network_note,
																	q3.plan_type,
																	q3.charge_type,
																	q3.capacity,
																	q3.payment_way,
																	q3.stay_limit,
																	reserve_guest.check_in,
																	reserve_guest.guest_nm,
																	reserve_guest.guest_last_nm,
																	reserve_guest.guest_first_nm,
																	reserve_guest.guest_last_nm_kn,
																	reserve_guest.guest_first_nm_kn,
																	reserve_guest.guest_tel,
																	reserve_guest.guest_pref_id,
																	reserve_guest.guest_address,
																	reserve_guest.guest_group,
																	reserve_guest.smoke,
																	reserve_guest.male,
																	reserve_guest.female,
																	reserve_guest.note as guest_note
															from	reserve_guest,
																(
																	select	q2.reserve_cd,
																			q2.date_ymd,
																			q2.order_cd,
																			q2.transaction_cd,
																			q2.partner_ref,
																			q2.partner_cd,
																			q2.affiliate_cd,
																			q2.affiliate_cd_sub,
																			q2.member_cd,
																			q2.guests,
																			q2.auth_type,
																			q2.reserve_system,
																			q2.reserve_status,
																			q2.reserve_dtm,
																			q2.cancel_dtm,
																			q2.reserve_type,
																			reserve_plan.hotel_cd,
																			reserve_plan.room_cd,
																			reserve_plan.plan_cd,
																			reserve_plan.hotel_nm,
																			reserve_plan.{$s_clm_room_nm},
																			reserve_plan.plan_nm,
																			reserve_plan.premium_status,
																			reserve_plan.room_type,
																			reserve_plan.floorage_min,
																			reserve_plan.floorage_max,
																			reserve_plan.floor_unit,
																			reserve_plan.stock_type,
																			reserve_plan.network,
																			reserve_plan.rental,
																			reserve_plan.connector,
																			reserve_plan.network_note,
																			reserve_plan.plan_type,
																			reserve_plan.charge_type,
																			reserve_plan.capacity,
																			reserve_plan.payment_way,
																			reserve_plan.stay_limit
																	from	reserve_plan,
																		(
																			select	reserve.reserve_cd,
																					reserve.date_ymd,
																					reserve.order_cd,
																					reserve.transaction_cd,
																					reserve.partner_ref,
																					reserve.partner_cd,
																					reserve.affiliate_cd,
																					reserve.affiliate_cd_sub,
																					reserve.member_cd,
																					reserve.guests,
																					reserve.auth_type,
																					reserve.reserve_system,
																					reserve.reserve_status,
																					reserve.reserve_dtm,
																					reserve.cancel_dtm,
																					reserve.reserve_type
																			from	reserve
																			where	null is null
																				{$s_pref}
																				{$s_member_cd}
																				{$s_member_cds}
																				{$s_reserve_status}
																				{$s_reserve_cd}
																				{$s_partner_ref}
																				{$s_partner_cd}
																				{$s_common_cd}
																				{$s_date_ymd}
																				{$s_reserve_system}
																				{$s_affiliate_cd}
																				{$s_affiliate_cd_sub}
																				{$s_auth_type}
																				{$s_after_date_ymd}
																				{$s_before_date_ymd}
																				{$s_reserve_dtm}
																				{$s_after_reserve_dtm}
																				{$s_before_reserve_dtm}
																				{$s_cancel_dtm}
																				{$s_after_cancel_dtm}
																				{$s_before_cancel_dtm}
																				{$s_hotel_cd}
																				{$s_hotel_cds}
																				{$s_reserve_type}
																				{$s_supervisor_cd}
																		) q2
																	where	reserve_plan.reserve_cd = q2.reserve_cd
																		{$s_hotel_nm}
																		{$s_stock_types}
																		{$s_capacity}
																) q3
															where	reserve_guest.reserve_cd = q3.reserve_cd
																{$s_guest_nm}
																{$s_member_nm}
														) q4
													-- where	reserve_plan_info.reserve_cd(+) = q4.reserve_cd -- 書き換えあっているか
                                                    on reserve_plan_info.reserve_cd = q4.reserve_cd
												) q5
											-- where	reserve_extension.reserve_cd(+) = q5.reserve_cd -- 書き換えあっているか
                                            on reserve_extension.reserve_cd = q5.reserve_cd
										) q6
									-- where	reserve_contact.reserve_cd(+) = q6.reserve_cd -- 書き換えあっているか
                                    on reserve_contact.reserve_cd = q6.reserve_cd
										{$s_email}
								) q7
							-- where	reserve_insurance_weather.reserve_cd(+) = q7.reserve_cd -- 書き換えあっているか
							-- 	and	reserve_insurance_weather.date_ymd(+)   = q7.date_ymd 
                            on reserve_insurance_weather.reserve_cd = q7.reserve_cd -- 書き換えあっているか
							 and reserve_insurance_weather.date_ymd = q7.date_ymd
								{$s_insurance_weather}
						) q8
					where	null is null
						and	reserve_charge.reserve_cd = q8.reserve_cd
						and	reserve_charge.date_ymd   = q8.date_ymd
							{$s_payment_ways}
							{$s_use_point}
					order by q8.reserve_cd, q8.date_ymd
SQL;

            // データの取得
            $this->a_reserves = DB::select($s_sql, $a_conditions);
            $data = DB::select($s_sql, $a_conditions); //確認用

            return true;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * １泊単位の予約情報を取得 ※ReserveのModelから移植
     * ※ このプログラムを修正する場合は \public\batch\scripts\php\models\Msd.php _get_reserve_days も修正が必要です。（MSD)
     *
     * @param array{
     *  include_member?: bool,        true : 会員情報を取得します。
     * } $aa_conditions
     * @param array{
     *  array{
     *   date_ymd?: string
     *   > array('date_ymd' => 'asc')     宿泊日昇順
     *   > array('date_ymd' => 'desc')    宿泊日降順
     *   reserve_dtm?: string
     *   > array('reserve_dtm' => 'asc')  予約日昇順
     *   > array('reserve_dtm' => 'desc') 予約日降順
     *   上記のうちのひとつを設定します。
     *  },
     * } $aa_order 表示順序 array('カラム' => 'asc or desc')
     * @param array{
     *  page?: int, ページ
     *  size?: int, レコード数(1から) ページ数を指定した場合必須
     * } $aa_offsets
     *
     * @return array
     */
    public function getReserveDays($aa_conditions = [], $aa_order = ['date_ymd' => 'desc'], $aa_offsets = [])
    {
        try {
            $a_result = $this->parentGetReserveDays($aa_conditions, $aa_order, $aa_offsets);

            if (!($this->is_empty($a_result['values']))) {
                $models_hotel = new Hotel();
                $mast_pref = new MastPref();

                $core_charge = new Charge();
                foreach ($a_result['values'] as $key => $value) {
                    //以下書き換えでいいか？ $models_hotel->set_hotel_cd($a_result['values'][$key]['hotel_cd']);
                    $models_hotel->s_hotel_cd = $a_result['values'][$key]->hotel_cd;

                    $a_mast_pref = $mast_pref->selectByKey($a_result['values'][$key]->hotel['pref_id']); //find→selectByKeyでいいか？

                    $a_result['values'][$key]->pref_cd = $a_mast_pref['pref_cd'];
                    $a_result['values'][$key]->pref_nm = $a_mast_pref['pref_nm'];

                    // 2009-07-01 以降に予約手続きが行われた予約は予約日時を元にシステム利用料を算出
                    $o_date = new DateUtil($a_result['values'][$key]->reserve_dtm); //Br_Models_Date→DateUtilでいいか？
                    if ($o_date->to_format('Ymd') < '20090701') {
                        $o_date = new DateUtil($a_result['values'][$key]->date_ymd); //Br_Models_Date→DateUtilでいいか？
                    }

                    // 予約時のポイント設定情報
                    $reserve_point = new ReservePoint();
                    $a_reserve_point = $reserve_point->selectByKey($a_result['values'][$key]->reserve_cd); //find→selectByKeyでいいか？

                    // システム利用料とシステム利用率を取得
                    $a_result['values'][$key]->bill['point_rate'] = $a_reserve_point['issue_point_rate'] - $a_reserve_point['issue_point_rate_our'];
                    $a_result['values'][$key]->bill['rate'] = $a_result['values'][$key]->system_rate;

                    if ($a_result['values'][$key]->reserve_status == 0) {
                        $a_result['values'][$key]->bill['bill_charge'] = floor(($a_result['values'][$key]->sales_charge - $a_result['values'][$key]->tax_charge - $a_result['values'][$key]->stay_tax_charge) * ($a_result['values'][$key]->system_rate / 100));
                        $a_result['values'][$key]->bill['point_charge'] = floor(($a_result['values'][$key]->sales_charge - $a_result['values'][$key]->tax_charge) * $a_reserve_point['issue_point_rate'] / 100) - floor(($a_result['values'][$key]->sales_charge - $a_result['values'][$key]->tax_charge) * $a_reserve_point['issue_point_rate_our'] / 100);

                        $a_result['values'][$key]->cancel_tax_charge = null; //elseと合わせるためにnullで追記していいか？

                        // キャンセル料に対するシステム利用料を設定
                    } else {
                        $o_date->set((int)$a_result['values'][$key]->date_ymd);
                        $a_tax_rate  = $core_charge->get_tax_rate($o_date->to_format('Y-m-d')); //未修整
                        $n_notax_cancel_charge = ceil($a_result['values'][$key]->cancel_charge / ($a_tax_rate['values'] / 100 + 1));
                        $a_result['values'][$key]->bill['bill_charge']  = floor($n_notax_cancel_charge * ($a_result['values'][$key]->system_rate / 100));
                        $a_result['values'][$key]->bill['point_charge'] = floor($n_notax_cancel_charge * $a_reserve_point['issue_point_rate'] / 100) - floor($n_notax_cancel_charge * $a_reserve_point['issue_point_rate_our'] / 100);

                        // キャンセル料に対する消費税を設定
                        $a_result['values'][$key]->cancel_tax_charge = $a_result['values'][$key]->cancel_charge - $n_notax_cancel_charge;
                    }

                    // 予約時のyahoo!のポイント情報の取得
                    $s_sql =
                    <<< SQL
							select	reserve_cd,
									transaction_cd,
									yahoo_point_type,
									get_yahoo_point,
									use_yahoo_point,
									date_ymd as date_ymd -- 書き換えあっている？
							from	yahoo_point_book_pre
							where	reserve_cd = :reserve_cd
SQL;

                    $a_yahoo_point_book = DB::select($s_sql, ['reserve_cd' => $a_result['values'][$key]->reserve_cd]);

                    foreach ($a_yahoo_point_book as $val) {
                        if ($val['yahoo_point_type'] < 0) {
                            $val['yahoo_point_type'] *= -1;
                        }

                        // yahoo_point_type 1:仮獲得  2:仮消費
                        if ($val['yahoo_point_type'] == 1) {
                            $a_result['values'][$key]->yahoo_point_book['get_yahoo_point'] += $val['get_yahoo_point'];
                        } elseif ($val['yahoo_point_type'] == 2) {
                            $a_result['values'][$key]->yahoo_point_book['use_yahoo_point'] += $val['use_yahoo_point'];
                        }
                    }
                }
            }

            return $a_result;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * １泊単位の予約情報を取得 ※ReserveのCoreから移植
     * ※ このプログラムを修正する場合は \public\batch\scripts\php\models\Msd.php _get_reserve_days も修正が必要です。（MSD)
     *
     * @param array{
     *  include_member?: bool,        true : 会員情報を取得します。
     * } $aa_conditions
     * @param array{
     *  array{
     *   date_ymd?: string
     *   > array('date_ymd' => 'asc')     宿泊日昇順
     *   > array('date_ymd' => 'desc')    宿泊日降順
     *   reserve_dtm?: string
     *   > array('reserve_dtm' => 'asc')  予約日昇順
     *   > array('reserve_dtm' => 'desc') 予約日降順
     *   cancel_dtm?: string
     *   > array('cancel_dtm' => 'asc')  キャンセル日昇順
     *   > array('cancel_dtm' => 'desc') キャンセル日降順
     *  上記のうちのひとつを設定します。
     *  },
     * } $aa_order 表示順序 array('カラム' => 'asc or desc')
     * @param array{
     *  page?: int, ページ
     *  size?: int, レコード数(1から) ページ数を指定した場合必須
     * } $aa_offsets
     *
     * @return array
     */
    public function parentGetReserveDays($aa_conditions = [], $aa_order = ['date_ymd' => 'desc'], $aa_offsets = [])
    {
        try {
            $a_reserves = $this->a_reserves;

            $hotel = new Hotel();
            $hotel_status = new HotelStatus();
            $partner = new Partner();
            //JRプラン、日本旅行は削除


            // 取得対象を絞り込む
            for ($n_cnt = 0; $n_cnt < count($a_reserves); $n_cnt++) {
                if (!($this->is_empty($aa_order['date_ymd']))) {
                    $a_sort['value'][] = $a_reserves[$n_cnt]->date_ymd;
                    $a_sort['reserve_cd'][] = $a_reserves[$n_cnt]->reserve_cd;
                    if ($aa_order['date_ymd'] == 'desc') {
                        $a_sort['sort']  = SORT_DESC;
                    } else {
                        $a_sort['sort']  = SORT_ASC;
                    }
                } elseif (!($this->is_empty($aa_order['reserve_dtm']))) {
                    $a_sort['value'][] = $a_reserves[$n_cnt]->reserve_dtm;
                    $a_sort['reserve_cd'][] = $a_reserves[$n_cnt]->reserve_cd;
                    if ($aa_order['reserve_dtm'] == 'desc') {
                        $a_sort['sort']  = SORT_DESC;
                    } else {
                        $a_sort['sort']  = SORT_ASC;
                    }
                } elseif (!($this->is_empty($aa_order['cancel_dtm']))) {
                    $a_sort['value'][]       = ($a_reserves[$n_cnt]->cancel_dtm ?? time() + 1); // nvl→??でいいか？
                    $a_sort['date_ymd'][]    = $a_reserves[$n_cnt]->date_ymd;
                    $a_sort['reserve_dtm'][] = $a_reserves[$n_cnt]->reserve_dtm;
                    $a_sort['reserve_cd'][]  = $a_reserves[$n_cnt]->reserve_cd;
                    if ($aa_order['cancel_dtm'] == 'desc') {
                        $a_sort['sort']  = SORT_DESC;
                    } else {
                        $a_sort['sort']  = SORT_ASC;
                    }
                }
            }

            if (!$this->is_empty($aa_order['date_ymd']) || !$this->is_empty($aa_order['reserve_dtm'])) {
                // ソート
                if (!($this->is_empty($a_reserves))) {
                    array_multisort(
                        $a_sort['value'],
                        $a_sort['sort'],
                        $a_sort['reserve_cd'],
                        SORT_ASC,
                        $a_reserves
                    );
                }
            } elseif (!$this->is_empty($aa_order['cancel_dtm'])) {
                // ソート
                if (!($this->is_empty($a_reserves))) {
                    array_multisort(
                        $a_sort['value'],
                        $a_sort['sort'],
                        $a_sort['date_ymd'],
                        SORT_ASC,
                        $a_sort['reserve_dtm'],
                        SORT_ASC,
                        $a_sort['reserve_cd'],
                        SORT_ASC,
                        $a_reserves
                    );
                }
            }

            // 必要な情報のみ取得（１ページ分）
            if (!($this->is_empty($aa_offsets['page'] ?? null))) { //null追記でいいか？
                $start = ($aa_offsets['size'] * $aa_offsets['page']) - $aa_offsets['size'];
                $end   = $aa_offsets['size'] * $aa_offsets['page'];

                for ($n_cnt = $start; $n_cnt < $end; $n_cnt++) {
                    if ($this->is_empty($a_reserves[$n_cnt] ?? null)) { //null追記でいいか？
                        $end = $n_cnt;
                        break;
                    }
                    $a_reserves[$n_cnt]->total_page  = ceil(count($a_reserves) / $aa_offsets['size']);
                    $a_reserves[$n_cnt]->total_count = count($a_reserves);
                }

                // 全件返す
            } else {
                $start = 0;
                $end   = count($a_reserves);
            }

            $a_result = []; //初期化[]でいいか

            // 対象データに必要な付加情報を追加
            for ($n_cnt = $start; $n_cnt < $end; $n_cnt++) {
                $a_result[] = $a_reserves[$n_cnt];
                $i = count($a_result) - 1;

                $buf_reserve_cd = null; //$buf_reserve_cdの定義がなかったので追記していいか？

                if ($a_result[$i]->reserve_cd != $buf_reserve_cd || $this->is_empty($buf_reserve_cd)) {
                    // 提携先を取得
                    $a_partner = $partner->find(['partner_cd' => $a_result[$i]->partner_cd]);

                    // 会員情報を取得する場合
                    $a_member = []; //初期化追記していいか？
                    if ($aa_conditions['include_member']) {
                        // 会員情報を取得
                        $a_member = $this->getMember($a_result[$i]->partner_cd, $a_result[$i]->member_cd, $a_result[$i]->auth_type);
                    }

                    // 宿泊体験存在確認
                    $voice = $this->hasVoice(['reserve_cd' => $a_result[$i]->reserve_cd]);

                    // リザーブプランスペックの取得
                    $reserve_plan_specs = $this->getReservePlanSpecs(['reserve_cd' => $a_result[$i]->reserve_cd]);

                    // 施設情報の取得
                    $a_hotel = $hotel->selectByKey(['hotel_cd' => $a_result[$i]->hotel_cd]); //find→selectByKeyでもいいか？（findでも取れなくないが、戻した後のソースに合わない）

                    // 施設情報状態の取得
                    $a_hotel_status = $hotel_status->selectByKey(['hotel_cd' => $a_result[$i]->hotel_cd]); //find→selectByKeyでもいいか？（findでも取れなくないが、戻した後のソースに合わない）

                    // 施設注意事項の取得
                    $core_hotel = new Hotel();
                    // $core_hotel->set_hotel_cd($a_result[$i]->hotel_cd']); 書き替えは以下でいい？？
                    $core_hotel->s_hotel_cd = $a_result[$i]->hotel_cd;
                    $hotel_inform_cancel = $core_hotel->getHotelInformCancel();

                    // 予約追加メッセージの取得
                    $core_reserve_added_msg   = new ReserveAddedMessage();
                    $a_reserve_added_msgs = $core_reserve_added_msg->getReserveAddedMessage($a_result[$i]->reserve_cd);
                    $s_goto_msg_for_hotel = ""; //初期化追記したが、GOTO不要？
                    $s_goto_msg_for_guest = "";
                    foreach ($a_reserve_added_msgs as $a_reserve_added_msg) {
                        if ($a_reserve_added_msg['msg_type'] == 1) {
                            //GoToクーポン取り消し時のメッセージ
                            $s_goto_msg_for_hotel = $a_reserve_added_msg['msg_for_hotel'];
                            $s_goto_msg_for_guest = $a_reserve_added_msg['msg_for_guest'];
                        }
                    }

                    // アイコン設定
                    // 金土日
                    if ($a_result[$i]->plan_type == 'fss') {
                        $a_icons['fss'] = true;
                    } else {
                        $a_icons['fss'] = false;
                    }

                    // 連泊プラン
                    if ($a_result[$i]->stay_limit != 1) {
                        $a_icons['stay_limit'] = true;
                    } else {
                        $a_icons['stay_limit'] = false;
                    }

                    if ($a_result[$i]->smoke == 1 && !preg_match('/^禁煙ルームを希望します。/', $a_result[$i]->guest_note)) {
                        $a_result[$i]->guest_note = '禁煙ルームを希望します。' . $a_result[$i]->guest_note;
                    } elseif ($a_result[$i]->smoke == 2 && !preg_match('/^タバコが吸える部屋を希望します。/', $a_result[$i]->guest_note)) {
                        $a_result[$i]->guest_note = 'タバコが吸える部屋を希望します。' . $a_result[$i]->guest_note;
                    }
                }

                $buf_reserve_cd = $a_result[$i]->reserve_cd;

                // 値を設定
                $a_result[$i]->partner             = $a_partner;
                $a_result[$i]->member              = $a_member;
                $a_result[$i]->voice               = $voice;
                $a_result[$i]->reserve_plan_specs  = $reserve_plan_specs;
                $a_result[$i]->hotel_inform_cancel = $hotel_inform_cancel;
                $a_result[$i]->hotel               = $a_hotel;
                $a_result[$i]->icons               = $a_icons;
                $a_result[$i]->hotel_status        = $a_hotel_status;
                $a_result[$i]->goto_msg_for_hotel  = $s_goto_msg_for_hotel;
                $a_result[$i]->goto_msg_for_guest  = $s_goto_msg_for_guest;

                // 電話キャンセル可否を設定
                $o_sysdate = new DateUtil(); //Br_Models_Date→DateUtilでいいか
                if ($this->isMidnight($a_result[$i]->date_ymd)) {
                    $o_sysdate->add('d', -1);
                }
                if (0 <= $o_sysdate->diff('d', (int)$a_result[$i]->date_ymd)) {
                    $a_result[$i]->cancel['tel']   = true;
                } else {
                    $a_result[$i]->cancel['tel']   = false;
                }


                // 割引料金の取得
                $a_result[$i]->reserve_charge_discounts = $this->getReserveChargeDiscounts([
                    'reserve_cd' => $a_result[$i]->reserve_cd,
                    'date_ymd'   => $a_result[$i]->date_ymd
                ]);

                if (!($this->is_empty($aa_order['date_ymd']))) {
                    $a_sort['value'][] = $a_result[$i]->date_ymd;
                    if ($aa_order['date_ymd'] == 'desc') {
                        $a_sort['sort']  = 3;
                    } else {
                        $a_sort['sort']  = 4;
                    }
                } elseif (!($this->is_empty($aa_order['reserve_dtm']))) {
                    $a_sort['value'][] = $a_result[$i]->reserve_dtm;
                    if ($aa_order['reserve_dtm'] == 'desc') {
                        $a_sort['sort']  = 3;
                    } else {
                        $a_sort['sort']  = 4;
                    }
                }
            }


            return [
                'values'     => $a_result,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 元ソースではCoreにあるが、ここでいいか？
    // 当日深夜予約であるか？
    public function isMidnight($as_check_in_ymd)
    {
        $o_date = new DateUtil(); //Br_Models_Date→DateUtilでいいか
        $o_date->diff('d', $as_check_in_ymd);

        if (
            $o_date->diff('d', $as_check_in_ymd) == -1
            && 0 <= (int)$o_date->to_format('H')
            && (int)$o_date->to_format('H') <= 5
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 予約割引料金の取得
     *
     * @param array{
     *  reserve_cd?: string, 予約コード
     *  date_ymd?: string, 宿泊日 YYYY-MM-DD
     *  discount_factor_id?: string, 割引要素ID 1:パワーダウンチャージ 2:Y!ポイント 3:ギフトチケット（ＢＲポイント）
     * } $aa_conditions
     *
     * @return array
     */
    private function getReserveChargeDiscounts($aa_conditions)
    {
        try {
            // 初期化
            $s_reserve_cd = '';
            $s_date_ymd = '';
            $s_discount_factor_id = '';

            // 予約コードを設定
            if (!$this->is_empty($aa_conditions['reserve_cd'] ?? null)) { //??null追記でいいか？
                $s_reserve_cd = '	and	reserve_charge_discount.reserve_cd = :reserve_cd';
                $a_conditions['reserve_cd'] = $aa_conditions['reserve_cd'];
            }

            // 宿泊日を設定
            if (!$this->is_empty($aa_conditions['date_ymd'] ?? null)) { //??null追記でいいか？
                $s_date_ymd = "	and	reserve_charge_discount.date_ymd = date_format(:date_ymd, '%Y-%m-%d')"; //to_date→date_formatでいいか？
                $o_date = new DateUtil($aa_conditions['date_ymd']); //Br_Models_Date→DateUtilでいいか

                $a_conditions['date_ymd'] = $o_date->to_format('Y-m-d');
            }

            // 割引要素IDを設定
            if (!$this->is_empty($aa_conditions['discount_factor_id'] ?? null)) { //??null追記でいいか？
                $s_discount_factor_id = '	and	reserve_charge_discount.discount_factor_id = :discount_factor_id';
                $a_conditions['discount_factor_id'] = $aa_conditions['discount_factor_id'];
            }


            $s_sql =
            <<< SQL
					select	reserve_charge_discount.reserve_cd,
							date_format(reserve_charge_discount.date_ymd, '%Y-%m-%d') as date_ymd, -- to_charはdate_formatでいいか？
							reserve_charge_discount.discount_factor_id,
							reserve_charge_discount.discount_charge
					from	reserve_charge_discount
					where	null is null
						{$s_reserve_cd}
						{$s_date_ymd}
						{$s_discount_factor_id}
SQL;

            $a_discount_charge = DB::select($s_sql, $a_conditions);
            $a_discount = [];
            for ($n_cnt = 0; $n_cnt < count($a_discount_charge); $n_cnt++) {
                $a_discount[$a_discount_charge[$n_cnt]['discount_factor_id']] = $a_discount_charge[$n_cnt];
            }
            // データの取得
            return [
                'values'     => $a_discount
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 宿泊体験が存在するか？
    //
    //   aa_conditions
    //     reserve_cd 予約コード
    //
    // example
    //   >> null 宿泊体験が存在しません。
    //   >> 1    宿泊体験が存在します。
    //   >> 2    宿泊体験が存在し、返答がされています。
    private function hasVoice($aa_conditions)
    {

        try {
            if ($this->is_empty($aa_conditions['reserve_cd'])) {
                throw new Exception('予約コードを設定してください。');
            }

            // 予約コードを設定
            if (!$this->is_empty($aa_conditions['reserve_cd'])) {
                $s_reserve_cd = '	and	voice_stay.reserve_cd = :reserve_cd';
            }

            $s_sql =
            <<< SQL
					select	voice + case when voice_reply.hotel_cd is not null then 1 else 0 end as voice -- nvl2(voice_reply.hotel_cd, 1, 0)の書き替えは←でいい？
					from	voice_reply
                    right outer join
						(
							select	voice_stay.voice_cd,
									voice_stay.hotel_cd,
									1 as voice
							from	voice_stay
							where	null is null
							and		status = 0
								{$s_reserve_cd}
						) q1
					-- where	voice_reply.hotel_cd(+) = q1.hotel_cd -- 書き換えあっているか？
					-- 	and	voice_reply.voice_cd(+) = q1.voice_cd
                    on voice_reply.hotel_cd = q1.hotel_cd
                     and	voice_reply.voice_cd = q1.voice_cd
SQL;

            // データの取得
            return [
                'values'     => DB::select($s_sql, $aa_conditions)
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 予約プランスペックの取得
    //
    //   aa_conditions
    //     reserve_cd 予約コード
    public function getReservePlanSpecs($aa_conditions)
    {

        try {
            if ($this->is_empty($aa_conditions['reserve_cd'])) {
                throw new Exception('予約コードを設定してください。');
            }

            // 予約コードを設定
            if (!$this->is_empty($aa_conditions['reserve_cd'])) {
                $s_reserve_cd = '	and	reserve_plan_spec.reserve_cd = :reserve_cd';
            }

            $s_sql =
            <<< SQL
					select	sq2.reserve_cd,
							sq2.element_id,
							sq2.element_value_id,
							sq2.element_nm,
							mast_plan_element_value.element_value_text
					from	mast_plan_element_value,
						(
							select	sq1.reserve_cd,
									sq1.element_id,
									sq1.element_value_id,
									mast_plan_element.element_nm
							from	mast_plan_element,
								(
									select	reserve_plan_spec.reserve_cd,
											reserve_plan_spec.element_id,
											reserve_plan_spec.element_value_id
									from	reserve_plan_spec
									where	null is null
										{$s_reserve_cd}
								) sq1
							where	mast_plan_element.element_id = sq1.element_id
						) sq2
					where	mast_plan_element_value.element_id = sq2.element_id
						and	mast_plan_element_value.element_value_id = sq2.element_value_id
					order by sq2.element_nm
SQL;

            // データの取得
            $a_row = DB::select($s_sql, $aa_conditions);
            $a_values = [];
            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                $a_values[$a_row[$n_cnt]['element_id']] = $a_row[$n_cnt];
            }

            return [
                'values'     => $a_values
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 予約者の会員情報を取得します。
     *
     * @param string $as_partner_cd
     * @param string $as_member_cd
     * @param string $as_auth_type
     *
     * @return array
     */
    private function getMember($as_partner_cd, $as_member_cd, $as_auth_type)
    {
        try {
            //都道府県モデルのインスタンスを取得
            $mast_pref = new MastPref();

            // 提携先会員認証
            if ($as_auth_type == 'partner') {
                $member['member_cd']        = $as_member_cd;

                if ($as_partner_cd == '2000004700') {
                    $hikari_account = new HikariAccount();
                    $a_hikari_account = $hikari_account->selectByKey($as_member_cd); //find→selectByKeyでいいか？
                    $member['account_id']  = $a_hikari_account['account_id'];
                }

                return $member;

                // 非会員認証
            } elseif ($as_auth_type == 'free') {
                $member_free = new MemberFree();
                $a_member_free = $member_free->selectByWKey($as_member_cd, $as_partner_cd); //find→selectByWKeyでいいか？

                if (!($this->is_empty($a_member_free))) {
                    //氏名（漢字またはアルファベット）の追加
                    if (!$this->is_empty($a_member_free['member_last_nm']) && !$this->is_empty($a_member_free['member_first_nm'])) {
                        $a_member_free['full_nm'] = trim($a_member_free['member_last_nm'] . ' ' . $a_member_free['member_first_nm']);
                    }

                    //氏名（ふりがな）の追加
                    if (!$this->is_empty($a_member_free['member_last_nm_kn']) && !$this->is_empty($a_member_free['member_first_nm_kn'])) {
                        $a_member_free['full_kn'] = trim($a_member_free['member_last_nm_kn'] . ' ' . $a_member_free['member_first_nm_kn']);
                    }

                    //都道府県名の追加
                    if (!$this->is_empty($a_member_free['pref_id'])) {
                        $a_pref    = $mast_pref->find(['pref_id' => $a_member_free['pref_id']]);
                        $a_member_free['pref_nm'] = $a_pref['pref_nm'];
                    }

                    //住所のkeyを変更($a_member_free['address']は使用していない)
                    if (!$this->is_empty($a_member_free['address'])) {
                        $a_member_free['address1'] = $a_member_free['address'];
                    }

                    //電話番号のkeyを変更($a_member_free['member_tel']は使用していない)
                    if (!$this->is_empty($a_member_free['member_tel'])) {
                        $a_member_free['tel'] = $a_member_free['member_tel'];
                    }

                    return $a_member_free;
                }

                // 会員認証
            } else {
                //機能実装後に再修正。今回はこのメソッド基本は使用しないため、空データ戻しで一旦実装

                // $http_client = new Zend_Http_Client();
                // $http_client->setConfig(array('timeout' => 60));
                // $client = new Zend_Rest_Client($this->box->config->rpc_server->protect->host . 'member/');
                // $client->setHttpClient($http_client);

                // $o_response =  $client->get_detail($as_member_cd, true)->get();

                // // エラーの場合
                // if (!($o_response->isSuccess())) {
                //     // システムエラーの場合
                //     if ($o_response->error_type() == 'system_error') {
                //         throw new Exception($o_response->messages());
                //     }
                //     return false;
                // }

                // if (!$this->is_empty($o_response->pref_id())) {
                //     $a_pref    = $mast_pref->find(['pref_id' => $o_response->pref_id()]);
                // }

                // // プロパティに設定
                // $member['member_cd']        = $o_response->member_cd();
                // $member['affiliate_cd']     = $o_response->affiliate_cd();
                // $member['affiliate_cd_sub'] = $o_response->affiliate_cd_sub();
                // $member['member_status']    = $o_response->member_status();
                // $member['entry_dtm']        = $o_response->entry_dtm();
                // $member['withdraw_dtm']     = $o_response->withdraw_dtm();
                // $member['note']             = $o_response->note();
                // $member['account_id']       = $o_response->account_id();
                // $member['full_nm']          = $o_response->family_nm() . ' ' . $o_response->given_nm();
                // $member['family_nm']        = $o_response->family_nm();
                // $member['given_nm']         = $o_response->given_nm();
                // $member['full_kn']          = $o_response->family_kn() . ' ' . $o_response->given_kn();
                // $member['family_kn']        = $o_response->family_kn();
                // $member['given_kn']         = $o_response->given_kn();
                // $member['gender']           = $o_response->gender();
                // $member['birth_ymd']        = $o_response->birth_ymd();
                // $member['contact_type']     = $o_response->contact_type();
                // $member['postal_cd']        = $o_response->postal_cd();
                // $member['pref_id']          = $o_response->pref_id();
                // $member['pref_nm']          = $a_pref['pref_nm'];
                // $member['address1']         = $o_response->address1();
                // $member['address2']         = $o_response->address2();
                // $member['tel']              = $o_response->tel();
                // $member['email']            = $o_response->email();
                // $member['member_group']     = $o_response->member_group();
                // $member['email1']           = $o_response->email1();
                // $member['email2']           = $o_response->email2();
                // $member['email3']           = $o_response->email3();

                // return $member;
                return [];
            }

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * １部屋単位の予約情報を取得
     *
     * @param array{
     *  include_member?: bool,        true : 会員情報を取得します。
     *  last_check_in?: array{
     *   after?: string, 日付 > 日付以降最終チェックインの予約 YYYY-MM-DD
     *   before?: string 日付 > 日付以前最終チェックインの予約 YYYY-MM-DD
     *  }
     * } $aa_conditions
     * @param array{
     *  array{
     *   transaction_cd?: string
     *   > array('transaction_cd' => 'asc')      予約単位昇順（１泊目で判断します。）
     *   date_ymd?: string
     *   > array('date_ymd' => 'asc')     宿泊日昇順（１泊目で判断します。）
     *   > array('date_ymd' => 'desc')    宿泊日降順（１泊目で判断します。）
     *   reserve_dtm?: string
     *   > array('reserve_dtm' => 'asc')  予約日昇順（１泊目で判断します。）
     *   > array('reserve_dtm' => 'desc') 予約日降順（１泊目で判断します。）
     *   values?: string
     *   > array('values'         => 'reserve')  未来の予約,  過去の予約, 取消しの予約の順
     *   上記のうちのひとつを設定します。
     *  },
     * } $aa_order 表示順序 array('カラム' => 'asc or desc')
     * @param array{
     *  page?: int, ページ
     *  size?: int, レコード数(1から) ページ数を指定した場合必須
     * } $aa_offsets
     *
     * @return array
     */
    public function getReserveRooms($aa_conditions = [], $aa_order = ['date_ymd' => 'desc'], $aa_offsets = [])
    {
        try {
            $a_reserves = $this->a_reserves;

            $hotel = new Hotel();
            $hotel_status = new HotelStatus();
            $partner = new Partner();

            // 部屋、日付単位から部屋単位に修正
             //初期化追記して問題ないか？？
            $buf_reserve_cd = '';
            $a_reserves_room = [];
            $a_buf = [];
            $a_reserve_status = null;
            $a_reserve_date_ymd = null;
            $a_cancel_status = null;
            $a_cancel_date_ymd = null;
            $a_noshow_status = null;
            $a_noshow_date_ymd = null;

            for ($n_cnt = 0; $n_cnt < count($a_reserves); $n_cnt++) {

                // 予約データを設定
                if ($a_reserves[$n_cnt]->reserve_cd != $buf_reserve_cd || $this->is_empty($buf_reserve_cd)) {
                    // 前の予約コードに対してチェックイン日チェックアウトの取得を行う
                    // 最後の予約コードに対しても同様の処理をしているのでそこも一緒に修正すること
                    if (!($this->is_empty($buf_reserve_cd))) {
                        // キャンセル状態の場合
                        if (($a_reserve_status ?? 0) == 0) {
                            if (($a_noshow_status ?? 0) == 0) {
                                $o_check_in_ymd  = new DateUtil($a_cancel_date_ymd[0]);
                                $o_check_out_ymd = new DateUtil($a_cancel_date_ymd[count($a_cancel_date_ymd) - 1]);
                                $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'cancel';
                            } else {
                                $o_check_in_ymd  = new DateUtil($a_noshow_date_ymd[0]);
                                $o_check_out_ymd = new DateUtil($a_noshow_date_ymd[count($a_noshow_date_ymd) - 1]);
                                $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'noshow';
                            }

                            $o_check_out_ymd->add('d', 1);
                            $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));

                            // 予約状態の場合
                        } elseif (($a_cancel_status ?? 0) == 0) {
                            $o_check_in_ymd = new DateUtil($a_reserve_date_ymd[0]);
                            $o_check_out_ymd = new DateUtil($a_reserve_date_ymd[count($a_reserve_date_ymd) - 1]);

                            $o_check_out_ymd->add('d', 1);
                            $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'reserve';
                            $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));

                            // 日程短縮状態の場合
                        } else {
                            $o_check_in_ymd = new DateUtil($a_reserve_date_ymd[0]);
                            $o_check_out_ymd = new DateUtil($a_reserve_date_ymd[count($a_reserve_date_ymd) - 1]);

                            $o_check_out_ymd->add('d', 1);
                            $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'short';
                            $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                            $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));
                        }
                        $a_reserve_status = null;
                        $a_reserve_date_ymd = null;
                        $a_cancel_status = null;
                        $a_cancel_date_ymd = null;
                        $a_noshow_status = null;
                        $a_noshow_date_ymd = null;
                    }

                    $a_reserves_room[] = $a_reserves[$n_cnt];
                }

                // 予約数を数えます。
                if ($a_reserves[$n_cnt]->reserve_status == 0) {
                    $a_reserve_date_ymd[] = $a_reserves[$n_cnt]->date_ymd;
                    $a_reserve_status++;

                    // 不泊数を数えます。
                } elseif ($a_reserves[$n_cnt]->reserve_status == 4) {
                    $a_noshow_date_ymd[] = $a_reserves[$n_cnt]->date_ymd;
                    $a_noshow_status++;

                    // キャンセル数を数えます。
                } elseif ($a_reserves[$n_cnt]->reserve_status != 0) {
                    $a_cancel_date_ymd[] = $a_reserves[$n_cnt]->date_ymd;
                    $a_cancel_status++;
                }

                // 最低販売料金を取得
                if (!isset($a_reserves_room[count($a_reserves_room) - 1]->sales_charge_min)) {
                    $a_reserves_room[count($a_reserves_room) - 1]->sales_charge_min = null;
                } //初期化追記、上記でいいか？（??使用不可）
                if ($a_reserves[$n_cnt]->sales_charge < $a_reserves_room[count($a_reserves_room) - 1]->sales_charge_min || $this->is_empty($a_reserves_room[count($a_reserves_room) - 1]->sales_charge_min)) {
                    $a_reserves_room[count($a_reserves_room) - 1]->sales_charge_min = $a_reserves[$n_cnt]->sales_charge;
                }

                // 料金を計算
                if (!isset($a_reserves_room[count($a_reserves_room) - 1]->total_charge)) {
                    $a_reserves_room[count($a_reserves_room) - 1]->total_charge = null;
                } //初期化追記、上記でいいか？（??使用不可）
                if (!isset($a_reserves_room[count($a_reserves_room) - 1]->total_tax_charge)) {
                    $a_reserves_room[count($a_reserves_room) - 1]->total_tax_charge = null;
                } //初期化追記、上記でいいか？（??使用不可）
                if ($a_reserves[$n_cnt]->reserve_status == 0) {
                    $a_reserves_room[count($a_reserves_room) - 1]->total_charge += $a_reserves[$n_cnt]->sales_charge;
                    $a_reserves_room[count($a_reserves_room) - 1]->total_tax_charge += $a_reserves[$n_cnt]->tax_charge;

                    unset($a_reserves_room[count($a_reserves_room) - 1]->before_sales_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->sales_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->cancel_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->usual_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->discount_type);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->tax_charge);
                } else {
                    $a_reserves_room[count($a_reserves_room) - 1]->total_charge += $a_reserves[$n_cnt]->cancel_charge;
                    $a_reserves_room[count($a_reserves_room) - 1]->total_tax_charge += 0;

                    unset($a_reserves_room[count($a_reserves_room) - 1]->before_sales_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->sales_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->cancel_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->usual_charge);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->discount_type);
                    unset($a_reserves_room[count($a_reserves_room) - 1]->tax_charge);
                }

                if ($a_reserves[$n_cnt]->weather_status) {
                    if (!isset($a_reserves_room[count($a_reserves_room) - 1]->total_weather_insurance_charge)) {
                        $a_reserves_room[count($a_reserves_room) - 1]->total_weather_insurance_charge = null;
                    } //初期化追記、上記でいいか？（??使用不可）
                    $a_reserves_room[count($a_reserves_room) - 1]->total_weather_insurance_charge += $a_reserves[$n_cnt]->weather_insurance_charge;

                    if (!isset($a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition)) {
                        $a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition = null;
                    } //初期化追記、上記でいいか？（??使用不可）
                    if ($this->is_empty($a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition)) {
                        $a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition = $a_reserves[$n_cnt]->weather_condition;
                    } elseif ($a_reserves[$n_cnt]->weather_condition == 1) {
                        $a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition = $a_reserves[$n_cnt]->weather_condition;
                    } elseif ($a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition == 2) {
                        $a_reserves_room[count($a_reserves_room) - 1]->result_weather_condition = $a_reserves[$n_cnt]->weather_condition;
                    }
                }

                $buf_reserve_cd = $a_reserves[$n_cnt]->reserve_cd;
            }


            // 最後の予約コードに対しての処理を行う
            if (!($this->is_empty($buf_reserve_cd))) {
                // キャンセル状態の場合
                if (($a_reserve_status ?? 0) == 0) {
                    if (($a_noshow_status ?? 0) == 0) {
                        $o_check_in_ymd  = new DateUtil($a_cancel_date_ymd[0]);
                        $o_check_out_ymd = new DateUtil($a_cancel_date_ymd[count($a_cancel_date_ymd) - 1]);
                        $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'cancel';
                    } else {
                        $o_check_in_ymd  = new DateUtil($a_noshow_date_ymd[0]);
                        $o_check_out_ymd = new DateUtil($a_noshow_date_ymd[count($a_noshow_date_ymd) - 1]);
                        $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'noshow';
                    }
                    $o_check_out_ymd->add('d', 1);
                    $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));

                    // 予約状態の場合
                } elseif (($a_cancel_status ?? 0) == 0) {
                    $o_check_in_ymd = new DateUtil($a_reserve_date_ymd[0]); //DateUtil→DateUtilでいいか？（以下数か所も同様）
                    $o_check_out_ymd = new DateUtil($a_reserve_date_ymd[count($a_reserve_date_ymd) - 1]);

                    $o_check_out_ymd->add('d', 1);
                    $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'reserve';
                    $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));

                    // 日程短縮状態の場合
                } else {
                    $o_check_in_ymd = new DateUtil($a_reserve_date_ymd[0]);
                    $o_check_out_ymd = new DateUtil($a_reserve_date_ymd[count($a_reserve_date_ymd) - 1]);

                    $o_check_out_ymd->add('d', 1);
                    $a_reserves_room[count($a_reserves_room) - 1]->reserve_status = 'short';
                    $a_reserves_room[count($a_reserves_room) - 1]->check_in_ymd   = $o_check_in_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->check_out_ymd  = $o_check_out_ymd->get();
                    $a_reserves_room[count($a_reserves_room) - 1]->stay = $o_check_in_ymd->diff('d', $o_check_out_ymd->to_format('Y-m-d'));
                }
            }

            // 絞り込む
            $o_date_buf1 = new DateUtil();
            $o_date_buf2 = new DateUtil();
            for ($n_cnt = 0; $n_cnt < count($a_reserves_room); $n_cnt++) {
                // チェックイン日を設定
                $b_last_check_in_after = true;

                $o_date_buf2->set($a_reserves_room[$n_cnt]->check_out_ymd);
                $o_date_buf2->add('d', -1);
                if (!$this->is_empty($aa_conditions['last_check_in']['after'] ?? null)) { //null追記でいいか？
                    $o_date_buf1->set($aa_conditions['last_check_in']['after']);
                    // チェックイン日が指定された日以降の場合エラー
                    if (!($o_date_buf1->get() <= $o_date_buf2->get())) {
                        $b_last_check_in_after = false;
                    }
                }

                $b_last_check_in_before = true;
                if (!$this->is_empty($aa_conditions['last_check_in']['before'] ?? null)) { //null追記でいいか？
                    $o_date_buf1->set($aa_conditions['last_check_in']['before']);
                    // チェックイン日が指定された日以前の場合エラー
                    if (!($o_date_buf2->get() <= $o_date_buf1->get())) {
                        $b_last_check_in_before = false;
                    }
                }

                // チェックイン日の範囲ないの場合はデータを格納
                if ($b_last_check_in_after and $b_last_check_in_before) {
                    $a_buf[] = $a_reserves_room[$n_cnt];
                }
            }

            $a_reserves_room = $a_buf;

            $o_sysdate = new DateUtil();

            // ソートキーを作成
            for ($n_cnt = 0; $n_cnt < count($a_reserves_room); $n_cnt++) {
                // ソートキー作成（宿泊日）
                if (!($this->is_empty($aa_order['date_ymd']))) {
                    $a_sort['value'][] = $a_reserves_room[$n_cnt]->date_ymd;
                    $a_sort['reserve_cd'][] = $a_reserves_room[$n_cnt]->reserve_cd;
                    if ($aa_order['date_ymd'] == 'desc') {
                        $n_sort  = SORT_DESC;
                    } else {
                        $n_sort  = SORT_ASC;
                    }

                    // ソートキーの作成（予約日）
                } elseif (!($this->is_empty($aa_order['reserve_dtm']))) {
                    $a_sort['value'][] = $a_reserves_room[$n_cnt]->reserve_dtm;
                    $a_sort['reserve_cd'][] = $a_reserves_room[$n_cnt]->reserve_cd;
                    if ($aa_order['reserve_dtm'] == 'desc') {
                        $n_sort  = SORT_DESC;
                    } else {
                        $n_sort  = SORT_ASC;
                    }

                    // ソートキーの作成（予約単位）
                } elseif (!($this->is_empty($aa_order['transaction_cd']))) {
                    $a_sort['value'][]      = $a_reserves_room[$n_cnt]->transaction_cd;
                    $a_sort['reserve_cd'][] = $a_reserves_room[$n_cnt]->reserve_cd;
                    if ($aa_order['transaction_cd'] == 'desc') {
                        $n_sort  = SORT_DESC;
                    } else {
                        $n_sort  = SORT_ASC;
                    }

                    // ソートキーの作成（予約済み、宿泊済み）
                } elseif (!($this->is_empty($aa_order['values']))) {
                    // 未来の予約順（未来の予約で現在日に近い順に並び替え）
                    if ($aa_order['values'] == 'reserve') {
                        $n_sort  = SORT_ASC;

                        // キャンセル済みの予約は優先順位が低い
                        if ($a_reserves_room[$n_cnt]->reserve_status == 'cancel' or $a_reserves_room[$n_cnt]->reserve_status == 'noshow') {
                            $n_first = 10000;

                            // 宿泊済みの場合は過去のチェックイン日が現在日時に近い順で未来の予約の場合はチェックイン日の遠い順
                            $n_second = 1000 - $o_sysdate->diff('d', $a_reserves_room[$n_cnt]->check_in_ymd);
                        } else {
                            $n_first = 0;

                            // 宿泊済みの場合は過去のチェックイン日が現在日時に近い順
                            if ($o_sysdate->diff('d', $a_reserves_room[$n_cnt]->check_out_ymd) <= 0) {
                                $n_second = 1000 - $o_sysdate->diff('d', $a_reserves_room[$n_cnt]->check_in_ymd);

                                // 未来の予約の場合はチェックイン日で昇順
                            } else {
                                $n_second = $o_sysdate->diff('d', $a_reserves_room[$n_cnt]->check_in_ymd);
                            }
                        }
                        $a_sort['value'][] = $n_first + $n_second;
                        $a_sort['reserve_cd'][] = $a_reserves_room[$n_cnt]->reserve_cd;
                    }
                }
            }

            // ソート
            if (!($this->is_empty($a_reserves_room))) {
                array_multisort(
                    $a_sort['value'],
                    $n_sort,
                    $a_sort['reserve_cd'],
                    SORT_ASC,
                    $a_reserves_room
                );
            }

            // 必要な情報のみ取得（１ページ分）
            if (($aa_offsets['page'] ?? 0) > 0) {
                $start = ($aa_offsets['size'] * $aa_offsets['page']) - $aa_offsets['size'];
                $end   = $aa_offsets['size'] * $aa_offsets['page'];

                for ($n_cnt = $start; $n_cnt < $end; $n_cnt++) {
                    if ($this->is_empty($a_reserves_room[$n_cnt])) {
                        $end = $n_cnt;
                        break;
                    }
                    $a_reserves_sort[$n_cnt] = $a_reserves_room[$n_cnt];
                    $a_reserves_sort[$n_cnt]->total_page  = ceil(count($a_reserves_room) / $aa_offsets['size']);
                    $a_reserves_sort[$n_cnt]->total_count = count($a_reserves_sort);
                }

                // 全件返す
            } else {
                $start = 0;
                $end   = count($a_reserves_room);
                $a_reserves_sort = $a_reserves_room;
            }

            $a_result = []; //初期化
            for ($n_cnt = $start; $n_cnt < $end; $n_cnt++) {
                $a_result[] = $a_reserves_sort[$n_cnt];
                $i = count($a_result) - 1;

                // 提携先を取得
                $a_result[$i]->partner = $partner->find(['partner_cd' => $a_reserves_sort[$n_cnt]->partner_cd]);

                // 会員情報を取得する場合
                if ($aa_conditions['include_member']) {
                    // 会員情報を取得
                    $a_result[$i]->member = $this->getMember($a_reserves_sort[$n_cnt]->partner_cd, $a_reserves_sort[$n_cnt]->member_cd, $a_result[$n_cnt]->auth_type);
                }

                // 宿泊体験存在確認
                $a_result[$i]->voice = $this->hasVoice(['reserve_cd' => $a_reserves_sort[$n_cnt]->reserve_cd]);

                // リザーブプランスペックの取得
                $a_result[$i]->reserve_plan_specs = $this->getReservePlanSpecs(['reserve_cd' => $a_reserves_sort[$n_cnt]->reserve_cd]);

                // 施設情報の取得
                $a_result[$i]->hotel = $hotel->find(['hotel_cd' => $a_reserves_sort[$n_cnt]->hotel_cd]);

                // 施設情報状態の取得
                $a_result[$i]->hotel_status = $hotel_status->find(['hotel_cd' => $a_reserves_sort[$n_cnt]->hotel_cd]);

                // 施設注意事項の取得
                $core_hotel = new Hotel();
                $core_hotel->setHotelCd($a_reserves_sort[$n_cnt]->hotel_cd);
                $a_result[$i]->hotel_inform_cancel = $core_hotel->getHotelInformCancel();

                if ($a_result[$i]->smoke == 1 && !preg_match('/^禁煙ルームを希望します。/', $a_result[$i]->guest_note)) {
                    $a_result[$i]->guest_note = '禁煙ルームを希望します。' . $a_result[$i]->guest_note;
                } elseif ($a_result[$i]->smoke == 2 && !preg_match('/^タバコが吸える部屋を希望します。/', $a_result[$i]->guest_note)) {
                    $a_result[$i]->guest_note = 'タバコが吸える部屋を希望します。' . $a_result[$i]->guest_note;
                }

                // アイコン設定
                // 金土日
                if ($a_reserves_sort[$n_cnt]->plan_type == 'fss') {
                    $a_icons['fss'] = true;
                } else {
                    $a_icons['fss'] = false;
                }

                // 連泊プラン
                if ($a_reserves_sort[$n_cnt]->stay_limit != 1) {
                    $a_icons['stay_limit'] = true;
                } else {
                    $a_icons['stay_limit'] = false;
                }
            }



            return [
                'values'     => $a_result,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * CSVヘッダー設定 ※モデルへの記述でいい？
     *
     * @return array
     */
    public function setCsvHeader()
    {
        $header = [
            "参照コード","予約コード","予約受付日"
            ,"宿泊日","部屋コード","部屋名称"
            ,"プランコード","プラン名称"
            ,"会員名","宿泊代表者"
            ,"登録割引料金","税別サ込","システム利用料","率(%)"
        ];

        return $header;
    }

    /**
     * CSVデータ設定 ※モデルへの記述でいい？
     *
     * @return array
     */
    public function setCsvData($reserve_data)
    {
        $data = [];

        foreach ($reserve_data['values'] as $reserve_data) {
            //初期化
            $string = [
                strip_tags($reserve_data->reserve_cd),
                strip_tags($reserve_data->partner_ref),
                strip_tags($reserve_data->reserve_dtm),
                strip_tags($reserve_data->date_ymd),
                strip_tags($reserve_data->room_cd),
                strip_tags($reserve_data->room_nm),
                strip_tags($reserve_data->plan_cd),
                strip_tags($reserve_data->plan_nm),
                strip_tags($reserve_data->member['full_nm'] ?? null), //null追記でいいか？
                strip_tags($reserve_data->guest_nm),
                strip_tags($reserve_data->sales_charge),
                strip_tags($reserve_data->sales_charge),
                strip_tags($reserve_data->bill['bill_charge']),
                strip_tags($reserve_data->bill['rate'])
            ];

            $data[] = $string;
        }

        return $data;
    }
}
