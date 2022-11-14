<?php
namespace App\Http\Controllers\ctl;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use App\Models\PartnerSection;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Common\Traits;

	class BrpartnerSectionController extends _commonController
	{
		use Traits;

		//TODO 再読み込みを行った際の制御（再度前のアクションが実行されてエラーになる）

		//======================================================================
		// インデックス
		//======================================================================
		public function index()
		{	
			// データを取得
			//別アクションからのredirectの場合は渡されたデータを反映する
			if (session()->has('return_data')) {
					$request_params = session()->pull('return_data');
			} else {
				//それ以外（初期表示）
				$request_params = Request::all();
			}
	
			$partnerSectionModel = new PartnerSection();
			$section_list = $partnerSectionModel->get_section_list($request_params); //引数追加
			$search_params = $partnerSectionModel->search_params($request_params);
				
			// データを ビューにセット
			$this->addViewData("form_params", $request_params);
			$this->addViewData("section_list", $section_list);
			$this->addViewData("search_params", $search_params); 
			// ビューを表示
			return view("ctl.brpartnersection.index", $this->getViewData());			
		}

		//======================================================================
		// 新規追加 - 入力
		//======================================================================
		public function new()
		{	
			// データを取得
			$_a_request_params = Request::all();
			$partnerSectionModel = new PartnerSection();
			$search_params = $partnerSectionModel->search_params($_a_request_params);

			// データを ビューにセット
			$this->addViewData("form_params", $_a_request_params);
			$this->addViewData("search_params", $search_params); 
			// ビューを表示
			return view("ctl.brpartnersection.new", $this->getViewData());

		}

		//======================================================================
		// バリデーション
		//======================================================================
		private function validatePartnerSectionFromScreen(&$partnerSectionData,$request,$partnerSectionModel){

			// 登録情報
			$partnerSectionData = [];
			$partnerSectionData[$partnerSectionModel->COL_PARTNER_CD] = $request["partner_cd"]??null;
			$partnerSectionData[$partnerSectionModel->COL_SECTION_ID] = $request["section_id"]??null;
			$partnerSectionData[$partnerSectionModel->COL_SECTION_NM] = $request["section_nm"]??null;
			$partnerSectionData[$partnerSectionModel->COL_ORDER_NO] = $request["order_no"]??null;
	
			// バリデーション
			$errorList = $partnerSectionModel->validation($partnerSectionData);
	
			return $errorList;
		}
		//======================================================================
		// 新規追加 - 登録
		//======================================================================
		public function create()
		{
							
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();
			$search_params = $partnerSectionModel->search_params($requestPartnerSection);//追記

			//画面にないデータを作成
			$requestPartnerSection['section_id'] = $partnerSectionModel->get_section_id_next($requestPartnerSection);
			$requestPartnerSection['order_no'] = $partnerSectionModel->get_order_no_next($requestPartnerSection);


			// 画面入力を変換
			$errorList = $this->validatePartnerSectionFromScreen($partnerSectionData, $requestPartnerSection, $partnerSectionModel);

			if( count($errorList) > 0){
				$errorList[] = "所属団体情報の更新ができませんでした。";

				//書き換え後
				$this->addViewData("form_params", $requestPartnerSection); 
				$this->addViewData("search_params", $search_params); //追記
				return view("ctl.brpartnersection.new", $this->getViewData())
					->with(['errors'=>$errorList]);
					
			}

			// 共通カラム値設定
			$partnerSectionModel->setInsertCommonColumn($partnerSectionData);

			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerSectionModel, $partnerSectionData) 
				{
					// DB更新
					$partnerSectionModel->insert($con, $partnerSectionData); 
					//insertでいいか？
				});

			}catch(Exception $e){
				$errorList[] = '所属団体情報の更新ができませんでした。';
			}
			

			// 更新エラー
			if (count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "所属団体情報の更新ができませんでした。";
				$this->addViewData("form_params", $requestPartnerSection); 
				$this->addViewData("search_params", $search_params); //追記
				return view("ctl.brpartnersection.new", $this->getViewData())
					->with(['errors'=>$errorList]);
			}

			// 正常に完了、一覧へ戻る
			// 更新後の結果表示
			session()->put('return_data',$partnerSectionData);
			return redirect()->route('ctl.brpartnersection.index');

		}

		//======================================================================
		// 編集
		//======================================================================
		public function edit()
		{
			//リスエストの取得　あってる？
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();
			$search_params = $partnerSectionModel->search_params($requestPartnerSection);//追記

			// 初期化
			$partner_section_work = array();
			$partner_section_find = array();
			
			// 編集対象の取得
			$partner_section_work['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_work['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectbyWkey($partner_section_work);
			
			// 編集内容の設定
			$requestPartnerSection['section_nm'] = ($requestPartnerSection['section_nm'] ?? $partner_section_find['section_nm']);
			
			// データを ビューにセット
			$this->addViewData("form_params", $requestPartnerSection); //値合っている？
			$this->addViewData("search_params", $search_params); //追記
			
			// ビューを表示
			return view("ctl.brpartnersection.edit", $this->getViewData());
		}

		//======================================================================
		// 更新
		//======================================================================
		
		public function update(){

			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();
			$search_params = $partnerSectionModel->search_params($requestPartnerSection);//追記

			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			
			// 編集対象の取得
			$partner_section_work['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_work['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_work);

			// 更新するデータを作成
			$partner_section_find['section_nm']  = $requestPartnerSection['section_nm'];
			
			// 画面入力を変換
			$errorList = $this->validatePartnerSectionFromScreen($partnerSectionData, $partner_section_find, $partnerSectionModel);

			if( count($errorList) > 0){
				$errorList[] = "所属団体情報の更新ができませんでした。";

				//書き換え後
				$this->addViewData("form_params", $requestPartnerSection); 
				$this->addViewData("search_params", $search_params); //追記
				return view("ctl.brpartnersection.edit", $this->getViewData())
					->with(['errors'=>$errorList]);
					
			}

			// 共通カラム値設定
			$partnerSectionModel->setUpdateCommonColumn($partnerSectionData);

			// 更新件数
			$dbCount = 0;
			// コネクション
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerSectionModel, $partnerSectionData, &$dbCount) 
				{
					// DB更新
					$dbCount = $partnerSectionModel->updateByWKey($con, $partnerSectionData); 
					//TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
				});

			}catch(Exception $e){
				$errorList[] = '所属団体情報の更新ができませんでした。';
			}
			
			// 更新エラー
			if ($dbCount == 0 || count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "所属団体情報の更新ができませんでした。";
				$this->addViewData("form_params", $requestPartnerSection); 
				$this->addViewData("search_params", $search_params); //追記
				return view("ctl.brpartnersection.edit", $this->getViewData())
					->with(['errors'=>$errorList]);
			}

			// 正常に完了、一覧へ戻る
			session()->put('return_data',$partnerSectionData);
			return redirect()->route('ctl.brpartnersection.index');

		}

		//======================================================================
		// 削除
		//======================================================================
		protected function delete()
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();
			//検索用パラメータの取得(indexに戻る用)
			$search_params = $partnerSectionModel->search_params($requestPartnerSection);//追記

			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			$a_conditions           = array();
			$s_sql                  = '';
			$errorList = []; 
			
			// 編集対象の取得
			$partner_section_work['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_work['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_work);
			
			// 削除情報の設定
			$partner_section_work['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_work['section_id'] = $requestPartnerSection['section_id'];
	
			// 削除
			try{
				$con = DB::connection('mysql');
				$dbErr = $con->transaction(function() use($con, $partnerSectionModel, $partner_section_work)
				{
					$partnerSectionModel->deleteByKey($con, $partner_section_work);
				});
			}catch(Exception $e){
				$errorList[] = "所属団体名削除処理でエラーが発生しました。";
			}
			// 更新エラー
			if (count($errorList) > 0 || !empty($dbErr)){
				$errorList[] = "所属団体情報の更新ができませんでした。";
				$this->addViewData("form_params", $requestPartnerSection); //入力値を戻す
				$this->addViewData("search_params", $search_params); //検索用パラメータ
				$section_list = $partnerSectionModel->get_section_list($requestPartnerSection); //所属団体一覧の取得(indexに戻る用)
				$this->addViewData("section_list", $section_list); //所属団体一覧
				return view("ctl.brpartnersection.index", $this->getViewData())
					->with(['errors'=>$errorList]);//これであっている？共通化できない？
			}
			
			$s_sql =
<<< SQL
				select	partner_cd,
						section_id,
						order_no - 1 as new_order_no
				from	partner_section
				where	partner_cd = :partner_cd
					and	order_no > :order_no
				order by	order_no
SQL;
			$a_conditions['partner_cd'] = $partner_section_find['partner_cd'];
			$a_conditions['order_no']   = $partner_section_find['order_no'];
			// $this->_a_update_order_list = $this->_o_oracle->find_by_sql($s_sql, $a_conditions);
			$update_order_list = DB::select($s_sql, $a_conditions);

			//表示順の調整
			$order_result = $this->_update_order_no_section($update_order_list);
			if(!$order_result){
				$errorList[] = "所属団体情報の更新ができませんでした。";
				$this->addViewData("form_params", $requestPartnerSection); //入力値を戻す
				$this->addViewData("search_params", $search_params); //検索用パラメータ
				$section_list = $partnerSectionModel->get_section_list($requestPartnerSection); //所属団体一覧の取得(indexに戻る用)
				$this->addViewData("section_list", $section_list); //所属団体一覧
				return view("ctl.brpartnersection.index", $this->getViewData())
					->with(['errors'=>$errorList]);//これであっている？共通化できない？
			}

			// 正常に完了
			// 一覧へ戻る
			session()->put('return_data',$requestPartnerSection);//渡す値あってる？
			return redirect()->route('ctl.brpartnersection.index');

		}


		///////////////////////////////////////////////////////	
		// 表示順調整（削除時）
		// ※調整対象のプライマリキーと更新後の表示順を取得する
		//////////////////////////////////////////////////////
		private function _update_order_no_section($update_order_list)
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();

				// 更新対象が存在しなければ、何もしない
			if ( $this->is_empty($update_order_list) ) {
				return true;
			} else {
				// 表示順を更新
				// 入替元と入替先の表示順を更新
				foreach ( $update_order_list as $a_row ) {
					// 更新対象の情報取得
					$partner_section_temp               = array();
					$partner_section_temp['partner_cd'] = $a_row->partner_cd;
					$partner_section_temp['section_id'] = $a_row->section_id;
					$partner_section_find               = $partnerSectionModel->selectbyWkey($partner_section_temp);
					
					// 更新するデータを作成
					$partner_section_find['order_no']  = $a_row->new_order_no;

					// 画面入力を変換 （TODO 入力でも使うはず）
					$errorList = $this->validatePartnerSectionFromScreen($partnerSectionData, $partner_section_find, $partnerSectionModel);

					if( count($errorList) > 0){
						return false;
					}

					// 共通カラム値設定
					$partnerSectionModel->setUpdateCommonColumn($partnerSectionData);

					// コネクション
					try{
						$con = DB::connection('mysql');
						$dbErr = $con->transaction(function() use($con, $partnerSectionModel, $partnerSectionData) 
						{
							// DB更新
							$partnerSectionModel->updateByWKey($con, $partnerSectionData); 
						});

					}catch(Exception $e){
						$errorList[] = '所属団体情報の更新ができませんでした。';
					}
				}

				// 更新エラー
				if (count($errorList) > 0 || !empty($dbErr)){
					return false;
				}

				return true;
			}
			
		}
		//////////////////////////////////////////////////////



		//======================================================================
		// 順序更新（１つ上へ）
		//======================================================================
		public function up()
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();
		
			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			$a_conditions           = array();
			$s_sql                  = '';
			
			// 更新対象の取得
			$partner_section_temp['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_temp['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_temp);
			
			
			// 更新対象を取得
			// ※入替元と入替先のプライマリキーと更新後の表示順を取得する
			$s_sql =
