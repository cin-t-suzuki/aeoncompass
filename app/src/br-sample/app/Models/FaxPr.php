<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;


/**
 * 予約通知FAX掲載広告文章
 */
class FaxPr extends CommonDBModel
{
	protected $table = "fax_pr"; 
	// カラム
	public string $COL_FAX_PR_ID = "fax_pr_id";  // １レコード（ID:1）しか存在しません。
	public string $COL_TITLE = "title";
	public string $COL_NOTE = "note";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colFaxPrId = new ValidationColumn();
		$colFaxPrId->setColumnName($this->COL_FAX_PR_ID, "FAX掲載広告文章ID")->require()->length(1, 1);
		$colTitle = new ValidationColumn();
		$colTitle->setColumnName($this->COL_TITLE, "タイトル")->require()->notHalfKana()->length(0, 15);
		$colNote = new ValidationColumn();
		$colNote->setColumnName($this->COL_NOTE, "広告文章")->require()->notHalfKana()->length(0, 400);

		parent::setColumnDataArray([$colFaxPrId, $colTitle, $colNote]);
	}

	/**
	 * 主キーで取得
	 */
	public function selectByKey($faxPrId = 1){
		$data = $this->where("fax_pr_id", $faxPrId)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_FAX_PR_ID => $data[0]->fax_pr_id,
				$this->COL_TITLE => $data[0]->title,
				$this->COL_NOTE => $data[0]->note
			);
		}
		return null;
	}

	public function updateByKey($con, $data){
        $result = $con->table($this->table)->where($this->COL_FAX_PR_ID, $data[$this->COL_FAX_PR_ID])->update($data);
        if(!$result){
            return "更新に失敗しました";
        }
        return "";
	}


}
