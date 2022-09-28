<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/**
 * 銀行マスタ
 */
class MastBank extends CommonDBModel
{
    protected $table = "mast_bank";
    // カラム
    public string $COL_BANK_CD = "bank_cd";
    public string $COL_BANK_NM = "bank_nm";
    public string $COL_BANK_KN = "bank_kn";


    /**
     * コンストラクタ
     */
    function __construct(){
        // カラム情報の設定
        $colBankCd = new ValidationColumn();
        $colBankCd->setColumnName($this->COL_BANK_CD, "銀行コード")->require()->notHalfKana()->length(4, 4);
        $colBankNm = new ValidationColumn();
        $colBankNm->setColumnName($this->COL_BANK_NM, "銀行名称")->require()->notHalfKana()->length(0, 50);
        $colBankKn = new ValidationColumn();
        $colBankKn->setColumnName($this->COL_BANK_KN, "銀行名称（カナ）")->require()->notHalfKana()->length(0, 15)->originalValidation("bankKnValidate");
        parent::setColumnDataArray([$colBankCd, $colBankNm, $colBankKn]);
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($bankCd){
        $data = $this->where("bank_cd", $bankCd)->get();
        if(!is_null($data) && count($data) > 0){
            return array(
                "bank_cd" => $data[0]->bank_cd,
                "bank_nm" => $data[0]->bank_nm,
                "bank_kn" => $data[0]->bank_kn,
            );
        }
        return null;
    }

    /*
     * 銀行支店を取得
     */
    public function get_bankbranch($keyword)
    {
        // スペースで区切って検索する
        $keywordArr = preg_split('/ |　/', $keyword);
        $wheres = "";
        for ($i=0; $i < count($keywordArr); $i++) {
            $orgKey = trim($keywordArr[$i]);
            $knKey = trim(mb_convert_kana($orgKey, 'CKVAs'));
            if (!empty($orgKey)) {
                $wheres .= <<<SQL
                and	(
                    q1.bank_cd like '%{$orgKey}%' or q1.bank_cd like '%{$knKey}%'
                    or q1.bank_nm like '%{$orgKey}%' or q1.bank_nm like '%{$knKey}%'
                    or q1.bank_kn like '%{$orgKey}%' or q1.bank_kn like '%{$knKey}%'
                    or q2.bank_branch_cd like '%{$orgKey}%' or q2.bank_branch_cd like '%{$knKey}%'
                    or q2.bank_branch_nm like '%{$orgKey}%' or q2.bank_branch_nm like '%{$knKey}%'
                    or q2.bank_branch_kn like '%{$orgKey}%' or q2.bank_branch_kn like '%{$knKey}%'
                )
                SQL;
            }
        }
        // キーワード
        if(empty($wheres)){
            throw new \Exception('検索キーワードを設定してください。');
        }

        $query = <<<SQL
        select
            q1.bank_cd,
            q1.bank_nm,
            q1.bank_kn,
            q2.bank_branch_cd,
            q2.bank_branch_nm,
            q2.bank_branch_kn
        from
            mast_bank q1
            left join mast_bank_branch q2 on q1.bank_cd = q2.bank_cd
        where
            1 = 1
            {$wheres}
        order by
            q1.bank_kn,
            q2.bank_branch_kn
        SQL;

        // データを取得
        $result = null;
        $data = DB::select($query);
        if(!empty($data) && count($data) > 0){
            foreach($data as $row){
                $result[$row->bank_cd]["bank"] = [
                    "bank_cd" => $row->bank_cd,
                    "bank_nm" => $row->bank_nm,
                    "bank_kn" => $row->bank_kn];
                if (empty($row->bank_branch_cd)) {
                    $result[$row->bank_cd]["branch"] = [];
                } else {
                    $result[$row->bank_cd]["branch"][] = [
                        "bank_branch_cd" => $row->bank_branch_cd,
                        "bank_branch_nm" => $row->bank_branch_nm,
                        "bank_branch_kn" => $row->bank_branch_kn,
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * 新規登録(1件)
     */
    public function singleInsert($con, $data){
        // 重複チェック
        $cnt = $this->where($this->COL_BANK_CD, $data[$this->COL_BANK_CD])->count();
        if($cnt > 0){
            return "ご指定の銀行コードは既に存在しています";
        }
        // 銀行名称（カナ）はカタカナに変換
        $data[$this->COL_BANK_KN] = trim(mb_convert_kana($data[$this->COL_BANK_KN], 'CKVAs'));

        $result = $con->table($this->table)->insert($data);
        if(!$result){
            return "登録に失敗しました";
        }
        return "";
    }

    /**
     * 更新(1件)
     */
    public function singleUpdate($con, $bankCd, $data){
        // 銀行名称（カナ）はカタカナに変換
        $data[$this->COL_BANK_KN] = trim(mb_convert_kana($data[$this->COL_BANK_KN], 'CKVAs'));

        $result = $con->table($this->table)->where("bank_cd", $bankCd)->update($data);
        if(!$result){
            return "更新に失敗しました";
        }
        return "";
    }

    /**
     * 独自のバリデーション
	 *   銀行名称（カナ）
     */
    protected function bankKnValidate($value){
        if (mb_strlen(mb_convert_kana($value, 'hkas')) > 15 ) {
            return '銀行名称（カナ）は、半角カナ文字にしたときに15文字以内になるように入力してください';
        }
    }

}
