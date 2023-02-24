<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * 請求先・支払先関連施設
 *
 */
class Billpay extends CommonDBModel
{
    use Traits;

    public function __construct()
    {
        // カラム情報の設定
    }


    /**
     * キーワードから対象となる施設を特定します。
     *
     * @param array $aa_conditions
     * @return object
     */
    public function searchCustomer($aa_conditions)
    {
        try {
            // キーワード
            $a_conditions['keyword1'] = trim($aa_conditions['keyword']);
            $a_conditions['keyword2_1'] = '%' . trim($aa_conditions['keyword']) . '%'; //Invalid parameter numberになるので4つに
            $a_conditions['keyword2_2'] = '%' . trim($aa_conditions['keyword']) . '%';
            $a_conditions['keyword2_3'] = '%' . trim($aa_conditions['keyword']) . '%';
            $a_conditions['keyword2_4'] = '%' . trim($aa_conditions['keyword']) . '%';

            // keywordsで検索
            $s_sql =
            <<< SQL
					select	hotel.hotel_cd,
							hotel.hotel_nm,
							q3.customer_id,
							q3.customer_nm
					from	hotel,
						(
							select	customer.customer_id,
									customer.customer_nm,
									q2.hotel_cd
							from	customer,
								(
									select	customer_hotel.hotel_cd,
											customer_hotel.customer_id
									from	customer_hotel,
										(
											select	customer_hotel.hotel_cd
											from	customer_hotel,
												(
													select	customer_id
													from	customer
													where	null is null
														and	(customer_nm like :keyword2_1)
												) q1
											where	customer_hotel.customer_id = q1.customer_id
											union
											select	hotel_cd
											from	hotel
											where	(hotel_cd       = :keyword1 or
													hotel_nm     like :keyword2_2 or
													hotel_old_nm like :keyword2_3 or
													hotel_kn     like :keyword2_4
												)
										) q1
									where	customer_hotel.hotel_cd = q1.hotel_cd
								) q2
							where	customer.customer_id = q2.customer_id
						) q3
					where	hotel.hotel_cd = q3.hotel_cd
					and	exists(select * from hotel_control where hotel_cd = hotel.hotel_cd and stock_type in(0, 2))
					order by q3.customer_id,
							hotel.hotel_cd
SQL;

            $a_result = DB::select($s_sql, $a_conditions);

            return $a_result;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }



}
