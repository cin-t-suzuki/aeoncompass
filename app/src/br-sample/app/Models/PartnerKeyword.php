<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

/**
 * パートナーマスタ
 */
class PartnerKeyword extends CommonDBModel
{
	use Traits;

    protected $table = 'partner_keyword_example';
	

	// カラム
	public string $COL_PARTNER_CD = "partner_cd";
	public string $COL_LAYOUT_TYPE = "layout_type";
	public string $COL_BRANCH_NO = "branch_no";
	public string $COL_WORD = "word";
	public string $COL_VALUE = "value";
	public string $COL_ORDER_NO = "order_no";
	public string $COL_DISPLAY_STATUS = "display_status";
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colPartnerCd= (new ValidationColumn())->setColumnName($this->COL_PARTNER_CD, "提携先コード")->require()->length(0,10)->notHalfKana();
		$colLayoutType= (new ValidationColumn())->setColumnName($this->COL_LAYOUT_TYPE, "表示場所")->require()->length(0,9)->intOnly();
		$colBranchNo= (new ValidationColumn())->setColumnName($this->COL_BRANCH_NO, "枝番")->require()->length(0,2)->intOnly();
		$colWord= (new ValidationColumn())->setColumnName($this->COL_WORD, "表示文字列")->length(0,32)->notHalfKana();
		$colValue= (new ValidationColumn())->setColumnName($this->COL_VALUE, "値")->length(0,32)->notHalfKana();
		$colOrderNo= (new ValidationColumn())->setColumnName($this->COL_ORDER_NO, "表示順序")->length(0,10)->intOnly(); 
		$colDisplayStatus= (new ValidationColumn())->setColumnName($this->COL_DISPLAY_STATUS, "表示ステータス")->require();// TODO パターンチェック必要？カラムの説明
		parent::setColumnDataArray([$colPartnerCd,$colLayoutType,$colBranchNo,$colWord,$colValue,$colOrderNo,$colDisplayStatus]);
	}

	//======================================================================
	// 
	//======================================================================
	public function list($request_params)
	{
		// 検索用パラメータ設定（パートナー管理に戻った時用）
		$list = $this->_get_list($request_params);
		return $list;
	}

	//======================================================================
	// 一覧の取得
	//======================================================================
	private function _get_list($request_params)
	{
		// 初期化
		$s_sql        = '';
		$a_conditions = array();
		$a_result     = array();

		$s_sql =
<<< SQL
			select	word,
					branch_no,
					display_status,
					order_no,
					value
			from	partner_keyword_example
			where	partner_cd  = :partner_cd
				and	layout_type = 0
			order by	order_no
SQL;
		$a_conditions['partner_cd'] = $request_params['partner_cd'];
		$a_result = DB::select($s_sql, $a_conditions);

		foreach($a_result as $key => $value){
			$a_result[$key]->pre_branch_no = $a_result[$key-1]->branch_no??null;
			$a_result[$key]->pre_order_no = $a_result[$key-1]->order_no??null;
			$a_result[$key]->rear_branch_no = $a_result[$key+1]->branch_no??null;
			$a_result[$key]->rear_order_no = $a_result[$key+1]->order_no??null;
			//??null追記でいいか
		}

		return $a_result;
	}

	//======================================================================
	// 
	//======================================================================
	public function search_params($request_params)
	{
		// 検索用パラメータ設定（パートナー管理に戻った時用）
		$search_params = $this->_set_search_params($request_params);
		return $search_params;
	}
	
	//======================================================================
	// 検索用パラメータ設定（パートナー管理に戻った時用）
	//   ※リクエストから検索に使用するパラメータだけを抽出して保持する
	//======================================================================
	private function _set_search_params($request_params)
	{
		// 初期化
		$a_search_params = array();
		$s_search_params = '';
		
		// 提携先コード
		if ( !$this->is_empty($request_params['search_partner_cd']?? null) ) { //??null追加でいいか（下記同様）
			$a_search_params['search_partner_cd'] = $request_params['search_partner_cd'];
		}
		
		// 提携先名称
		if ( !$this->is_empty($request_params['search_partner_nm']?? null) ) {
			$a_search_params['search_partner_nm'] = $request_params['search_partner_nm'];
		}
		
		// 接続形態
		if ( !$this->is_empty($request_params['search_connect_cls']?? null) ) {
			$a_search_params['search_connect_cls'] = $request_params['search_connect_cls'];
		}
		
		// 接続形態詳細
		if ( !$this->is_empty($request_params['search_connect_type']?? null) ) {
			$a_search_params['search_connect_type'] = $request_params['search_connect_type'];
		}
		
		// 表示項目
		for ( $ii = 1; $ii <= 5; $ii++ ) {
			if ( !$this->is_empty($request_params['search_partner_disply_' . $ii]?? null) ) {
				$a_search_params['search_partner_disply_' . $ii] = $request_params['search_partner_disply_' . $ii];
			}
		}
		
		// 検索用パラメータの指定があればURI形式も作成
		foreach ( $a_search_params as $key => $value ) {
			$s_search_params .= '/' . $key . '/' . $value;
		}

		return $a_search_params; //TODO sの方渡せていない

	}

	//======================================================================
	// 
	//======================================================================
	public function display_status_selecter()
	{
		// 検索用パラメータ設定（パートナー管理に戻った時用）
		$display_status_selecter = $this->_get_display_status_selecter();
		return $display_status_selecter;
	}

	//======================================================================
	// 表示設定の選択項目を作成
	//======================================================================
	private function _get_display_status_selecter()
	{
		// 初期化
		$result    = array();
		$names = array();
		
		$names['1'] = '表示';
		$names['0'] = '非表示';
		
		foreach ( $names as $key => $value ) {
			// $a_result['values'][ $s_key ] = $s_key; //不要？
			$result['names'][ $key ]  = $value;
		}
		
		return $result;
	}

	//======================================================================
	// 
	//======================================================================
	public function count_record($request_params)
	{
		$display_status_selecter = $this->_get_count_record($request_params);
		return $display_status_selecter;
	}

	//======================================================================
	// Partner_Keyword_Exampleテーブルの提携先別レコード数
	//======================================================================
	private function _get_count_record($request_params)
	{
		try {
			// 初期化
			$s_sql        = '';
			$a_conditions = array();
			$a_result     = array();
			
			$s_sql =
<<< SQL
				select	count(*) as count_record
				from	partner_keyword_example
				where	partner_cd = :partner_cd
SQL;
			$a_conditions['partner_cd'] = $request_params['partner_cd'];
			$a_result = DB::select($s_sql, $a_conditions);
			
			return (int)$a_result[0]->count_record;
			
		} catch (Exception $e) {
			throw $e;
		}
	}

	
	/**  新規登録
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return 
	 */
	public function insert($con, $data){
		$result = $con->table($this->table)->insert($data);
		return  $result;
		
	}

	//パートナーCDとセクションIDでの提携先所属団体情報取得
	public function selectbyTriplekey($partner_keyword_example_work){
		$data = $this->where(array($this->COL_PARTNER_CD=>$partner_keyword_example_work['partner_cd'],$this->COL_LAYOUT_TYPE=>$partner_keyword_example_work['layout_type'],$this->COL_BRANCH_NO=>$partner_keyword_example_work['branch_no']))->get();

		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_PARTNER_CD => $data[0]->partner_cd,
				$this->COL_LAYOUT_TYPE => $data[0]->layout_type,
				$this->COL_BRANCH_NO => $data[0]->branch_no,
				$this->COL_WORD => $data[0]->word,
				$this->COL_VALUE => $data[0]->value,
				$this->COL_ORDER_NO => $data[0]->order_no,
				$this->COL_DISPLAY_STATUS => $data[0]->display_status,
				$this->COL_ENTRY_CD => $data[0]->entry_cd, 
				$this->COL_ENTRY_TS => $data[0]->entry_ts,
				$this->COL_MODIFY_CD => $data[0]->modify_cd, 
				$this->COL_MODIFY_TS => $data[0]->modify_ts,
			);
		}
		return [];
	
	}

	/**  複合主キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByTripleKey($con, $partnerKeywordData){
		$result = $con->table($this->table)->where(array($this->COL_PARTNER_CD=>$partnerKeywordData['partner_cd'],$this->COL_LAYOUT_TYPE=>$partnerKeywordData['layout_type'],$this->COL_BRANCH_NO=>$partnerKeywordData['branch_no']))->update($partnerKeywordData);
		return  $result;
	}


}

