<?php

namespace App\Models\common;
use Illuminate\Database\Eloquent\Model;
use App\Common\DateUtil;

/**
 * DBモデルの共通クラス
 * 
 * バリデーション処理を記述
 */
abstract class CommonDBModel extends Model
{
	/**
	 * カラム情報
	 *   ValidColumn[]
	 */
	private $colmunArray = [];


	/**
	 * カラム情報の設定
	 */
	public function setColumnData($data){
		$this->colmunArray[$data->getColumnId()] = $data;
	}
	public function setColumnDataArray($dataArr){
		for($i=0; $i<count($dataArr); $i++){
			$this->setColumnData($dataArr[$i]);
		}
	}

	/**
	 * 登録値の検証
	 *   $values[カラム名] = 値
	 */
	public function validation($values){
		$rtnErrors = [];

		// 対象がなければ処理しない
		if(empty($values)){
			return $rtnErrors;
		}

		// カラム情報がなければエラー
		if(empty($this->colmunArray) || count($this->colmunArray) == 0){
			$rtnErrors[] = "カラム情報が定義されておりません";
			return $rtnErrors;
		}

		// バリデーション
		foreach($values as $key => $val){
			// テーブルカラムに存在しているか？
			if(!array_key_exists($key, $this->colmunArray)){
				$rtnErrors[] = $key . "が定義されておりません";
				continue;
			}

			// 必須入力チェック
			if ($this->colmunArray[$key]->isRequire()){
				if (is_null($val)) {
					$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は必ず入力(選択)してください";
					continue;
				}
			}

			// 半角カナチェック
			if($this->colmunArray[$key]->isNotHalfKana()){
				if (strlen($val) != 0){
					if($val != mb_convert_kana($val, "KV")){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "に半角カナが含まれています";
					}
				}
			}

			// 整数のみかチェック（小数点NG、マイナスOK）
			if($this->colmunArray[$key]->isIntOnly()){
				if (strlen($val) != 0){
					if(!is_numeric($val) || !(ceil($val) == $val)) {
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "に小数点以外の数値以外が含まれています";
					}
				}
			}

			// 整数のみかチェック（小数点NG、マイナスNG）
			if($this->colmunArray[$key]->isCurrencyOnly()){
				if (strlen($val) != 0){
					if(!is_numeric($val) || !(ceil($val) == $val) || !(abs($val) == $val)) {
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "に数値以外が含まれています";
					}
				}
			}

			// 率かチェック
			if($this->colmunArray[$key]->isRateOnly()){
				if (strlen($val) != 0){
					if(!is_numeric($val) || !(0 <= $val and $val <= 100)) {
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は1~100の範囲で指定してください";
					}
				}
			}
			
			// カナのみかチェック
			if($this->colmunArray[$key]->isKanaOnly()){
				if (strlen($val) != 0){
					if(!preg_match('/^[ァ-ヾ]+$/u', $val)){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "にカナ以外が含まれています";
					}
				}
			}

			// 日付整合性チェック
			if($this->colmunArray[$key]->isCorrectDate()){
				if (strlen($val) != 0){
					$dateUtil = new DateUtil();
					if(!$dateUtil->is_date($val)){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は正しい日付ではありません";
					}
				}
			}

			// ハイフンかスラッシュの日付整合性チェック
			if($this->colmunArray[$key]->isHyphenOrSlashDate()){
				if (strlen($val) != 0){
					$dateUtil = new DateUtil();
					if(!$dateUtil->check_date_ymd($val)){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は正しい日付でないか、「/」か「-」で区切られていません";
					}
				}
			}
			
			// 郵便番号チェック
			if($this->colmunArray[$key]->isPostal()){
				if (strlen($val) != 0){
					if(!preg_match('/\A[0-9]{3}-?[0-9]{4}\z/u', $val)){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は半角数字とハイフンの郵便番号を入力してください";
					}
				}
			}

			// 電話番号チェック
			if($this->colmunArray[$key]->isPhoneNumber()){
				if (strlen($val) != 0){
					if(!preg_match( '/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/', $val )){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は半角数字とハイフンの電話番号を入力してください";
					}
				}
			}

			// チェックイン アウト時刻の書式チェック
			if($this->colmunArray[$key]->isCheckInOutTime()){
				if (strlen($val) != 0){
					if(!preg_match( '/^\d{2}:\d{2}$/', $val )){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は「00:00」の形式で入力してください";
					}
				}
			}

			// 長さチェック
			if($this->colmunArray[$key]->isLength()){
				$range = $this->colmunArray[$key]->getLengthRange();
				if (mb_strlen($val) < $range[0] || mb_strlen($val) > $range[1]){
					if ($range[0] == $range[1]){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は" . $range[0] . "文字で入力してください";
					} elseif ($range[0] == 0){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は" . $range[1] . "文字以下で入力してください";
					} elseif ($range[1] == 0){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は" . $range[0] . "文字以上で入力してください";
					} else {
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は" . $range[0] . "文字以上" . $range[1] . "文字以下で入力してください";
					}
				}
			}

			// 独自チェック
			if($this->colmunArray[$key]->isOriginalValidation()){
				$funcArr = $this->colmunArray[$key]->getOriginalValidFunc();
				foreach ($funcArr as $method){
					if (method_exists($this, $method)){
						$error = $this->$method($val);
						if(!empty($error)){
							$rtnErrors[] = $error;
						}
					}
				}
			}

		}


		return $rtnErrors;
	}

	/** TBL共通フィールドに値を設定する。
	 * param    $tblModel 参照
	 *          $actionCd  処理 ("Brbank/create.")
	 */
	public function setInsertCommonColumn(&$tblModel, $action_cd)
	{
		$tblModel['entry_cd']                       = $action_cd;
		$tblModel['entry_ts']                       = date("Y-m-d H:i:s");
		$tblModel['modify_cd']                      = $action_cd;
		$tblModel['modify_ts']                      = date("Y-m-d H:i:s");
	}

	/** TBL共通フィールドに値を設定する。
	 * param    $tblModel 参照
	 *          $actionCd  処理 ("Brbank/create.")
	 */
	public function setUpdateCommonColumn(&$tblModel, $action_cd)
	{
		$tblModel['modify_cd']                      = $action_cd;
		$tblModel['modify_ts']                      = date("Y-m-d H:i:s");
	}

}
