<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use App\Models\FaxPr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Exception;

	class BrfaxPrController extends _commonController
	{

		/** 予約通知FAX掲載広告文章 編集画面 表示
		 * 
		 * @return 画面
		 */
		public function edit()
		{
			// faxデータ取得
			$this->setFaxPrDbData();

			// ビューを表示
			return view("ctl.brfaxPr.edit", $this->getViewData());
		}

		/** faxデータをDBから取得
		 *
		 * @return void
		 */
		private function setFaxPrDbData()
		{
			$faxPr = new FaxPr();

			$faxPrData = $faxPr->selectByKey();

			$this->addViewData("faxPr", $faxPrData);
		}

		/** 予約通知FAX掲載広告文章 更新処理
		 *
		 * @return 画面
		 */
		public function update()
		{
			//画面の値を取得
			$request = Request::all();
			$faxPr = new FaxPr();
			$errorList = [];

			if(!isset($request["title"])){
				$this->addErrorMessageArray("登録パラメータが存在しません");
				$this->setFaxPrDbData();
				return view("ctl.brfaxPr.edit", $this->getViewData());
			}else{
				$faxPrData[$faxPr->COL_FAX_PR_ID] = $request["fax_pr_id"];
				$faxPrData[$faxPr->COL_TITLE] = $request["title"];
				$faxPrData[$faxPr->COL_NOTE] = $request["note"];
			}

			if( count($errorList) == 0){
				//バリデーションチェック
				$errorList = $faxPr->validation($faxPrData);
			}

			if( count($errorList) == 0){
				//共通項目設定
				$faxPr->setUpdateCommonColumn($faxPrData, 'BrfaxPr/update.');
				try{
					$con = DB::connection('mysql');
					$dbErr = $con->transaction(function() use($con, $faxPr, $faxPrData) 
					{
						//更新
						$faxPr->updateByKey($con, $faxPrData);
					});

				}catch(Exception $e){
					$errorList[] = 'ご希望の予約通知FAX掲載広告文章データを更新できませんでした。';
				}
				// 更新エラーなし
				if (empty($dbErr)){
						// 正常処理
						$guideMsg="予約通知FAX掲載広告文章の更新が完了しました。";
						$this->addGuideMessage($guideMsg);
						// ビューを表示
						return view("ctl.brfaxPr.show", $this->getViewData());	
				}else{
					$errorList[] = $dbErr;
				}

			}

			$this->addErrorMessageArray($errorList);

			$this->addViewData("faxPr", $faxPrData);

			return view("ctl.brfaxPr.edit", $this->getViewData());	
		
		}

	}
?>