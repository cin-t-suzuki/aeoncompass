<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;
	//TODO require_once '_common/models/Core/Schedule.php';

	class ModelsSchedule 
	{

		private $box                 = null;  // ボックス

		// コンストラクタ
		//
		function __construct(){
			
		}

		/* スケジュールを取得します。
		*
		*  aa_conditions
		*    date_ym 取得する処理月 YYYY-MM
		*/
		public function get_schedules($aa_conditions = array())
		{
			//TODO $ym = $aa_conditions['date_ym']; // oracle に合わせるため、ymの文字書式で比較するよう変更
			$ym = $aa_conditions['date_ym'];

			$s_sql =
				<<<SQL
					select
						date_format(money_schedule.ym, '%Y-%m-%d') as ym, date_format(money_schedule.date_ymd, '%Y-%m-%d') AS date_ymd,
						/*CAST(str_to_date(date_format(cast(
							CONVERT_TZ(STR_TO_DATE(date_format(money_schedule.ym, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS'), 'Asia/Tokyo', 'UTC')
							as datetime), '%Y-%m-%d'), '%Y-%m-%d')
								- str_to_date('1970-01-01', '%Y-%m-%d') AS SIGNED) * 24 * 60 * 60 
									+ CAST(date_format(cast(
										CONVERT_TZ(STR_TO_DATE(date_format(money_schedule.ym, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS'), 'Asia/Tokyo', 'UTC')
																					as datetime), '%s%sS')AS SIGNED) as ym,
						CAST(str_to_date(date_format(cast(
							CONVERT_TZ(STR_TO_DATE(date_format(money_schedule.date_ymd, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS'), 'Asia/Tokyo', 'UTC')
															as datetime), '%Y-%m-%d'), '%Y-%m-%d')
								- str_to_date('1970-01-01', '%Y-%m-%d')AS SIGNED) * 24 * 60 * 60
									+ CAST(date_format(cast(
											CONVERT_TZ(STR_TO_DATE(date_format(money_schedule.date_ymd, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS'), 'Asia/Tokyo', 'UTC')
																							as datetime), '%s%sS')AS SIGNED) as date_ymd,*/
							q1.id as money_schedule_id,
							q1.schedule_nm
					from	money_schedule right outer join
						(
							select	id,
								schedule_nm
							from	mast_money_schedule
						) q1
					on	money_schedule.money_schedule_id = q1.id
						and	DATE_FORMAT(money_schedule.ym,'%Y-%m') = '{$ym}'
					order by money_schedule.date_ymd
				SQL;

				$data = DB::select($s_sql);

				if(!empty($data) && count($data) > 0){
					return $data;
				}

				return null;

		}

		/*TODO bk SQL
							select	CAST(str_to_date(date_format(cast(
						SYS_EXTRACT_UTC(to_timestamp(date_format(money_schedule.ym, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS'))	 --to_timestampが不要かも？
						as datetime), '%Y-%m-%d'), '%Y-%m-%d')
							- str_to_date('1970-01-01', '%Y-%m-%d') AS SIGNED) * 24 * 60 * 60 
								+ CAST(date_format(cast(
									SYS_EXTRACT_UTC(to_timestamp(date_format(money_schedule.ym, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS')) as datetime), '%s%sS')AS SIGNED) as ym,
					CAST(str_to_date(date_format(cast(
						SYS_EXTRACT_UTC(to_timestamp(date_format(money_schedule.date_ymd, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS')) as datetime), '%Y-%m-%d'), '%Y-%m-%d')
							- str_to_date('1970-01-01', '%Y-%m-%d')AS SIGNED) * 24 * 60 * 60
								+ CAST(date_format(cast(
										SYS_EXTRACT_UTC(to_timestamp(date_format(money_schedule.date_ymd, '%Y-%m-%d %H:%i:%s'), 'YYYY-MM-DD HH24:MI:SS')) as datetime), '%s%sS')AS SIGNED) as date_ymd,
						q1.id as money_schedule_id,
						q1.schedule_nm
				from	money_schedule right outer join
					(
						select	id,
							schedule_nm
						from	mast_money_schedule
					) q1
				on	money_schedule.money_schedule_id = q1.id
					and	money_schedule.ym = str_to_date( '%{$ym}%'  , '%Y-%m')
				order by money_schedule.date_ymd
			*/

/* TODO 使用箇所 未実装
		// 経理関係スケジュールの一覧を取得
		public function get_money_schedules($aa_conditions = array()){
			try {

				$a_conditions = array();

				$s_sql =
<<<SQL
					select	to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(money_schedule.ym, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(money_schedule.ym, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as ym,
							money_schedule.money_schedule_id,
							to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(money_schedule.date_ymd, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(money_schedule.date_ymd, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as date_ymd,
							q1.schedule_nm
					from	money_schedule,
						(
							select	id,
									schedule_nm
							from	mast_money_schedule
						) q1
					where	money_schedule_id = q1.id
						and	money_schedule.ym >= trunc(add_months(sysdate, -1), 'MM')
					order by money_schedule.ym, money_schedule.date_ymd
SQL;

				$_oracle = _Oracle::getInstance();

				return array(
							'values'     => $_oracle->find_by_sql($s_sql, $a_conditions),
							'reference'  => $this->set_reference('経理関係スケジュール一覧', __METHOD__)
							);

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}


		// 経理関係スケジュールマスタの一覧を取得
		public function get_mast_money_schedules($aa_conditions = array()){
			try {

				$a_conditions = array();

				$s_sql =
<<<SQL
					select	id,
							schedule_nm
					from	mast_money_schedule
					order by schedule_nm
SQL;

				$_oracle = _Oracle::getInstance();

				return array(
							'values'     => $_oracle->find_by_sql($s_sql, $a_conditions),
							'reference'  => $this->set_reference('経理関係スケジュールマスタ一覧', __METHOD__)
							);

			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
*/
	}

?>