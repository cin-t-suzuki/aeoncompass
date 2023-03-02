<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * リクエスト
 *
 */
class Request extends CommonDBModel
{
    use Traits;

    public function __construct()
    {
        // カラム情報の設定
    }

    // 生リクエスト内容をそのままGetパラメータに変換します。
    //
    //   aa_key      パラメータに変換する対象となるキー
    //   ab_include  true:aa_key に存在するもののみを表示 false:aa_keyに存在しないものを表示
    public function toCorrectQuery($aa_key = [], $ab_include = true)
    {

        foreach ($this->getCorrectParams() as $key => $value) {
            if ($ab_include) {
                if (in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (in_array($key2, $aa_key)) {
                                if (!is_null($value2)) {
                                    if ($value2 === "") {
                                        $result .= $key . "[" . $key2 . "]" . '=&';
                                    } else {
                                        $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                                    }
                                }
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            if ($value === "") {
                                $result .= $key . '=&';
                            } else {
                                $result .= $key . '=' . urlencode($value) . '&';
                            }
                        }
                    }
                }
            } else {
                if (!in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (!in_array($key2, $aa_key)) {
                                if (!is_null($value2)) {
                                    if ($value2 === "") {
                                        $result .= $key . "[" . $key2 . "]" . '=&';
                                    } else {
                                        $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                                    }
                                }
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            if ($value === "") {
                                $result .= $key . '=&';
                            } else {
                                $result .= $key . '=' . urlencode($value) . '&';
                            }
                        }
                    }
                }
            }
        }

        $s_result = substr($result, 0, -1);
        if ($s_result === false) {
            return null;
        }

        return $s_result;
    }
}
