<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SubmitFormCheck;
use Exception;

class Core extends CommonDBModel
{
    use Traits;

    // 別モデルから取得するため、protected→publicへ変更していいか？
    public $s_partner_cd       = null;  // 提携先コード

    // カラム

    public function __construct()
    {
        // カラム情報の設定
    }

    // 提携先コードの設定
    //
    public function setPartnerCd($as_partner_cd)
    {
        $this->s_partner_cd = $as_partner_cd;
    }

    // 生リクエスト内容をそのままGetパラメータに変換します。
    //
    //   aa_key      パラメータに変換する対象となるキー
    //   ab_include  true:aa_key に存在するもののみを表示 false:aa_keyに存在しないものを表示
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

    // 重複登録防止策
    public function isDuplicate($as_sfck)
    {
        //非表示部分元ソース、書き換えあっているか？
        //バリデーションはコントローラ側で取得した値を引き継ぐ形で実装（sendメソッドの引数でFormリクエスト使用）
        //↑の場合、sfckはhiddenなのにバリデーションエラーならform送信時にエラーメッセージが出てしまうがいいか？
        //違う形で実装する場合はapp/Http/Requests/SubmitFormCheckRequest.phpは不要、sendメソッドの引数も変更要

        // // ボックスを取得l
        // $o_controller = Zend_Controller_Front::getInstance();
        // $box  = &$o_controller->getPlugin('Box')->box;

        if ($this->is_empty($as_sfck)) {
            return true;
        }

        // 重複確認
        // $validations = Validations::getInstance($box);
        // $validations->set_table(Submit_Form_Check::getInstance());
        // $validations->set_validate(array('Submit_Form_Check'    => 'check_cd')); // チェックコード

        $submitFormCheckModel = new SubmitFormCheck();

        //値のセット
        $a_params['check_cd'] = $as_sfck;

        // 共通カラム値設定
        $submitFormCheckModel->setInsertCommonColumn($a_params);

        // if (!($o_check->save())) {
        //     return false;
        // }

        // if ($o_check->row_count() == 0) {
        //     return false;
        // }

        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $submitFormCheckModel, $a_params) {
                // DB更新
                $submitFormCheckModel->insert($con, $a_params);
            });
        } catch (Exception $e) {
            $errorList[] = '返信の登録処理でエラーが発生しました。';
        }
        // 更新エラー
        if (
            count($errorList) > 0 || !empty($dbErr)
        ) {
            return false;
        }

        return true;
    }
}
