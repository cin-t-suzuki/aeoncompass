<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Core extends CommonDBModel
{
    use Traits;

    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * 生リクエスト内容をそのままGetパラメータに変換します。
     *
     * @param array $aa_key
     * パラメータに変換する対象となるキー
     * @param bool $ab_include
     * true:aa_key に存在するもののみを表示 false:aa_keyに存在しないものを表示
     *
     * @return string
    */
    public function toQueryCorrect($aa_key = [], $ab_include = true)
    {
        //初期化
        $result = '';

        foreach ($_GET as $key => $value) {
            if ($ab_include) {
                if (in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (!is_null($value2)) {
                                $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            $result .= $key . '=' . urlencode($value) . '&';
                        }
                    }
                }
            } else {
                if (!in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (!is_null($value2)) {
                                $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            $result .= $key . '=' . urlencode($value) . '&';
                        }
                    }
                }
            }
        }

        foreach ($_POST as $key => $value) {
            if ($ab_include) {
                if (in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (!is_null($value2)) {
                                $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            $result .= $key . '=' . urlencode($value) . '&';
                        }
                    }
                }
            } else {
                if (!in_array($key, $aa_key)) {
                    if (is_array($value)) {
                        foreach ($value as $key2 => $value2) {
                            if (!is_null($value2)) {
                                $result .= $key . "[" . $key2 . "]" . '=' . urlencode($value2) . '&';
                            }
                        }
                    } else {
                        if (!is_null($value)) {
                            $result .= $key . '=' . urlencode($value) . '&';
                        }
                    }
                }
            }
        }

        if ($this->is_empty($result)) {
            return null;
        }

        return substr($result, 0, -1);
    }
}