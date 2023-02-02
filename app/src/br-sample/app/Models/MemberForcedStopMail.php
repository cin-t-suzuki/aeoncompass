<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberForcedStopMail extends CommonDBModel
{

    protected $table = 'member_forced_stop_mail';

    // カラム
    const COL_MEMBER_CD = 'member_cd';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'member_cd';


    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($member_cd)
    {
        $data = $this->where(self::COL_MEMBER_CD, $member_cd)->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_MEMBER_CD => $data[0]->member_cd
            ];
        }
        return null;
    }
}
