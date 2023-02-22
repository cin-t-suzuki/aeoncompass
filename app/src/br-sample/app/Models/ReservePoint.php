<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservePoint extends CommonDBModel
{
    use Traits;

    protected $table = "reserve_point";

    // カラム
    const COL_RESERVE_CD  = "reserve_cd";
    const COL_ISSUE_POINT_RATE  = "issue_point_rate";
    const COL_POINT_STATUS  = "point_status";
    const COL_AMOUNT  = "amount";
    const COL_MIN_POINT  = "min_point";
    const COL_MAX_POINT  = "max_point";
    const COL_CANCEL_PRIORITY  = "cancel_priority";
    const COL_ISSUE_POINT_RATE_OUR  = "issue_point_rate_our";


    public function __construct()
    {
        // カラム情報の設定
            // カラム情報の設定
            $colReserveCd = new ValidationColumn();
            $colReserveCd->setColumnName(self::COL_RESERVE_CD, '予約コード');
            $colIssuePointRate = new ValidationColumn();
            $colIssuePointRate->setColumnName(self::COL_ISSUE_POINT_RATE, '獲得ポイント率');
            $colIssuePointRateOur = new ValidationColumn();
            $colIssuePointRateOur->setColumnName(self::COL_ISSUE_POINT_RATE_OUR, '獲得ポイント当社負担率');
            $colPointStatus = new ValidationColumn();
            $colPointStatus->setColumnName(self::COL_POINT_STATUS, 'ポイント利用可否');
            $colAmount = new ValidationColumn();
            $colAmount->setColumnName(self::COL_AMOUNT, '増量単位');
            $colMinPoint = new ValidationColumn();
            $colMinPoint->setColumnName(self::COL_MIN_POINT, '最低利用ポイント');
            $colMaxPoint = new ValidationColumn();
            $colMaxPoint->setColumnName(self::COL_MAX_POINT, '最大利用ポイント');
            $colCancelPrioirty = new ValidationColumn();
            $colCancelPrioirty->setColumnName(self::COL_CANCEL_PRIORITY, '優先設定');


            // バリデーションルール
            // 予約コード
            $colReserveCd->require();     // 必須入力チェック
            $colReserveCd->notHalfKana(); // 半角カナチェック
            $colReserveCd->length(0, 14); // 長さチェック

            // 獲得ポイント率
            $colIssuePointRate->length(0, 5); // 長さチェック
            $colIssuePointRate->rateOnly(); // 数字：数値チェック

            // 獲得ポイント当社負担率
            $colIssuePointRateOur->length(0, 5); // 長さチェック
            $colIssuePointRateOur->rateOnly(); // 数字：数値チェック

            // ポイント利用可否
            $colPointStatus->length(0, 1); // 長さチェック
            $colPointStatus->intOnly(); // 数字：数値チェック

            // 増量単位
            $colAmount->length(0, 4); // 長さチェック
            $colAmount->intOnly(); // 数字：数値チェック

            // 最低利用ポイント
            $colMinPoint->length(0, 5); // 長さチェック
            $colMinPoint->intOnly(); // 数字：数値チェック

            // 最大利用ポイント
            $colMaxPoint->length(0, 6); // 長さチェック
            $colMaxPoint->notHalfKana(); // 半角カナチェック

            // 優先設定
            $colCancelPrioirty->require();     // 必須入力チェック
                //パターンチェックは不要？

            parent::setColumnDataArray([
                $colReserveCd, $colIssuePointRate, $colIssuePointRateOur, $colPointStatus,
                $colAmount, $colMinPoint, $colMaxPoint, $colCancelPrioirty
            ]);
    }

    /**
     * 主キーで取得
     *
     * @param string $reserve_cd
     * @return array|null
     */
    public function selectByKey($reserve_cd)
    {
        $data = $this->where(self::COL_RESERVE_CD, $reserve_cd)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_RESERVE_CD  => $data[0]->reserve_cd,
                self::COL_ISSUE_POINT_RATE  => $data[0]->issue_point_rate,
                self::COL_POINT_STATUS  => $data[0]->point_status,
                self::COL_AMOUNT  => $data[0]->amount,
                self::COL_MIN_POINT  => $data[0]->min_point,
                self::COL_MAX_POINT  => $data[0]->max_point,
                self::COL_CANCEL_PRIORITY  => $data[0]->cancel_priority,
                self::COL_ISSUE_POINT_RATE_OUR  => $data[0]->issue_point_rate_our
            ];
        }
        return null;
    }

}
