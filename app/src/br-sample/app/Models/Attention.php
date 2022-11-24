<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use App\Common\DateUtil;

/**
 * @注目文言マスタ
 */
class Attention extends CommonDBModel
{
	use Traits;

	protected $table = "top_attention";
	// カラム
	public string $COL_ATTENTION_ID = "attention_id";
	public string $COL_START_DATE = "start_date";
	public string $COL_DISPLAY_STATUS = "display_status";
	public string $COL_DISPLAY_FLAG = "display_flag";
	public string $COL_TITLE = "title";
	public string $COL_NOTE = "note";
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// 使わなそう？これ自体は残さないとエラーになる
	}

	//======================================================================
	// 販売日時：selectのoption  ※private->publicへ変更した
	//======================================================================
	public function makeYmdSelecter()
	{
		// 初期化
		$o_models_date = new DateUtil();
		// $n_start_year  = 2017;
		$n_now_year    = (int)$o_models_date->to_format('Y');
		$n_end_year    = $n_now_year + 2;
		$a_result      = array();

		// 選択可能項目の作成：年
		$a_result['year'] = array();
		for ($ii = $n_now_year; $ii <= $n_end_year; $ii++) {
			$a_result['year'][] = $ii;
		}

		// 選択可能項目の作成：月
		$a_result['month'] = array();
		for ($ii = 1; $ii <= 12; $ii++) {
			$a_result['month'][] = $ii;
		}

		// 選択可能項目の作成：日
		$a_result['day'] = array();
		for ($ii = 1; $ii <= 31; $ii++) {
			$a_result['day'][] = $ii;
		}

		return $a_result;

	}

	//======================================================================
	// 画面表示用配列の作成 ※private->publicへ変更した
	//======================================================================
	public function makeStartArray(){

		$initial_array = array();
		for ($a=0; $a <= 3; $a++) { 
			//null追記したが、null以外にならないのでは？？
			$initial_array[$a]['word'] = $a_attention_info[$a]['word']??null;
			$initial_array[$a]['url'] = $a_attention_info[$a]['url']??null;
			$initial_array[$a]['jwest_word'] = $a_attention_info[$a]['jwest_word']??null;
			$initial_array[$a]['jwest_url'] = $a_attention_info[$a]['jwest_url']??null;
		}
		
		return $initial_array;
	}


	//======================================================================
	// チェック ※private->publicへ変更した
	//======================================================================
	public function insertCheck($requestAttention){ //引き数追加

		//??null追記
		$attention_id = $requestAttention['attention_id']??null;
		$title = $requestAttention['title']??null;
		$start_date_year = $requestAttention['start_date_year']??null;
		$start_date_month = $requestAttention['start_date_month']??null;
		$start_date_day = $requestAttention['start_date_day']??null;
		$display_status = $requestAttention['display_status']??null;
		$word = $requestAttention['word']??null;
		$url = $requestAttention['url']??null;
		$jwest_word = $requestAttention['jwest_word']??null;
		$jwest_url = $requestAttention['jwest_url']??null;
		$note = $requestAttention['note']??null;

		//タイトルの入力チェック
		$a_error_message = array();
		if(empty($title)){
			$a_error_message['empty_title'] = 'タイトルを入力してください';
		}

		if(mb_strlen($title)>100){
			$a_error_message['count_title'] = 'タイトルは100文字以下にしてください。';
		}

		//期間の入力チェック
		$start_date = $start_date_year.'-'.$start_date_month.'-'.$start_date_day;
		$reg_date = strtotime($start_date);

		$now_date = strtotime(date('Y-m-d'));

		if (!checkdate($start_date_month, $start_date_day, $start_date_year)){
			$a_error_message['date_error'] = '入力されている日付が間違っています。';
		}
		if ($now_date > $reg_date){
			$a_error_message['date_error'] = '現在よりも過去の日付は選択できません。';
		}

		$s_sql =
<<<SQL
				select attention_id,
						start_date
				from top_attention
				where start_date = :start_date
				and   attention_id <> :attention_id
SQL;

			$check_date = array("start_date" => $start_date,
								"attention_id" => $attention_id);
			$a_check_date = DB::select($s_sql,$check_date);
			
		if (!empty($a_check_date)) {
				$a_error_message['date_error'] = $start_date_year.'年'.$start_date_month.'月'.$start_date_day.'日はすでに登録されています。';
		}

		//表示方法選択チェック
		if(!($display_status == 2 or $display_status == 4)){
			$a_error_message['irregular_status'] = '表示方法が無効です。';
		}

		//説明、URLの入力チェック
		for ($i=0; $i < $display_status ; $i++) { 
			$n = $i+1;

			//null追記
			if (empty($word[$i]??null)){
				$a_error_message['null_word_'.$i] = 'ベストリザーブ表示順位'.$n.'の説明が未入力です。';
			}
			if (mb_strlen($word[$i]??null)>100){
				$a_error_message['null_word_'.$i] = 'ベストリザーブ表示順位'.$n.'の説明を100文字以下に設定してください。';
			}
			if (empty($url[$i]??null)){
				$a_error_message['null_url_'.$i] = 'ベストリザーブ表示順位'.$n.'のURLが未入力です。';
			}
			if (mb_strlen($url[$i]??null)>2000){
				$a_error_message['null_url_'.$i] = 'ベストリザーブ表示順位'.$n.'のURLは2000文字以下に設定してください。';
			}
			if (empty($jwest_word[$i]??null)){
				$a_error_message['null_jwest_word_'.$i] = 'J-WEST表示順位'.$n.'の説明が未入力です。';
			}
			if (mb_strlen($jwest_word[$i]??null)>100){
				$a_error_message['null_jwest_word_'.$i] = 'J-WEST表示順位'.$n.'の説明を100文字以下に設定してください。';
			}
			if (empty($jwest_url[$i]??null)){
				$a_error_message['null_jwest_url_'.$i] = 'J-WEST表示順位'.$n.'のURLが未入力です。';
			}
			if (mb_strlen($jwest_url[$i]??null)>2000){
				$a_error_message['jwest_null_url_'.$i] = 'J-WEST表示順位'.$n.'のURLは2000文字以下に設定してください。';
			}
		}

		if(mb_strlen($note)>1000){
			$a_error_message['count_note'] = '備考は1000文字以下にしてください。';
		}

		if (!empty($a_error_message)) {
			foreach ($a_error_message as $message) {
				// $this->box->item->guide->add($message);//エラーメッセージ
				$errorList[] = $message;//エラーメッセージ
			}
			return $errorList;
		}

		return true;
			
	}



	

}