<<< SQL
				select	partner_cd,
						section_id,
						if (order_no = :order_no, order_no - 1, order_no + 1 ) as new_order_no
				from	partner_section
				where	partner_cd = :partner_cd
					and	(
							order_no = :order_no2
								or  
							order_no = :order_no3 - 1
						)
				order by	order_no
SQL;
			$a_conditions['partner_cd'] = $partner_section_find['partner_cd'];
			$a_conditions['order_no']   = $partner_section_find['order_no'];
			$a_conditions['order_no2']   = $partner_section_find['order_no'];//sql文内で同じパラメータを２回使うとエラーが出るため
			$a_conditions['order_no3']   = $partner_section_find['order_no'];//上記同様(これでいいのか？)
			$update_order_list = DB::select($s_sql, $a_conditions);
			
			//表示順の調整
			$this->_update_order_no_section($update_order_list);
			
			// 正常に完了
			// 一覧へ戻る
			session()->put('return_data',$requestPartnerSection);//渡す値あってる？
			return redirect()->route('ctl.brpartnersection.index');
		}
		
		//======================================================================
		// 順序更新（１つ下へ）
		//======================================================================
		public function down()
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();

			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			$a_conditions           = array();
			$s_sql = '';

			// 更新対象の取得
			$partner_section_temp['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_temp['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_temp);

			// 更新対象を取得
			// ※入替元と入替先のプライマリキーと更新後の表示順を取得する
			$s_sql =
