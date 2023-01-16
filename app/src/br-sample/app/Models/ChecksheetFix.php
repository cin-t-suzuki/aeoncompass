<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChecksheetFix extends CommonDBModel
{
    use Traits;

    protected $table = "checksheet_fix";

    // カラム
    const COL_CHECKSHEET_YM  = "checksheet_ym";
    const COL_HOTEL_CD  = "hotel_cd";
    const COL_FIX_STATUS  = "fix_status";
    const COL_FIX_DTM  = "fix_dtm";
    const COL_FIXED_DTM  = "fixed_dtm";

    public function __construct()
    {
        // カラム情報の設定
        $colCheckSheetYm = new ValidationColumn();
        $colCheckSheetYm->setColumnName(self::COL_CHECKSHEET_YM, '処理年月');
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName(self::COL_HOTEL_CD, '施設コード');
        $colFixStatus = new ValidationColumn();
        $colFixStatus->setColumnName(self::COL_FIX_STATUS, '確定テータス');
        $colFixDtm = new ValidationColumn();
        $colFixDtm->setColumnName(self::COL_FIX_DTM, '確定日時');
        $colFixedDtm = new ValidationColumn();
        $colFixedDtm->setColumnName(self::COL_FIXED_DTM, '検収確定日');

        // バリデーションルール
        // 処理年月
        $colCheckSheetYm->require();     // 必須入力チェック
        $colCheckSheetYm->correctDate(); // 日付チェック

        // 施設コード
        $colHotelCd->require();     // 必須入力チェック
        $colHotelCd->notHalfKana(); // 半角カナチェック
        $colHotelCd->length(0, 10); // 長さチェック

        // 確定テータス
        $colFixStatus->require();     // 必須入力チェック
        $colFixStatus->length(0, 1); // 長さチェック
        $colFixStatus->intOnly(); // 数字：数値チェック
            //パターンチェックとカラムの説明は不要でいいか？？

        // 確定日時
        $colCheckSheetYm->correctDate(); // 日付チェック

        parent::setColumnDataArray([
            $colCheckSheetYm, $colHotelCd, $colFixStatus, $colFixDtm, $colFixedDtm
        ]);
    }

    // 処理年月と施設CDでの取得
    public function selectByWKey($checksheet_ym, $hotel_cd)
    {
        $data = $this->where([
                self::COL_CHECKSHEET_YM => $checksheet_ym,
                self::COL_HOTEL_CD => $hotel_cd
            ])->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_CHECKSHEET_YM => $data[0]->checksheet_ym,
                self::COL_HOTEL_CD => $data[0]->hotel_cd,
                self::COL_FIX_STATUS => $data[0]->fix_status,
                self::COL_FIX_DTM => $data[0]->fix_dtm,
                self::COL_FIXED_DTM => $data[0]->fixed_dtm
            ];
        }
        return [];
    }

    /**  複合主キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByWKey($con, $requestChecksheetFix)
    {
        $result = $con->table($this->table)->where([
                self::COL_CHECKSHEET_YM => $requestChecksheetFix['checksheet_ym'],
                self::COL_HOTEL_CD => $requestChecksheetFix['hotel_cd']
            ])->update($requestChecksheetFix);
        return  $result;
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
