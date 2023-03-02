<?php

namespace App\Services;

use App\Common\Traits;
use App\Models\DenyList;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelInsuranceWeather;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStatus;
use App\Models\HotelSystemVersion;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;
use Exception;

class MastService
{
    use Traits;

    // モデルにするかサービスにするか
    // 元ソースではMastモデルだが、SQL文をそれぞれ直接書いてあるのでServiceでいいのでは？でこちらを作成
    // ディレクトリ構造含め、これで問題ないか？


    // 都道府県IDを取得
    //
    // aa_conditions
    //   pref_cd    都道府県CD
    //
    public function getPrefId($aa_conditions = [])
    {
        try {
            $s_sql =
            <<<SQL
					select	pref_id
					from	mast_pref
					where	null is null
						and	pref_cd = :pref_cd
SQL;

            // データの取得
            $a_row = DB::select($s_sql, $aa_conditions);

            return $a_row[0]['pref_id'];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    // 市IDを取得
    // aa_conditions
    //   city_cd 都道府県ID
    //
    public function getCityId($aa_conditions = [])
    {
        try {

            $s_sql =
            <<<SQL
					select	city_id
					from	mast_city
					where	null is null
						and	city_cd = :city_cd
SQL;

            // データの取得
            $_oracle = _Oracle::getInstance();
            $a_row = $_oracle->find_by_sql($s_sql, $aa_conditions);

            return $a_row[0]['city_id'];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 区IDを取得
    // aa_conditions
    //   ward_cd 区CD
    //
    public function getWardId($aa_conditions = [])
    {
        try {

            $s_sql =
            <<<SQL
					select	ward_id
					from	mast_ward
					where	null is null
						and	ward_cd = :ward_cd
SQL;

            // データの取得
            $_oracle = _Oracle::getInstance();
            $a_row = $_oracle->find_by_sql($s_sql, $aa_conditions);

            return $a_row[0]['ward_id'];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }



        // wardzoneIdに紐づく区マスタを取得
    //   aa_conditions
    //   wardzone_id   地区ID
    //
    public function getWardZoneWards($aa_conditions = [])
    {
        try {
            // 市ID
            $s_wardzone_id = '';
            if (!$this->is_empty($aa_conditions['wardzone_id'])) {
                $s_wardzone_id = '	and	mast_wardzone_detail.wardzone_id = :wardzone_id';
            }

            $s_sql =
            <<<SQL
					select	mast_ward.ward_id,
							mast_ward.pref_id,
							mast_ward.city_id,
							mast_ward.ward_cd,
							mast_ward.ward_nm,
							mast_ward.city_ward_nm,
							mast_ward.pref_city_ward_nm,
							mast_ward.order_no,
							mast_ward.delete_ymd
					from	mast_ward,
						(
							select	mast_wardzone_detail.ward_id
							from	mast_wardzone_detail
							where	null is null
								{$s_wardzone_id}
						) q1
					where	mast_ward.ward_id = q1.ward_id
SQL;

            if ($aa_conditions['wardzone_id'] == 8) {
                $a_row[] = ['pref_id' => 13];
            } elseif ($aa_conditions['wardzone_id'] == 9) {
                $a_row[] = ['pref_id' => 27];
            } else {
                $a_row = DB::select($s_sql, $aa_conditions);
            }

            // データの取得
            return [
                'values'     => $a_row
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    // 地域が属している都道府県市区町村を取得します。
    //   aa_conditions
    //     area_id   エリアID
    public function getMastAreaMatchReverse($aa_conditions)
    {
        try {
            // エリアID
            if (!$this->is_empty($aa_conditions['area_id'])) {
                $s_area_id = '	and	mast_area_match.area_id = :area_id';
            }

            $s_sql =
            <<<SQL
					select	pref_id,
							city_id,
							ward_id
					from	mast_area_match
					where	null is null
						{$s_area_id}
SQL;

            // データの取得

            $a_row = DB::select($s_sql, $aa_conditions);
            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                if (!$this->is_empty($a_row[$n_cnt]['pref_id'])) {
                    $a_result['pref_id'] = $a_row[$n_cnt]['pref_id'];
                } elseif (!$this->is_empty($a_row[$n_cnt]['city_id'])) {
                    $a_result['city_id'] = $a_row[$n_cnt]['city_id'];
                } elseif (!$this->is_empty($a_row[$n_cnt]['ward_id'])) {
                    $a_result['ward_id'] = $a_row[$n_cnt]['ward_id'];
                }
            }

            return [
                'values'     => $a_result,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
