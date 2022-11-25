<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\MastPref;

class BrInsuranceWeatherController extends _commonController
{

	use Traits;


	// 成立設定
	public function index()
	{
		// データを取得
		$requestInsuranceWeather = Request::all();

		// データを ビューにセット
		$this->addViewData("jbr_no", $requestInsuranceWeather['jbr_no'] ?? null); //null追記

		// ビューを表示
		return view("ctl.brinsuranceweather.index", $this->getViewData());
	}


	// 成立設定
	public function updateCondition() //修正途中
	{
		try {

			//データを取得
			$requestInsuranceWeather = Request::all();
			$jbr_no = $requestInsuranceWeather['jbr_no'] ?? null; //null追記;

			// 対象未設定
			// if (is_empty(trim($this->_a_request_params['jbr_no']))) {
			if ($this->is_empty(trim($jbr_no))) {
				$errors[] = "成立対象の値を設定してください。";
				$this->addErrorMessageArray($errors);

				$a_result = array(); //追記

				// データを ビューにセット
				$this->addViewData("jbr_no", $jbr_no);
				$this->addViewData('result', $a_result);
				return view("ctl.brinsuranceweather.updatecondition", $this->getViewData());
			}

			$a_rows = explode("\n", $jbr_no);

			for ($n_cnt = 0; $n_cnt < count($a_rows); $n_cnt++) {
				$a_clms = preg_split("/[\s]+/", str_replace(',', '', $a_rows[$n_cnt]));
				$a_clm = array();
				if (!$this->is_empty($a_clms[0])) {
					$a_clm['jbr_no'] = (int)substr($a_clms[0], 2);
				}
				if (!$this->is_empty($a_clms[1] ?? null)) { //??null追記
					$a_clm['valid_charge'] = $a_clms[1];
				}
				if (!$this->is_empty($a_clm)) {
					$a_result[] = $a_clm;
				}
			}

			// 状態更新

			for ($n_cnt = 0; $n_cnt < count($a_result); $n_cnt++) {
				// 作成結果の更新 //date_ymdはblade側でformatするように書き換え
				$s_sql =
					<<<SQL
					select	riw.reserve_cd,
							riw.date_ymd as date_ymd,
							riw.present_charge,
							riw.jbr_no,
							riw.condition,
							riw.status,
							riw.action_condition,
							r.reserve_status
					from	reserve_insurance_weather riw,
													reserve r
					where	riw.jbr_no = :jbr_no
											  and	riw.reserve_cd = r.reserve_cd
											  and	riw.date_ymd = r.date_ymd
SQL;

				$a_reserve = DB::select(
					$s_sql,
					array(
						'jbr_no'     => $a_result[$n_cnt]['jbr_no'],
					)
				);
				$a_reserve = json_decode(json_encode($a_reserve), true); //json~追記しないとviewでエラー

				$a_result[$n_cnt]['error'] = null; //追記

				if ($this->is_empty($a_reserve)) {
					$a_result[$n_cnt]['error'] = 'NotJbrNo';
					// $this->_s_error = $a_result[$n_cnt]['error'];
					$errors[] = "更新できない情報が含まれてます。下記エラー状況を確認の上正しい「お天気保証番号」 と 「保険金」 の指定をお願いいたします。";
				} elseif ($a_reserve[0]['present_charge'] != $a_result[$n_cnt]['valid_charge']) {
					$a_result[$n_cnt]['error'] = 'NotCharge';
					// $this->_s_error = $a_result[$n_cnt]['error'];
					$errors[] = "更新できない情報が含まれてます。下記エラー状況を確認の上正しい「お天気保証番号」 と 「保険金」 の指定をお願いいたします。";
				} elseif ($a_reserve[0]['status'] == 0) {
					$a_result[$n_cnt]['error'] = 'NotValid';
					// $this->_s_error = $a_result[$n_cnt]['error'];
					$errors[] = "更新できない情報が含まれてます。下記エラー状況を確認の上正しい「お天気保証番号」 と 「保険金」 の指定をお願いいたします。";
				} elseif ($a_reserve[0]['reserve_status'] <> 0) {
					$a_result[$n_cnt]['error'] = 'Canceled';
					// $this->_s_error = $a_result[$n_cnt]['error'];
					$errors[] = "更新できない情報が含まれてます。下記エラー状況を確認の上正しい「お天気保証番号」 と 「保険金」 の指定をお願いいたします。";
				}
				$a_result[$n_cnt]['reserve_cd']       = $a_reserve[0]['reserve_cd'] ?? null; //??null追記
				$a_result[$n_cnt]['date_ymd']         = $a_reserve[0]['date_ymd'] ?? null; //??null追記
				$a_result[$n_cnt]['present_charge']   = $a_reserve[0]['present_charge'] ?? null; //??null追記
				$a_result[$n_cnt]['status']           = $a_reserve[0]['status'] ?? null; //??null追記
				$a_result[$n_cnt]['condition']        = $a_reserve[0]['condition'] ?? null; //??null追記
				$a_result[$n_cnt]['action_condition'] = $a_reserve[0]['action_condition'] ?? null; //??null追記
				$a_result[$n_cnt]['reserve_status']   = $a_reserve[0]['reserve_status'] ?? null; //??null追記
			}


			// エラーの予約があったら
			if (!$this->is_empty($errors ?? null)) { //??null追記
				$this->addErrorMessageArray($errors);
				// データを ビューにセット
				$this->addViewData("jbr_no", $jbr_no);
				$this->addViewData('result', $a_result);
				return view("ctl.brinsuranceweather.updatecondition", $this->getViewData());
			}

			// 成立の更新
			$b_update = false;
			for ($n_cnt = 0; $n_cnt < count($a_result); $n_cnt++) {

				// 結果更新済みの場合は、スキップ
				if ($a_result[$n_cnt]['condition'] != 0) {
					continue;
				}
				$b_update = true;
				$a_result[$n_cnt]['action_condition'] = 10;

				//modify設定追記
				$MastPrefModel = new MastPref(); //下記の共通メソッドにアクセスしたいだけだから既存のモデル使用でいいか
				$MastPrefModel->setUpdateCommonColumn($requestInsuranceWeather);
				$modify_cd = $requestInsuranceWeather['modify_cd'];

				$s_sql =
					<<<SQL
					update	reserve_insurance_weather
						set	action_condition  = :action_condition,
							modify_cd         = :modify_cd,
							modify_ts         = now()
					where	jbr_no            = :jbr_no
						and	`condition`  = 0
						and	status     = 1
SQL;

				DB::update(
					$s_sql,
					array(
						'action_condition'  => $a_result[$n_cnt]['action_condition'],
						'modify_cd'         => $modify_cd,
						'jbr_no'            => $a_result[$n_cnt]['jbr_no'],
					)
				);
			}

			// 結果をビューにセット
			$this->addViewData('result', $a_result);

			if (!$b_update) {
				$errors[] = "すでに「成立」へ更新済みですので、更新いたしませんでした。";
				$this->addErrorMessageArray($errors);
				// データを ビューにセット
				$this->addViewData("jbr_no", $jbr_no);
				return view("ctl.brinsuranceweather.updatecondition", $this->getViewData());
			}


			// 不成立の更新 （このSQLは次のSQLにより上書きされるので動作しないが問題ない様子）
			$s_sql =
				<<<SQL
				update	reserve_insurance_weather
					set	action_condition = :action_condition,
						modify_cd        = :modify_cd,
						modify_ts        = now()
				where	trunc(date_ymd + 1, 'mm') = trunc(to_date(:date_ymd, 'yyyy-mm-dd') + 1, 'mm')
					and	`condition`  = 0
					and	action_condition is null
SQL;
			// 判定不用の更新
			$s_sql =
				<<<SQL
				update	reserve_insurance_weather
					set	action_condition = :action_condition,
						modify_cd        = :modify_cd,
						modify_ts        = now()
				where	status  = 0
					and	action_condition is null
SQL;

			DB::update(
				$s_sql,
				array(
					'action_condition' => 0,
					'modify_cd'        => $modify_cd,
					// 'date_ymd'         => $a_result[0]['date_ymd'], //上のSQLにしかないので、下で上書きされたときは不要（あるとエラー）
				)
			);

			// ガイドメッセージの設定
			$guides = "翌月1日に成立状態を「成立」に更新し、「成立メール」の送信をいたします。";
			$this->addGuideMessage($guides);
			// データを ビューにセット
			$this->addViewData("jbr_no", $jbr_no); //値あっている？
			$this->addViewData('result', $a_result);
			return view("ctl.brinsuranceweather.updatecondition", $this->getViewData());
		} catch (Exception $e) {
			throw $e;
		}
	}
}