<<< SQL
	select	partner_cd,
			section_id,
			if (order_no = :order_no, order_no + 1, order_no - 1 ) as new_order_no
	from	partner_section
	where	partner_cd = :partner_cd
		and	(
				order_no = :order_no2
					or  
				order_no = :order_no3 + 1
			)
	order by	order_no
SQL;

			$a_conditions['partner_cd'] = $partner_section_find['partner_cd'];
			$a_conditions['order_no']   = $partner_section_find['order_no'];
			$a_conditions['order_no2']   = $partner_section_find['order_no'];//sql文内で同じパラメータを２回使うとエラーが出るため
			$a_conditions['order_no3']   = $partner_section_find['order_no'];//上記同様(これでいいのか？)
			$update_order_list = DB::select($s_sql, $a_conditions);

			//表示順の調整
			$this->_update_order_no_section($update_order_list);
			
			// 正常に完了
			// 一覧へ戻る
			session()->put('return_data',$requestPartnerSection);//渡す値あってる？
			return redirect()->route('ctl.brpartnersection.index');
		}
		
		//======================================================================
		// 順序更新（先頭へ）
		//======================================================================
		public function head()
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();

			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			$a_conditions           = array();
			$s_sql                  = '';
			
			// 更新対象の取得
			$partner_section_temp['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_temp['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_temp);
			
			// 更新対象を取得
			// ※入替元と入替先のプライマリキーと更新後の表示順を取得する
			$s_sql =
