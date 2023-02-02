<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Member extends CommonDBModel
{
    use Traits;

    protected $table = 'member';

    // カラム
    const COL_MEMBER_CD = 'member_cd';
    const COL_RESERVE_SYSTEM     = 'reserve_system';
    const COL_PARTNER_CD    = 'partner_cd';
    const COL_AFFILIATE_CD = 'affiliate_cd';
    const COL_AFFILIATE_CD_SUB = 'affiliate_cd_sub';
    const COL_MEMBER_STATUS = 'member_status';
    const COL_POINT_STATUS = 'point_status';
    const COL_ENTRY_DTM = 'entry_dtm';
    const COL_WITHDRAW_DTM = 'withdraw_dtm';
    const COL_NOTE = 'note';
    const COL_MEMBER_TYPE = 'member_type';


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
                self::COL_MEMBER_CD => $data[0]->member_cd,
                self::COL_RESERVE_SYSTEM     => $data[0]->reserve_system,
                self::COL_PARTNER_CD    => $data[0]->partner_cd,
                self::COL_AFFILIATE_CD => $data[0]->affiliate_cd,
                self::COL_AFFILIATE_CD_SUB => $data[0]->affiliate_cd_sub,
                self::COL_MEMBER_STATUS => $data[0]->member_status,
                self::COL_POINT_STATUS => $data[0]->point_status,
                self::COL_ENTRY_DTM => $data[0]->entry_dtm,
                self::COL_WITHDRAW_DTM => $data[0]->withdraw_dtm,
                self::COL_NOTE => $data[0]->note,
                self::COL_MEMBER_TYPE => $data[0]->member_type,
            ];
        }
        return null;
    }

    // メール強制配信停止状態を返す
    // 停止中 : ture 配信可：false
    public function isForcedStopMail($as_member_cd)
    {
        try {
            $o_member_stop_mail = new MemberForcedStopMail();
            $a_stop_mail = $o_member_stop_mail->selectByKey($as_member_cd); //find→selectByKeyでいいか？
            if ($this->is_empty($a_stop_mail)) {
                return false;
            }
            return true; //元ソースtureだがtrueでいいか？

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
