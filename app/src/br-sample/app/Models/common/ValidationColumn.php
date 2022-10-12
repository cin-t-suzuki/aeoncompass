<?php

namespace App\Models\common;


/**
 * カラム情報クラス
 * ・実施するバリデーション情報を保持
 * ・設定メソッドはチェーン可
 */
class ValidationColumn
{
	private $columnId = "";                // 物理カラム名
	private $columnName = "";              // 論理カラム名
	private $requireCheck = false;         // 必須チェック対象
	private $notHalfKanaCheck = false;     // 半角カナチェック対象
	private $kanaOnlyCheck = false;     // カナのみかチェック対象
	private $intOnlyCheck = false;         // 数値のみかチェック対象
	private $currencyOnlyCheck = false;         // 数値のみかチェック対象
	private $rateOnlyCheck = false;         // 数値のみかチェック対象
	private $correctDateCheck = false;          // 日付の妥当性チェック対象
	private $hyphenOrSlashDateCheck = false;          // ハイフンかスラッシュ日付の妥当性チェック対象
	private $postalCheck = false;          //郵便番号の妥当性チェック対象
	private $phoneNumberCheck = false;          //電話番号の妥当性チェック対象
	private $emailsCheck = false;               // メールアドレスの妥当性チェック対象 (カンマ区切りで列挙可)
	private $checkInOutTimeCheck = false;          //チェックインとアウトの時刻書式のチェック対象

	private $inputTypeCheck = false;       // 入力チェック対象
	private $inputTypeArray = [];          // 入力可能タイプ
	private $lengthCheck = false;          // 文字数チェック
	private $lengthRange = [];             // 入力可能範囲

	private $originalValidation = false;   // 独自チェック処理対象
	private $originalValidFunc = [];       // 独自チェックメソッド名



	/**
	 * カラム情報の設定
	 */
	public function setColumnName($physics, $logical){
		$this->columnId = $physics;
		$this->columnName = $logical;
		return $this;
	}
	public function getColumnId(){ return $this->columnId; }
	public function getColumnName(){ return $this->columnName; }

	/**
	 * 必須チェック
	 */
	public function require(){
		$this->requireCheck = true;
		return $this;
	}
	public function isRequire(){ return $this->requireCheck; }

	/**
	 * 半角カナチェック
	 */
	public function notHalfKana(){
		$this->notHalfKanaCheck = true;
		return $this;
	}
	public function isNotHalfKana(){ return $this->notHalfKanaCheck; }

	/**
	 * カナのみかチェック
	 */
	public function kanaOnly(){
		$this->kanaOnlyCheck = true;
		return $this;
	}
	public function isKanaOnly(){ return $this->kanaOnlyCheck; }

	/**
	 * 数字形式である（小数点NG、マイナスOK）。only_integer
	 */
	public function intOnly(){
		$this->intOnlyCheck = true;
		return $this;
	}
	public function isIntOnly(){ return $this->intOnlyCheck; }

	/**
	 * 数字形式（小数点NG、マイナスNG）である。only_currency
	 */
	public function currencyOnly(){
		$this->currencyOnlyCheck = true;
		return $this;
	}
	public function isCurrencyOnly(){ return $this->currencyOnlyCheck; }

	/**
	 * 0-100の率形式である。only_rate
	 */
	public function rateOnly(){
		$this->rateOnlyCheck = true;
		return $this;
	}
	public function isRateOnly(){ return $this->rateOnlyCheck; }

	/**
	 * 日付チェック
	 */
	public function correctDate(){
		$this->correctDateCheck = true;
		return $this;
	}
	public function isCorrectDate(){ return $this->correctDateCheck; }

	/**
	 * ハイフンとスラッシュの日付チェック
	 */
	public function hyphenOrSlashDate(){
		$this->hyphenOrSlashDateCheck = true;
		return $this;
	}
	public function isHyphenOrSlashDate(){ return $this->hyphenOrSlashDateCheck; }

	/**
	 * 半角ハイフンの郵便番号かチェック
	 */
	public function postal(){
		$this->postalCheck = true;
		return $this;
	}
	public function isPostal(){ return $this->postalCheck; }

	/**
	 * 半角ハイフンの電話番号かチェック
	 */
	public function phoneNumber(){
		$this->phoneNumberCheck = true;
		return $this;
	}
	public function isPhoneNumber(){ return $this->phoneNumberCheck; }

    /**
     * メールアドレス (カンマ区切りで列挙可) かチェック
     */
    public function emails() {
        $this->emailsCheck = true;
        return $this;
    }
    public function isEmails() { return $this->emailsCheck; }

	/**
	 * チェックインとアウトの時刻書式チェック
	 */
	public function checkInOutTime(){
		$this->checkInOutTimeCheck = true;
		return $this;
	}
	public function isCheckInOutTime(){ return $this->checkInOutTimeCheck; }


	/**
	 * 入力タイプチェック
	 */
	public function inputType($permissionTypeArr){
		$this->inputTypeCheck = true;
		$this->inputTypeArray = $permissionTypeArr;
		return $this;
	}
	public function isInputType(){ return $this->inputTypeCheck; }
	public function getInputArray(){ return $this->inputTypeArray; }

	/**
	 * 入力範囲チェック
	 */
	public function length($start, $end){
		$this->lengthCheck = true;
		$this->lengthRange = [$start, $end];
		return $this;
	}
	public function isLength(){ return $this->lengthCheck; }
	public function getLengthRange(){ return $this->lengthRange; }

	/**
	 * 独自チェックメソッド
	 */
	public function originalValidation($functionName){
		$this->originalValidation = true;
		$this->originalValidFunc[] = $functionName;
		return $this;
	}
	public function isOriginalValidation(){ return $this->originalValidation; }
	public function getOriginalValidFunc(){ return $this->originalValidFunc; }

	
}