<<< SQL
				select	partner_cd,
						section_id,
						if (section_id = :section_id, 1, order_no + 1 ) as new_order_no
				from	partner_section
				where	partner_cd = :partner_cd
				order by	order_no
SQL;

			$a_conditions['partner_cd'] = $partner_section_find['partner_cd'];
			$a_conditions['section_id']   = $partner_section_find['section_id'];
			$update_order_list = DB::select($s_sql, $a_conditions);
			
			//表示順の調整
			$this->_update_order_no_section($update_order_list);
			
			// 正常に完了
			// 一覧へ戻る
			session()->put('return_data',$requestPartnerSection);//渡す値あってる？
			return redirect()->route('ctl.brpartnersection.index');
		}
		
		//======================================================================
		// 順序更新（末尾へ）
		//======================================================================
		public function tail()
		{
			$requestPartnerSection = Request::all(); 
			$partnerSectionModel = new PartnerSection();

			// 初期化
			$partner_section_work = array();
			$partner_section_temp = array();
			$partner_section_find = array();
			$a_conditions           = array();
			$s_sql                  = '';
			
			// 更新対象の取得
			$partner_section_temp['partner_cd'] = $requestPartnerSection['partner_cd'];
			$partner_section_temp['section_id'] = $requestPartnerSection['section_id'];
			$partner_section_find               = $partnerSectionModel->selectByWKey($partner_section_temp);
			
			// 更新対象を取得
			// ※入替元と入替先のプライマリキーと更新後の表示順を取得する
			$s_sql =
<<< SQL
				select	partner_cd,
						section_id,
						if (section_id = :section_id, ps_order.max_order_no, order_no - 1 ) as new_order_no
				from	partner_section,
						(
							select	max(ps.order_no) as max_order_no
							from	partner_section ps
							where	ps.partner_cd = :partner_cd
						) ps_order
				where	partner_cd  = :partner_cd2
					and	order_no   >= :order_no
				order by	order_no
SQL;

			$a_conditions['partner_cd'] = $partner_section_find['partner_cd'];
			$a_conditions['partner_cd2'] = $partner_section_find['partner_cd'];//sql文内で同じパラメータを２回使うとエラーが出るため
			$a_conditions['section_id']   = $partner_section_find['section_id'];
			$a_conditions['order_no']   = $partner_section_find['order_no'];
			$update_order_list = DB::select($s_sql, $a_conditions);
			
			//表示順の調整
			$this->_update_order_no_section($update_order_list);
			
			// 正常に完了
			// 一覧へ戻る
			session()->put('return_data',$requestPartnerSection);//渡す値あってる？
			return redirect()->route('ctl.brpartnersection.index');
		}
	}
?>