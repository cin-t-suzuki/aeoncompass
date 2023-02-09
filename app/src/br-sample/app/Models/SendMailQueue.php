<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Util\Models_Cipher;
use App\Common\Traits;

/**
 *
 */
class SendMailQueue extends CommonDBModel
{
    use Traits;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'send_mail_queue';

    // カラム
    const COL_MAIL_CD = 'mail_cd';
    const COL_FROM_MAIL = 'from_mail';
    const COL_FROM_NM = 'from_nm';
    const COL_BCC_MAIL = 'bcc_mail';
    const COL_TO_MAIL = 'to_mail';
    const COL_RETURN_PATH = 'return_path';
    const COL_FREE_FIELD = 'free_field';
    const COL_SUBJECT = 'subject';
    const COL_CONTENTS = 'contents';
    const COL_CIPHER = 'cipher';
    const COL_START_DTM = 'start_dtm';
    const COL_SEND_DTM = 'send_dtm';
    const COL_SEND_STATUS = 'send_status';

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // // カラム情報の設定
    }

    /**
     * 主キーで取得
     */
    public function selectByWKey($mail_cd, $send_dtm)
    {
        $data = $this->where([
            self::COL_MAIL_CD => $mail_cd,
            self::COL_SEND_DTM => $send_dtm
        ])->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_MAIL_CD => $data[0]->mail_cd,
                self::COL_FROM_MAIL => $data[0]->from_mail,
                self::COL_FROM_NM => $data[0]->from_nm,
                self::COL_BCC_MAIL => $data[0]->bcc_mail,
                self::COL_TO_MAIL => $data[0]->to_mail,
                self::COL_RETURN_PATH => $data[0]->return_path,
                self::COL_FREE_FIELD => $data[0]->free_field,
                self::COL_SUBJECT => $data[0]->subject,
                self::COL_CONTENTS => $data[0]->contents,
                self::COL_CIPHER => $data[0]->cipher,
                self::COL_START_DTM => $data[0]->start_dtm,
                self::COL_SEND_DTM => $data[0]->send_dtm,
                self::COL_SEND_STATUS => $data[0]->send_status
            ];
        }
        return [];
    }
}
