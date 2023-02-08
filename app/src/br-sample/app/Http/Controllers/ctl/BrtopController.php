<?php
	namespace App\Http\Controllers\ctl;

	use App\Common\DateUtil;
	use App\Http\Controllers\ctl\_commonController;

  use App\Models\ModelsSchedule;
  use App\Models\ModelsLicense;

	use App\Common\Traits;

	class BrtopController extends _commonController
	{
		use Traits;

		// アクションを呼び出す際、毎回ログインチェックを行う
		public function preDispatch()
		{
			try{
				// アクションを呼び出す際、毎回処理を行う。
				parent::brDispatch();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// インデックス
		public function index()
		{
			// ライセンス、 スケジュール モデルの取得
			$modelsSchedule = new ModelsSchedule();
			$modelsLicense  = new ModelsLicense();

			// 経理関係スケジュールの一覧を取得
			$o_date = new DateUtil();
			//当月
			$a_schedules['this_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));
			//前月
			$o_date->add('m', -1);
			$a_schedules['pre_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));
			//翌月
			$o_date->add('m', 2);
			$a_schedules['next_month'] = $modelsSchedule->get_schedules(array('date_ym' => $o_date->to_format('Y-m')));
			
			// 自身に許可されているライセンストークン取得
			//TODO ユーザー情報から取得し移送
			$operator_cd='1';
			$a_license_tokens = $modelsLicense->get_applicant_license($operator_cd);

			// ビュー情報を設定
			$this->addViewData("Schedules", $a_schedules);
			$this->addViewData("licenses", $a_license_tokens);
			
			// ビューを表示
			return view("ctl.brtop.index", $this->getViewData());

		}

		// 確認
		public function confirmation()
		{
			return view("ctl.brtop.confirmation", $this->getViewData());

		}

		// 登録
		public function registration()
		{
			try {

				return view("ctl.brtop.registration");

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// 提供
		public function offerAction()
		{
			try {

				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// 支払
		public function payment()
		{
			try {

				return view("ctl.brtop.payment");

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}


		// 仕入
		public function stockAction()
		{
			try {

				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// 請求
		public function claimAction()
		{
			try {

				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// 会員情報閲覧
		public function inspect()
		{
			try {

				return view("ctl.brtop.inspect");

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

	}
?>