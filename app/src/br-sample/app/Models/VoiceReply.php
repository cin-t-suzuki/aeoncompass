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
class VoiceReply extends CommonDBModel
{
    use Traits;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'voice_reply';

    // カラム
    const COL_HOTEL_CD       = 'hotel_cd';
    const COL_VOICE_CD  = 'voice_cd';
    const COL_REPLY_TYPE      = 'reply_type';
    const COL_ANSWER   = 'answer';
    const COL_REPLY_DRM          = 'reply_dtm';

    /**
     * コンストラクタ
     */
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

    /**
     * 復号主キーで取得
    */
    public function selectByWKey($hotelCd, $voiceCd)
    {
        $data = $this->where([self::COL_HOTEL_CD => $hotelCd, self::COL_VOICE_CD => $voiceCd])->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_HOTEL_CD => $data[0]->hotel_cd,
                self::COL_VOICE_CD => $data[0]->voice_cd,
                self::COL_REPLY_TYPE  => $data[0]->reply_type,
                self::COL_ANSWER => $data[0]->answer,
                self::COL_REPLY_DRM  => $data[0]->reply_dtm,
            ];
        }
        return null;
    }

    /**  複合主キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByWKey($con, $voiceReplyData)
    {
        $result = $con->table($this->table)->where([
                self::COL_HOTEL_CD => $voiceReplyData['hotel_cd'],
                self::COL_VOICE_CD => $voiceReplyData['voice_cd']
            ])->update($voiceReplyData);
        return  $result;
    }
}
