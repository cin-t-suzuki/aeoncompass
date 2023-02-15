<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 請求先・支払先関連施設
 *
 */
class CustomerHotel extends CommonDBModel
{
    use Traits;

    protected $table = "customer_hotel";
    // カラム
    const COL_HOTEL_CD = "hotel_cd";
    const COL_CUSTOMER_ID = "customer_id";

    /**
     * コンストラクタ
     */
    public function __construct() //publicでいいか？
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName(self::COL_HOTEL_CD, '施設コード');
        $colCustomerId = new ValidationColumn();
        $colCustomerId->setColumnName(self::COL_CUSTOMER_ID, '請求先・支払先ID');

        // 施設コード
        $colHotelCd->require();      // 必須入力チェック
        $colHotelCd->length(0, 10);  // 長さチェック
        $colHotelCd->notHalfKana();  // 半角カナチェック

        // 支払先ID
        $colCustomerId->length(0, 10);  // 長さチェック
        $colCustomerId->intOnly();         // 数字：数値チェック

        parent::setColumnDataArray([$colHotelCd, $colCustomerId]);
    }

    /**
     * 主キーで取得
     *
     * @param string $hotelCd
     * @return array
     */
    public function selectByKey($hotelCd)
    {
        $data = $this->where(self::COL_HOTEL_CD, $hotelCd)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_HOTEL_CD => $data[0]->hotel_cd,
                self::COL_CUSTOMER_ID => $data[0]->customer_id
            ];
        }
        return null;
    }

    /** 請求先・支払先施設データ
     * as_hotel_cd 請求先・支払先施設データの施設番号
     *
     * @param [type] $as_hotel_cd
     * @return array
     */
    public function getCustomer($as_hotel_cd)
    {
        $s_sql = <<<SQL
					select	customer.customer_id,
							customer.customer_nm
					from	customer,
						(
							select	customer_id
							from	customer_hotel
							where	hotel_cd = :hotel_cd
						) q1
					where	customer.customer_id = q1.customer_id
SQL;

        $data = DB::select($s_sql, ['hotel_cd' => $as_hotel_cd]);

        $result = [];
        if (!is_null($data) && count($data) > 0) {
            foreach ($data as $row) {
                $result[] = [
                    self::COL_CUSTOMER_ID => $row->customer_id,
                    'customer_nm' => $row->customer_nm
                ];
            }
        }
        return ['values' => $result];
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
}
