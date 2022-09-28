<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/**
 * 銀行支店マスタ
 */
class MastBankBranch extends CommonDBModel
{
    protected $table = "mast_bank_branch";
    // カラム
    public string $COL_BANK_CD = "bank_cd";
    public string $COL_BANK_BRANCH_CD = "bank_branch_cd";
    public string $COL_BANK_BRANCH_NM = "bank_branch_nm";
    public string $COL_BANK_BRANCH_KN = "bank_branch_kn";


    /**
     * コンストラクタ
     */
    function __construct(){
        // カラム情報の設定
        $colArr[] = new ValidationColumn();
        $colArr[0]->setColumnName($this->COL_BANK_CD, "銀行コード")->require()->notHalfKana()->length(4, 4);
        $colArr[] = new ValidationColumn();
        $colArr[1]->setColumnName($this->COL_BANK_BRANCH_CD, "支店コード")->require()->notHalfKana()->length(3, 3);
        $colArr[] = new ValidationColumn();
        $colArr[2]->setColumnName($this->COL_BANK_BRANCH_NM, "支店名称")->require()->notHalfKana()->length(0, 30);
        $colArr[] = new ValidationColumn();
        $colArr[3]->setColumnName($this->COL_BANK_BRANCH_KN, "支店名称（カナ）")->require()->notHalfKana()->length(0, 15)->originalValidation("bankBranchKnValidate");
        parent::setColumnDataArray($colArr);
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($bankCd, $bankBranchCd){
        $data = $this->where("bank_cd", $bankCd)->where("bank_branch_cd", $bankBranchCd)->get();
        if(!is_null($data) && count($data) > 0){
            return array(
                "bank_cd" => $data[0]->BANK_CD,
                "bank_branch_cd" => $data[0]->bank_branch_cd,
                "bank_branch_nm" => $data[0]->bank_branch_nm,
                "bank_branch_kn" => $data[0]->bank_branch_kn,
            );
        }
        return null;
    }

    /**
     * 銀行コードで取得
     */
    public function selectByBankCd($bankCd){
        $data = $this->where("bank_cd", $bankCd)->orderBy("bank_branch_cd", "asc")->get();
        $rtnArr = [];
        if(!is_null($data) && count($data) > 0){
            foreach($data as $row){
                $rtnArr[] = array(
                    "bank_cd" => $row->bank_cd,
                    "bank_branch_cd" => $row->bank_branch_cd,
                    "bank_branch_nm" => $row->bank_branch_nm,
                    "bank_branch_kn" => $row->bank_branch_kn,
                );
            }
        }
        return $rtnArr;
    }

    /**
     * 新規登録(1件)
     */
    public function singleInsert($con, $data){
        // 重複チェック
        $cnt = $this->where($this->COL_BANK_CD, $data[$this->COL_BANK_CD])
            ->where($this->COL_BANK_BRANCH_CD, $data[$this->COL_BANK_BRANCH_CD])
            ->count();
        if($cnt > 0){
            return "ご指定の支店コードは既に存在しています";
        }
        // 支店名称（カナ）はカタカナに変換
        $data[$this->COL_BANK_BRANCH_KN] = trim(mb_convert_kana($data[$this->COL_BANK_BRANCH_KN], 'CKVAs'));

        $result = $con->table($this->table)->insert($data);
        if(!$result){
            return "登録に失敗しました";
        }
        return "";
    }

    /**
     * 更新(1件)
     */
    public function singleUpdate($con, $bankCd, $bankBranchCd, $data){
        // 支店名称（カナ）はカタカナに変換
        $data[$this->COL_BANK_BRANCH_KN] = trim(mb_convert_kana($data[$this->COL_BANK_BRANCH_KN], 'CKVAs'));

        $result = $con->table($this->table)->where("bank_cd", $bankCd)->where("bank_branch_cd", $bankBranchCd)->update($data);
        if(!$result){
            return "更新に失敗しました";
        }
        return "";
    }

    /**
     * 独自のバリデーション
	 *   支店名称（カナ）
     */
    protected function bankBranchKnValidate($value){
        if (mb_strlen(mb_convert_kana($value, 'hkas')) > 15 ) {
            return '支店名称（カナ）は、半角カナ文字にしたときに15文字以内になるように入力してください。';
        }
    }

}
