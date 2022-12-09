<?php

namespace App\Rules;

class EmailCommon
{
    /**
     * 有効なメールアドレスかを判定
     *
     * ベストリザーブの判定基準を踏襲
     * MEMO: 移植元 plugins\ActiveRecord\lib\Validations\Validator.php
     *
     * @param string $email
     * @return boolean
     */
    protected function isEmail($email): bool
    {
        var_dump('email common called.');
        // 『@』が複数ないか？
        if (1 < substr_count($email, '@')) {
            return false;
        }

        // 『@』が先頭と末尾にないか？
        if (preg_match('/^@/', $email) or preg_match('/@$/', $email)) {
            return false;
        }
        $s_account = substr($email, 0, strpos($email, '@'));
        $s_domain  = substr($email, strrpos($email, '@') + 1);
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
