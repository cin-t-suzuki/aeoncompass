<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use App\Models\PartnerKeyword;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Common\Traits;

	class BrpartnerKeywordController extends _commonController
	{
		use Traits;

		//TODO private,protected,public 書き換えで変わっているが大丈夫か、privateへのアクセスの仕方大丈夫か

		//======================================================================
		// インデックス
		//======================================================================
		public function index()
		{
			// データを取得
			//別アクションからのredirectの場合は渡されたデータを反映する
			if (session()->has('return_data')) {
				$requestPartnerKeyword = session()->pull('return_data');
				if (session()->has('errors')){
					//エラーメッセージの取得、セット
					$errorList = session()->pull('errors');
					$this->addErrorMessageArray($errorList);
				}
			} else {
				//それ以外（初期表示）
				$requestPartnerKeyword = Request::all();
			}

			// データを取得
			$partnerKeywordModel = new PartnerKeyword();
			$keyword_list = $partnerKeywordModel->list($requestPartnerKeyword);
			$search_params = $partnerKeywordModel->search_params($requestPartnerKeyword);

			// 提携先コードの取得(以下非表示は元ソース)			
			// ※社内からログインしたときはBoxに提携先コードが存在せず持ち回せない為
			// $this->_s_partner_cd = nvl($this->box->user->operator->partner_cd, $this->_a_request_params['partner_cd']);
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];

			// データを ビューにセット
			$this->addViewData("partner", $partner);
			$this->addViewData("form_params", $requestPartnerKeyword);
			$this->addViewData("keyword_list", $keyword_list);
			$this->addViewData("search_params", $search_params); 
			// ビューを表示
			return view("ctl.brpartnerKeyword.index", $this->getViewData());	
		}

	    //======================================================================
		// 表示順番入れ替え
		//======================================================================
		public function sort() 
		{
			// データを取得
			$requestPartnerKeyword = Request::all();
			$partnerKeywordModel = new PartnerKeyword();
			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];

			$requestPartnerKeyword['branch_no'];
			$result1 = $this->sort_list($requestPartnerKeyword['other_branch_no'] ,$requestPartnerKeyword['order_no'] );
			$result2 = $this->sort_list($requestPartnerKeyword['branch_no'] ,$requestPartnerKeyword['other_order_no'] );

			//データの再取得
			$partner_keyword_example_work['partner_cd']  = $requestPartnerKeyword['partner_cd'];
			$partner_keyword_example_work['layout_type'] = 0; // 提携先管理画面では「キーワード」で固定
			$partner_keyword_example_work['branch_no']   = $requestPartnerKeyword['branch_no'];
			$sorted_data                = $partnerKeywordModel->selectbyTriplekey($partner_keyword_example_work);

			if($result1 && $result2){
				// 正常に完了、一覧へ戻る
				session()->put('return_data',$sorted_data);//↑で再取得した更新済データを渡す
				return redirect()->route('ctl.brpartnerKeyword.index');
			} else {
				//エラー
				$errorList[] = "キーワードの並べ替えができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.index');
			}			
		}

		//======================================================================
		// 更新する並べ替え対象の取得
		//======================================================================
		private function sort_list($branch_no,$order_no) 
		{			
			// データを取得
			$requestPartnerKeyword = Request::all();
			$partnerKeywordModel = new PartnerKeyword();
			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];
		
			$partner_keyword_example_work['partner_cd']  = $partner['partner_cd'];
			$partner_keyword_example_work['layout_type'] = 0; // 提携先管理画面では「キーワード」で固定
			$partner_keyword_example_work['branch_no']   = $branch_no;
			$partner_keyword_example_find                = $partnerKeywordModel->selectbyTriplekey($partner_keyword_example_work);			
			
			// 更新するデータを作成（検索したデータをリクエストの値で書き替える）
			$partner_keyword_example_find['order_no']       = $order_no;

			// 画面入力を変換
			$errorList = $this->validatePartnerKeywordFromScreen($partnerKeywordData, $partner_keyword_example_find, $partnerKeywordModel);
			//work→findへ書き換え

			if( count($errorList) > 0 ){
				return false;
			}

			// 共通カラム値設定
			$partnerKeywordModel->setUpdateCommonColumn($partnerKeywordData);

			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerKeywordModel, $partnerKeywordData, &$dbCount) 
				{
					// DB更新
					$partnerKeywordModel->updateByTripleKey($con, $partnerKeywordData); 
				});
			}catch(Exception $e){
				$errorList[] = 'キーワードの更新ができませんでした。';
			}

			// 更新エラー
			if (count($errorList) > 0 || !empty($dbErr)){
				return false;
			}

			return true;

		}

		//======================================================================
		// 新規追加（入力）
		//======================================================================
		public function new()
		{
			// データを取得
			if (session()->has('return_data')) {
				//エラーで戻ってきた場合
				$requestPartnerKeyword = session()->pull('return_data');
				$errorList = session()->pull('errors');
				$this->addErrorMessageArray($errorList);
			} else {
				$requestPartnerKeyword = Request::all();
			}
			$partnerKeywordModel = new PartnerKeyword();
			$display_status_selecter = $partnerKeywordModel->display_status_selecter();
			$search_params = $partnerKeywordModel->search_params($requestPartnerKeyword);

			// 初期化（リクエスト値にdisplay_statusがなければ1を追加）
			$requestPartnerKeyword['display_status'] = ($requestPartnerKeyword['display_status'] ?? 1);

			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];

			// データを ビューにセット
			$this->addViewData("partner", $partner);
			$this->addViewData("form_params", $requestPartnerKeyword);
			$this->addViewData("display_status_selecter", $display_status_selecter);
			$this->addViewData("search_params", $search_params); 
			// ビューを表示
			return view("ctl.brpartnerKeyword.new", $this->getViewData());
		}

		//======================================================================
		// バリデーション
		//======================================================================
		private function validatePartnerKeywordFromScreen(&$partnerKeywordData,$request,$partnerKeywordModel){

			// 登録情報
			$partnerKeywordData = [];
			$partnerKeywordData[$partnerKeywordModel->COL_PARTNER_CD] = $request["partner_cd"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_LAYOUT_TYPE] = $request["layout_type"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_BRANCH_NO] = $request["branch_no"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_WORD] = $request["word"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_VALUE] = $request["value"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_ORDER_NO] = $request["order_no"]??null;
			$partnerKeywordData[$partnerKeywordModel->COL_DISPLAY_STATUS] = $request["display_status"]??null;
	
			// バリデーション
			$errorList = $partnerKeywordModel->validation($partnerKeywordData);
	
			return $errorList;
		}
		
		//======================================================================
		// 新規追加（登録）
		//======================================================================
		public function create()
		{
			$requestPartnerKeyword = Request::all();
			$partnerKeywordModel = new PartnerKeyword();
			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];
			
			// 初期化
			$a_partner_keyword_example_work = array();
			
			// 入力チェック
			if ( $this->is_empty($requestPartnerKeyword['word']) ) {
				$errorList[] = 'キーワードを入力してください。';
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.new');
			}

			// 登録するデータを作成
			$n_bo = $partnerKeywordModel->count_record($requestPartnerKeyword) + 1; // ※bo = branch_noとorder_noの略（同じ値を設定する為）

			$a_partner_keyword_example_work['partner_cd']     = $partner['partner_cd'];
			$a_partner_keyword_example_work['layout_type']    = 0; // 提携先管理画面では「キーワード」で固定
			$a_partner_keyword_example_work['branch_no']      = $n_bo;
			$a_partner_keyword_example_work['word']           = $requestPartnerKeyword['word'];
			$a_partner_keyword_example_work['value']          = $requestPartnerKeyword['value'];
			$a_partner_keyword_example_work['order_no']       = $n_bo;
			$a_partner_keyword_example_work['display_status'] = $requestPartnerKeyword['display_status'];

			// 画面入力を変換
			$errorList = $this->validatePartnerKeywordFromScreen($partnerKeywordData, $a_partner_keyword_example_work, $partnerKeywordModel);

			if( count($errorList) > 0){
				$errorList[] = "キーワードの登録ができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.new');			
			}

			// 共通カラム値設定
			$partnerKeywordModel->setInsertCommonColumn($partnerKeywordData);
			
			
			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerKeywordModel, $partnerKeywordData) 
				{
					// DB更新
					$partnerKeywordModel->insert($con, $partnerKeywordData); 
					//insertでいいか？
				});

			}catch(Exception $e){
				$errorList[] = 'キーワードの登録ができませんでした。';
			}
			
			// 更新エラー
			if (count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "キーワードの登録ができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.new');
			}

			// 正常に完了、一覧へ戻る
			session()->put('return_data',$partnerKeywordData);
			return redirect()->route('ctl.brpartnerKeyword.index');
			
			return true;
		}
		
		//======================================================================
		// 編集（入力）
		//======================================================================
		public function edit()
		{		
			// データを取得
			$requestPartnerKeyword = Request::all();
			$partnerKeywordModel = new PartnerKeyword();
			$display_status_selecter = $partnerKeywordModel->display_status_selecter();
			$search_params = $partnerKeywordModel->search_params($requestPartnerKeyword);
			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];

			// 初期化
			$partner_keyword_example_work = array();
			$partner_keyword_example_find = array();
			
			// 編集対象の取得（リクエストの3カラムからDBを検索）
			$partner_keyword_example_work['partner_cd']  = $partner['partner_cd'];
			$partner_keyword_example_work['layout_type'] = 0;
			$partner_keyword_example_work['branch_no']   = $requestPartnerKeyword['branch_no'];
			$partner_keyword_example_find                = $partnerKeywordModel->selectbyTriplekey($partner_keyword_example_work);
			
			// 画面表示内容の設定（↑で検索した結果を代入）　??nullの追加でいいか？
			if ( $this->is_empty($requestPartnerKeyword['is_update']??null) ) {
				$requestPartnerKeyword['word']           = $partner_keyword_example_find['word'];
				$requestPartnerKeyword['display_status'] = $partner_keyword_example_find['display_status'];
				$requestPartnerKeyword['value'] = $partner_keyword_example_find['value'];
			}

			// データを ビューにセット
			$this->addViewData("partner", $partner);
			$this->addViewData("form_params", $requestPartnerKeyword);
			$this->addViewData("display_status_selecter", $display_status_selecter);
			$this->addViewData("search_params", $search_params); 
			// ビューを表示
			return view("ctl.brpartnerKeyword.edit", $this->getViewData());
		}
		
		//======================================================================
		// 編集（更新）
		//======================================================================
		public function update()
		{
			$requestPartnerKeyword = Request::all();
			$partnerKeywordModel = new PartnerKeyword();
			// 提携先コードの取得(TODO indexを修正したらこちらも修正)
			$partner['partner_cd'] = $requestPartnerKeyword['partner_cd'];

			// 初期化
			$partner_keyword_example_work = array();
			
			// 編集対象の取得（リクエストの3カラムからDBを検索）
			$partner_keyword_example_work['partner_cd']  = $partner['partner_cd'];
			$partner_keyword_example_work['layout_type'] = 0;
			$partner_keyword_example_work['branch_no']   = $requestPartnerKeyword['branch_no'];
			$partner_keyword_example_find                = $partnerKeywordModel->selectbyTriplekey($partner_keyword_example_work);
			
			// 更新するデータを作成（検索したデータをリクエストの値で書き替える）
			$partner_keyword_example_find['word']           = $requestPartnerKeyword['word'];
			$partner_keyword_example_find['value']           = $requestPartnerKeyword['value'];
			$partner_keyword_example_find['display_status'] = $requestPartnerKeyword['display_status'];


			// 画面入力を変換
			$errorList = $this->validatePartnerKeywordFromScreen($partnerKeywordData, $partner_keyword_example_find, $partnerKeywordModel);

			if( count($errorList) > 0 ){
				$errorList[] = "キーワードの並べ替えができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.index');			
			}

			// 共通カラム値設定
			$partnerKeywordModel->setUpdateCommonColumn($partnerKeywordData);

			// 更新件数
			$dbCount = 0;
			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerKeywordModel, $partnerKeywordData, &$dbCount) 
				{
					// DB更新
					$dbCount = $partnerKeywordModel->updateByTripleKey($con, $partnerKeywordData); 
					//TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
				});

			}catch(Exception $e){
				$errorList[] = 'キーワードの更新ができませんでした。';
			}

			// 更新エラー
			if ($dbCount == 0 || count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "キーワードの更新ができませんでした。";
				session()->put('errors', $errorList);
				session()->put('return_data',$requestPartnerKeyword);
				return redirect()->route('ctl.brpartnerKeyword.index');		
			}

			// 正常に完了、 一覧へ戻る
			session()->put('return_data',$partnerKeywordData);
			return redirect()->route('ctl.brpartnerKeyword.index');

		}
	}
?>