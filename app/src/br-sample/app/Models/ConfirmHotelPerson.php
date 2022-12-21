<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/** 施設管理サイト担当者
 *
 */
class ConfirmHotelPerson extends CommonDBModel
{
    use Traits;

    protected $table = "confirm_hotel_person";

    // カラム
    const COL_HOTEL_CD = "hotel_cd";
    const COL_CONFIRM_DTM   = "confirm_dtm";
    const COL_HOTEL_PERSON_EMAIL_CHECK   = "hotel_person_email_check";
    const COL_CUSTOMER_EMAIL_CHECK = "customer_email_check";


    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $hotelCd = new ValidationColumn();
        $hotelCd->setColumnName(self::COL_HOTEL_CD, '施設コード');
        $confirmDtm = new ValidationColumn();
        $confirmDtm->setColumnName(self::COL_CONFIRM_DTM, '確認日時');
        $hotelPersonEmailCheck = new ValidationColumn();
        $hotelPersonEmailCheck->setColumnName(self::COL_HOTEL_PERSON_EMAIL_CHECK, '施設担当者メール確認');
        $customerEmailCheck = new ValidationColumn();
        $customerEmailCheck->setColumnName(self::COL_CUSTOMER_EMAIL_CHECK, '精算先担当者メール確認');
        parent::setColumnDataArray([$hotelCd, $confirmDtm, $hotelPersonEmailCheck, $customerEmailCheck]);

        // 施設コード
        $hotelCd->require(); // 必須入力チェック
        $hotelCd->notHalfKana(); // 半角カナチェック
        $hotelCd->length(0, 10); // 長さチェック

        // 確認日時
        $confirmDtm->correctDate(); // 日付チェック

        // 施設担当者メール確認
        $hotelPersonEmailCheck->length(0, 1); // 長さチェック
        $hotelPersonEmailCheck->intOnly(); // 数字：数値チェック

        // 精算先担当者メール確認
        $customerEmailCheck->length(0, 1); // 長さチェック
        $customerEmailCheck->intOnly(); // 数字：数値チェック
    }


    /**
     * 主キーで取得
     */
    public function selectByKey($hotelCd)
    {
        $data = $this->where(self::COL_HOTEL_CD, $hotelCd)->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_HOTEL_CD  => $data[0]->hotel_cd,
                self::COL_CONFIRM_DTM   => $data[0]->confirm_dtm,
                self::COL_HOTEL_PERSON_EMAIL_CHECK   => $data[0]->hotel_person_email_check,
                self::COL_CUSTOMER_EMAIL_CHECK => $data[0]->customer_email_check
            ];
        }
        return null;
    }

    /**  キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)->where(self::COL_HOTEL_CD, $data[self::COL_HOTEL_CD])->update($data);
        if (!$result) {
            return "更新に失敗しました";
        }
        return "";
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
