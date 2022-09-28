<?php

namespace App\ViewHelper;


/**
 * Viewで扱うHelperメソッド群
 */
class RsvHelper
{
    /**
     * HTMLタグを除去します。
     */
    public static function strip_tags($as_value, $as_allowable_tags = null, $ab_escape_html = true){

        // タグでないと思われるものの < を ＜ に変換 ActiveRecord _arrangement でも利用
        // 開始タグがある 且つ 文字列の最後までチェックしていなかったら
        $n_char = 0;
        $s_result = null;
        while (mb_strpos($as_value, '<') !== false and $n_char < mb_strlen($as_value)){

            // 現在の位置より後ろに < があるか？
            if (mb_strpos(mb_substr($as_value, $n_char), '<') !== false){
                $s_result .= mb_substr($as_value, $n_char, mb_strpos(mb_substr($as_value, $n_char), '<'));

                // <([a-zA-Z]|\/|\!) でなければ
                if (!(preg_match('/<([a-zA-Z]|\/|\!)/', mb_substr(mb_substr($as_value, $n_char), mb_strpos(mb_substr($as_value, $n_char), '<'), 2)))){
                    $s_result .= '＜' . mb_substr(mb_substr($as_value, $n_char), mb_strpos(mb_substr($as_value, $n_char), '<') + 1, 1);

                } else {
                    $s_result .= mb_substr(mb_substr($as_value, $n_char), mb_strpos(mb_substr($as_value, $n_char), '<'), 2);
                }

                $s_result = str_replace('<!--', '＜！－－', $s_result);

            } else {
                $s_result .= mb_substr($as_value, $n_char);
            }

            $n_char = mb_strlen($s_result);
        }

        if (!(is_null($s_result))){
            $as_value = $s_result;
        }

        $as_value = strip_tags($as_value, $as_allowable_tags);

        // HTMLをエンティティにします。
        if ($ab_escape_html) {
            return  htmlspecialchars(str_replace('＜！－－', '<!--', $as_value), ENT_COMPAT, 'UTF-8', false);
        } else {
            return  str_replace('＜！－－', '<!--', $as_value);
        }

    }


    /**
     * 文字列を左から文字数分切り出します。
     */
    public static function left($as_string, $an_length, $as_ext = null, $an_ext_length = 0)
    {
        try {

            // 長さが指定されていない場合は元の文字列を返却
            if ($an_length == 0){
                return $as_string;
            }

            // 文字数が指定された長さよりも短い場合は元の文字列を返却
            if (mb_strlen($as_string, "UTF-8") <= $an_length) {
                return $as_string;
            }

            // 文字数を切り出したものに拡張文字を付与し返却
            return mb_substr($as_string, 0, $an_length - $an_ext_length , "UTF-8") . $as_ext;

        // 各メソッドで Exception が投げられた場合
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
