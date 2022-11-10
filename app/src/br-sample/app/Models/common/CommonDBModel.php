<?php

namespace App\Models\common;
use Illuminate\Database\Eloquent\Model;
use App\Common\DateUtil;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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

			// 全角英数チェック
			if($this->colmunArray[$key]->isNotFullCharacter()){
				if (strlen($val) != 0){
					if($val != mb_convert_kana($val, "kvrn")){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "に全角英数が含まれています";
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
					if(!preg_match( '/(^0\d{1,4}?-\d{1,4}?-\d{1,4}$|^\d{9,12}$)/', $val )){
						$rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "は半角数字とハイフンの電話番号を入力してください";
					}
				}
			}

            // メールアドレスチェック（単体）
            if ($this->colmunArray[$key]->isEmail()) {
                if (strlen($val) != 0) {
                    if (!$this->is_mail($val)) {
                        $rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "を半角で正しく入力してください";
                    }
                }
            }

            // メールアドレスチェック（列挙可）
            if ($this->colmunArray[$key]->isEmails()) {
                if (strlen($val) != 0) {
                    if (!$this->is_mails($val)) {
                        $rtnErrors[] = $this->colmunArray[$key]->getColumnName() . "を半角で正しく入力してください";
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
	 *          $actionCd 不要となったので削除予定の引数（処理 ("Brbank/create.")）
	 */
	public function setInsertCommonColumn(&$tblModel, $action_cd = null)
	{
		$action_cd = $this->getActionCd();
		$tblModel['entry_cd']                       = $action_cd;
		$tblModel['entry_ts']                       = date("Y-m-d H:i:s");
		$tblModel['modify_cd']                      = $action_cd;
		$tblModel['modify_ts']                      = date("Y-m-d H:i:s");
	}

	/** TBL共通フィールドに値を設定する。（参照渡し）
	 * param    $tblModel 参照
	 *          $actionCd 不要となったので削除予定の引数（ 処理 ("Brbank/create.")）
	 */
	public function setUpdateCommonColumn(&$tblModel, $action_cd = null)
	{
		$tblModel['modify_cd']                      = $this->getActionCd();
		$tblModel['modify_ts']                      = date("Y-m-d H:i:s");
	}


	/** コントローラ名とアクション名を取得して、ユーザーIDと連結
	 *  ユーザーID取得は暫定の為、書き換え替えが必要です。
	 *
	 * @return string
	 */
	private function getActionCd(){
		// 
		$path = explode("@", Route::currentRouteAction());
		$pathList = explode('\\', $path[0]);
		$controllerName = str_replace("Controller","",end($pathList)); //コントローラ名
		$actionName = $path[1];           // アクション名
		$userId = Session::get("user_id"); //TODO ユーザー情報取得のキーは仮です
		$action_cd = $controllerName."/".$actionName.".".$userId;
		return $action_cd;
	}

	/** 	シーケンス取得
	 *
	 * @param [type] $sequenceName
	 * @return int
	 */
	public function incrementSequence($sequenceName){

		if(!isset($sequenceName)){
			return "シーケンス名が指定されていません";
		}

		$a_conditions['sequence_name'] = $sequenceName;

		$s_sql = <<<SQL
			SELECT  NextVal(:sequence_name) as val
		SQL;

		$data = DB::select($s_sql, $a_conditions);

		return $data[0]->val;
	}

    // メールアドレスチェック（単体）
    public function is_mail($email)
    {
        if (strlen($email) != 0) {
            return $this->_is_mail($email);
        }
    }
    // メールアドレスチェック（列挙可）
    public function is_mails($emails)
    {
        if (strlen($emails) != 0) {
            $values = explode(',', $emails);
            $result = true;
            foreach ($values as $value) {
                $result = $result && $this->_is_mail($value);
            }
            return $result;
        }
    }
    private function _is_mail($email)
    {
        // 『@』が複数ないか？
        if (1 < substr_count($email, '@')) {
            return false;
        }

        // 『@』が先頭と末尾にないか？
        if (preg_match('/^@/', $email) or preg_match('/@$/', $email)) {
            return false;
        }
        $s_account = substr($email, 0, strpos($email, '@'));
        $s_domain  = substr($email, strrpos($email, '@') + 1 );
        $a_domain  = explode('.', $s_domain);

        // 『A-Z』『a-z』『0-9』『.』
        // 『!』『#』『$』『%』『&』『'』
        // 『*』『+』『-』『/』『=』『?』
        // 『^』『_』『`』『{』『|』『}』『~』で構成されているか？
        if (!(preg_match("|^[A-Za-z0-9\.!#\$%&'\*\+\-/=\?\^_`\{\|\}~]+$|", $s_account))) {
            return false;
        }

        // アカウントは128文字以下か？
        if (128 < strlen($s_account)) {
            return false;
        }

        // アカウントの最後に『.』がないか？ Docomo Au を考慮して処理しない

        // トップレベルドメインチェックしない


        // 『A-Z』『a-z』『0-9』『.』『-』で構成されているか？
        if (!(preg_match('|^[A-Za-z0-9\.\-]+$|', $s_domain))) {
            return false;
        }

        // 末尾は『.』＋『２文字以上の英字』で構成されているか？
        if (!(preg_match('/\.+[A-Za-z]{2,}$/', $s_domain))) {
            return false;
        }

        // ドメイン全体で255文字以下か？
        if (255 < strlen($s_domain)) {
            return false;
        }

        // 『..』がないか？
        if (preg_match('/.+\.\..+/', $s_domain)) {
            return false;
        }

        // ドメインの最初と最後に『.』がないか？
        if (preg_match('/^\./', $s_domain) or preg_match('/\.$/', $s_domain)) {
            return false;
        }

        //
        foreach ($a_domain as $val) {
            if (63 < strlen($val)) {
                return false;
            }

            if (preg_match('/^-/', $val) or preg_match('/-$/', $val)) {
                return false;
            }
        }

        return true;
    }

}
