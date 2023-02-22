<?php

namespace App\Common;
use App\Models\Core;

trait Traits
{
	// 文字列が NULL または空文字または要素を持たない配列かを判断します。
	// 0についての扱いがzap_is_emptyを異なります
	// 0 は false と判断します。
	//
	// example
	//   ''       -> true
	//   null     -> true
	//   array    -> true
	//   0        -> false
	//   'a'      -> false
	//   array(0) -> false
	public function is_empty($a_val)
	{

		if (is_null($a_val)) {
			return true;
		}

		if ($a_val === '') {
			return true;
		}

		if (is_array($a_val)) {
			if (count($a_val) == 0){
				return true;
			}
		}

		return false;
	}

	// メールアドレスチェック
	//
	// a_attributes  には対象となるカラムと値を設定します。
	//
	// options
	//   なし
	//
	// true  メールアドレスにマッチ
	// false メールアドレスにマッチしない
	public function is_mail($as_attributes){

		if (strlen($as_attributes) != 0){
			return $this->_is_mail($as_attributes);
		}

	}

	// メールアドレスチェック
	//
	// true  メールアドレスにマッチ
	// false メールアドレスにマッチしない
	private function _is_mail($as_attributes){

		// 『@』が複数ないか？
		if (1 < substr_count($as_attributes, '@')){
			return false;
		}

		// 『@』が先頭と末尾にないか？
		if (preg_match('/^@/', $as_attributes) or preg_match('/@$/', $as_attributes)){
			return false;
		}
		$s_account = substr($as_attributes, 0, strpos($as_attributes, '@'));
		$s_domain  = substr($as_attributes, strrpos($as_attributes, '@') + 1 );
		$a_domain  = explode('\.', $s_domain);

		// 『A-Z』『a-z』『0-9』『.』
		// 『!』『#』『$』『%』『&』『'』
		// 『*』『+』『-』『/』『=』『?』
		// 『^』『_』『`』『{』『|』『}』『~』で構成されているか？
		if (!(preg_match("|^[A-Za-z0-9\.!#\$%&'\*\+\-/=\?\^_`\{\|\}~]+$|", $s_account))){
			return false;
		}

		// アカウントは128文字以下か？
		if (128 < strlen($s_account)){
			return false;
		}

		// アカウントの最後に『.』がないか？ Docomo Au を考慮して処理しない

		// トップレベルドメインチェックしない


		// 『A-Z』『a-z』『0-9』『.』『-』で構成されているか？
		if (!(preg_match('|^[A-Za-z0-9\.\-]+$|', $s_domain))){
			return false;
		}

		// 末尾は『.』＋『２文字以上の英字』で構成されているか？
		if (!(preg_match('/\.+[A-Za-z]{2,}$/', $s_domain))){
			return false;
		}

		// ドメイン全体で255文字以下か？
		if (255 < strlen($s_domain)){
			return false;
		}

		// 『..』がないか？
		if (preg_match('/.+\.\..+/', $s_domain)){
			return false;
		}

		// ドメインの最初と最後に『.』がないか？
		if (preg_match('/^\./', $s_domain) or preg_match('/\.$/', $s_domain)){
			return false;
		}

		//
		for ($n_cnt = 0; $n_cnt < count($a_domain); $n_cnt++){
			if (63 < strlen($a_domain[$n_cnt])){
				return false;
			}

			if (preg_match('/^-/', $a_domain[$n_cnt]) or preg_match('/-$/', $a_domain[$n_cnt])){
				return false;
			}
		}

		return true;

	}


    /**
     * 生リクエスト内容をそのままGetパラメータに変換します
     * @param string $as_key パラメータに変換する対象となるキーを「,」で区切って設定します。
     * @param bool $ab_include true:aa_key に存在するもののみを表示 false:aa_keyに存在しないものを表示
     * @return array
     */
    public function toQueryCorrect($as_key, $ab_include = true)
    {

        $aa_key = explode(',', $as_key);

        $core = new Core();
        return $core->toQueryCorrect($aa_key, $ab_include);
    }
}
