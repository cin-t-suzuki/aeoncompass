<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class SubmitFormCheck extends CommonDBModel
{
    use Traits;

    protected $table = 'submit_form_check';

    // カラム
    const COL_CHECK_CD = 'check_cd';

    public function __construct()
    {
        // カラム情報の設定
    }

    /**  新規登録
     *
     * @param [type] $con
     * @param [type] $data
     * @return
     */
    public function insert($con, $data)
    {
        $result = $con->table($this->table)->insert($data);
        return  $result;
    }
}
