<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HikariAccount extends CommonDBModel
{
    use Traits;

    // 光通信ログイン情報

    protected $table = "hikari_account";

    // カラム
    const COL_ID  = "id";
    const COL_ACCOUNT_ID  = "account_id";
    const COL_PASSWORD  = "password";
    const COL_ACCEPT_STATUS  = "accept_status";
    const COL_NOTE  = "note";


    public function __construct()
    {
        // カラム情報の設定
        $colId = new ValidationColumn();
        $colId->setColumnName(self::COL_ID, 'ID');
        $colAccountId = new ValidationColumn();
        $colAccountId->setColumnName(self::COL_ACCOUNT_ID, 'アカウントID');
        $colPassword = new ValidationColumn();
        $colPassword->setColumnName(self::COL_PASSWORD, 'パスワード');
        $colAcceptStatus = new ValidationColumn();
        $colAcceptStatus->setColumnName(self::COL_ACCEPT_STATUS, 'ステータス');
        $colNote = new ValidationColumn();
        $colNote->setColumnName(self::COL_NOTE, '備考');


        // バリデーションルール
        // ID
        $colId->require();     // 必須入力チェック
        $colId->length(0, 8); // 長さチェック
        $colId->intOnly(); // 数字：数値チェック

        // アカウントID
        $colAccountId->notHalfKana(); // 半角カナチェック
        $colAccountId->length(0, 20); // 長さチェック

        // パスワード
        $colPassword->notHalfKana(); // 半角カナチェック
        $colPassword->length(0, 64); // 長さチェック

        // ステータス
        $colAcceptStatus->length(0, 1); // 長さチェック
        $colAcceptStatus->intOnly(); // 数字：数値チェック

        // 備考
        $colNote->notHalfKana(); // 半角カナチェック
        $colNote->length(0, 1000); // 長さチェック

        parent::setColumnDataArray([
            $colId, $colAccountId, $colPassword, $colAcceptStatus, $colNote
        ]);
    }

    /**
     * 主キーで取得
     * @param string $id
     * @return array|null
     */
    public function selectByKey($id)
    {
        $data = $this->where(self::COL_ID, $id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_ID  => $data[0]->id,
                self::COL_ACCOUNT_ID  => $data[0]->account_id,
                self::COL_PASSWORD  => $data[0]->password,
                self::COL_ACCEPT_STATUS  => $data[0]->accept_status,
                self::COL_NOTE  => $data[0]->note,
            ];
        }
        return null;
    }
}
