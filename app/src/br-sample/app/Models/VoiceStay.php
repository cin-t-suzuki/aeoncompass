<?php

namespace App\Models;
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Models\Reserve;
use Exception;

/**
 * パートナーマスタ
 */
class VoiceStay extends CommonDBModel
{
    use Traits;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'voice_stay';

    // カラム
    const COL_VOICE_CD  = 'voice_cd';
    const COL_RESERVE_CD  = 'reserve_cd';
    const COL_HOTEL_CD       = 'hotel_cd';
    const COL_MEMBER_CD  = 'member_cd';
    const COL_TITLE      = 'title';
    const COL_EXPLAIN   = 'explain';
    const COL_EXPERIENCE_DTM          = 'experience_dtm';
    const COL_LIMIT_DTM          = 'limit_dtm';
    const COL_STATUS   = 'status';
    const COL_CANCEL_DTM          = 'cancel_dtm';
    const COL_NOTE   = 'note';
    const COL_GENDER   = 'gender';
    const COL_AGE   = 'age';


    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($voice_cd)
    {
        $data = $this->where(self::COL_VOICE_CD, $voice_cd)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_VOICE_CD  => $data[0]->voice_cd,
                self::COL_RESERVE_CD  => $data[0]->reserve_cd,
                self::COL_HOTEL_CD       => $data[0]->hotel_cd,
                self::COL_MEMBER_CD  => $data[0]->member_cd,
                self::COL_TITLE      => $data[0]->title,
                self::COL_EXPLAIN   => $data[0]->explain,
                self::COL_EXPERIENCE_DTM          => $data[0]->experience_dtm,
                self::COL_LIMIT_DTM          => $data[0]->limit_dtm,
                self::COL_STATUS   => $data[0]->status,
                self::COL_CANCEL_DTM          => $data[0]->cancel_dtm,
                self::COL_NOTE   => $data[0]->note,
                self::COL_GENDER   => $data[0]->gender,
                self::COL_AGE   => $data[0]->age,
            ];
        }
        return null;
    }

    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)->where(self::COL_VOICE_CD, $data[self::COL_VOICE_CD])->update($data);
        if (!$result) {
            return "更新に失敗しました";
        }
        return "";
    }
}
