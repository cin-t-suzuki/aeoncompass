<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

class HotelSupervisor extends CommonDBModel
{
    use Traits;

    // 統括一覧を取得
    protected $table = "hotel_supervisor";

    // カラム
    public string $COL_SUPERVISOR_CD = "supervisor_cd";
    public string $COL_SUPERVISOR_NM = "supervisor_nm";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";

    public string $METHOD_SAVE = "save";
    public string $METHOD_UPDATE = "update";

    /**
     * コンストラクタ
     */
    function __construct()
    {
        $colSupervisorCd = (new ValidationColumn())->setColumnName($this->COL_SUPERVISOR_CD, "施設統括コード")->require()->length(0, 10)->notHalfKana();
        $colSupervisorNm = (new ValidationColumn())->setColumnName($this->COL_SUPERVISOR_NM, "施設統括名")->require()->length(0, 42)->notHalfKana();

        parent::setColumnDataArray([$colSupervisorCd, $colSupervisorNm]);
    }

    public function getHotelSupervisor($aa_conditions = array())
    {

        $a_conditions = array();

        $s_supervisor_cd = "";
        if (isset($aa_conditions['supervisor_cd']) && !$this->is_empty($aa_conditions['supervisor_cd'])) {
            $s_supervisor_cd = "and	hotel_supervisor.supervisor_cd = :supervisor_cd";
            $a_conditions['supervisor_cd'] = $aa_conditions['supervisor_cd'];
        }

        $s_sql =
<<<SQL
		select	q1.supervisor_cd,
				q1.supervisor_nm,
				hotel_supervisor_account.account_id,
				hotel_supervisor_account.password,
				hotel_supervisor_account.accept_status
		from	hotel_supervisor_account,
			(
				select	hotel_supervisor.supervisor_cd,
						hotel_supervisor.supervisor_nm
				from	hotel_supervisor
				where	null is null
					{$s_supervisor_cd}
			) q1
		where	hotel_supervisor_account.supervisor_cd = q1.supervisor_cd
		order by hotel_supervisor_account.accept_status desc,q1.supervisor_cd  	
SQL;

        $data = DB::select($s_sql, $a_conditions);
        $result = [];
        $accept_status = 'accept_status';
        if (!is_null($data) && count($data) > 0) {
            foreach ($data as $row) {
                $result[] = array(
                    $this->COL_SUPERVISOR_CD => $row->supervisor_cd,
                    $this->COL_SUPERVISOR_NM => $row->supervisor_nm,
                    $accept_status => $row->accept_status
                );
            }
        }
        return array('values' => $result);
    }


    /** 主キーで取得
     */
    public function selectByKey($supervisorCd)
    {
        $data = $this->where($this->COL_SUPERVISOR_CD, $supervisorCd)->get();
        if (!is_null($data) && count($data) > 0) {
            return array(
                $this->COL_SUPERVISOR_CD => $data[0]->supervisor_cd,
                $this->COL_SUPERVISOR_NM => $data[0]->supervisor_nm,
            );
        }
        return [];
    }

    /**  キーで更新
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)
            ->where(array($this->COL_SUPERVISOR_CD => $data[$this->COL_SUPERVISOR_CD]))
            ->update($data);
        if ($result) {
            return $result;
        } elseif (!$result) {
            return false;
        }
    }

    /** 新規登録(1件)
     */
    public function singleInsert($con, $data)
    {

        $result = $con->table($this->table)->insert($data);
        if (!$result) {
            return "登録に失敗しました";
        }
        return "";
    }
}
