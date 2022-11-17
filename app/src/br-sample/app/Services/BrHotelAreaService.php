<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;

class BrHotelAreaService
{

    // 定数の定義
    const IDX_AREA_LARGE  = 0; // 大エリア
    const IDX_AREA_PREF   = 1; // 都道府県
    const IDX_AREA_MIDDLE = 2; // 中エリア
    const IDX_AREA_SMALL  = 3; // 小エリア

    // メンバ変数の定義
    protected $o_box;
    protected $o_oracle;
    protected $o_hotel_area;
    protected $o_validations;
    protected $s_hotel_cd;
    protected $a_mast_areas;
    protected $a_hotel_area_default;
    protected $a_attributes;
    protected $a_hotel_info;
    protected $a_hotel_areas;
    protected $n_active_entry_no;


    public function __construct()
    {
        try {
            // boxの生成
            $o_controller = Zend_Controller_Front::getInstance();
            $this->o_box = &$o_controller->getPlugin('Box')->box;

            // インスタンス生成
            $this->o_oracle      = _Oracle::getInstance();
            $this->o_validations = Validations::getInstance($this->o_box);
            $this->o_hotel_area  = Hotel_Area::getInstance();

            // 初期化
            $this->s_hotel_cd           = null;
            $this->n_active_entry_no    = null;
            $this->a_mast_areas         = array();
            $this->this->a_hotel_info   = array();
            $this->a_hotel_area_default = array();
            $this->a_attributes         = array();
            $this->a_hotel_areas        = array();

            // 地域マスタデータ取得
            $this->_make_mast_areas();
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Undocumented function
     *
     * @param string $hotelCd
     * @return stdClass
     */
    public function getHotelInfo($hotelCd): stdClass
    {
        $sql = <<<SQL
            select
                q3.hotel_cd,
                case
                    when q3.hotel_category = 'a' then 'カプセルホテル'
                    when q3.hotel_category = 'b' then 'ビジネスホテル'
                    when q3.hotel_category = 'c' then 'シティホテル'
                    when q3.hotel_category = 'j' then '旅館'
                    else ''
                end as hotel_category,
                q3.hotel_nm,
                q3.postal_cd,
                q3.address,
                q3.tel,
                q3.fax,
                q3.pref_nm,
                q3.city_nm,
                mw.ward_id -- nm じゃなくて大丈夫？ MEMO:
            from
                mast_ward mw
                right outer join (
                    select
                        q2.hotel_cd,
                        q2.hotel_category,
                        q2.hotel_nm,
                        q2.postal_cd,
                        q2.ward_id,
                        q2.address,
                        q2.tel,
                        q2.fax,
                        q2.pref_nm,
                        mc.city_nm
                    from
                        mast_city mc
                        inner join (
                            select
                                q1.hotel_cd,
                                q1.hotel_category,
                                q1.hotel_nm,
                                q1.postal_cd,
                                q1.city_id,
                                q1.ward_id,
                                q1.address,
                                q1.tel,
                                q1.fax,
                                mp.pref_nm
                            from
                                mast_pref mp
                                inner join (
                                    select
                                        h.hotel_cd,
                                        h.hotel_category,
                                        h.hotel_nm,
                                        h.postal_cd,
                                        h.pref_id,
                                        h.city_id,
                                        h.ward_id,
                                        h.address,
                                        h.tel,
                                        h.fax
                                    from
                                        hotel h
                                    where
                                        h.hotel_cd = :hotel_cd
                                ) q1 on q1.pref_id = mp.pref_id
                        ) q2 on q2.city_id = mc.city_id
                ) q3 on q3.ward_id = mw.ward_id
        SQL;
        /* こういう SQL では違う？
            $sql = <<<SQL
                select
                    hotel.hotel_cd,
                    case
                        when hotel.hotel_category = 'a' then 'カプセルホテル'
                        when hotel.hotel_category = 'b' then 'ビジネスホテル'
                        when hotel.hotel_category = 'c' then 'シティホテル'
                        when hotel.hotel_category = 'j' then '旅館'
                        else ''
                    end as hotel_category,
                    hotel.hotel_nm,
                    hotel.postal_cd,
                    hotel.address,
                    hotel.tel,
                    hotel.fax,
                    mast_pref.pref_nm,
                    mast_city.city_nm,
                    mast_ward.ward_id -- nm じゃなくて大丈夫？ MEMO:
                from
                    hotel
                    left outer join mast_pref on hotel.pref_id = mast_pref.pref_id
                    left outer join mast_city on hotel.city_id = mast_city.city_id
                    left outer join mast_ward on hotel.city_id = mast_ward.ward_id
                where
                    hotel.hotel_cd = :hotel_cd
            SQL;
        */

        $resultHotelInfo = DB::select($sql, ['hotel_cd' => $hotelCd]);
        if (count($resultHotelInfo) > 0) {
            return $resultHotelInfo[0];
        } else {
            // データがヒットしないときは、必要なプロパティを設定した空の stdClass を返す
            // MEMO: 設定しておかないと、 undefined array key で処理が止まる
            return (object)[
                'hotel_cd'  => null,
                'hotel_nm'  => null,
                'postal_cd' => null,
                'pref_nm'   => null,
                'address'   => null,
                'tel'       => null,
                'fax'       => null,
            ];
        }
    }

    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\HotelArea.php > _make_hotel_areas()
     * 移植元では、どこかで class property に設定(make) して、呼び出し時は class property を参照している
     *
     * @param string $hotelCd
     * @return array
     */
    public function getHotelAreas($hotelCd): array
    {
        $a_conditions = [
            'hotel_cd' => $hotelCd,
        ];
        $a_area_detail_large  = [];
        $a_area_detail_pref   = [];
        $a_area_detail_middle = [];
        $a_area_detail_small  = [];
        $a_sort_keys          = [];
        $a_area_key_names     = [
            0 => 'area_j',
            1 => 'area_l',
            2 => 'area_p',
            3 => 'area_m',
            4 => 'area_s'
        ];

        $s_sql = <<< SQL
            select
                q1.hotel_cd,
                q1.entry_no,
                ma.area_id,
                ma.area_type,
                ma.order_no
            from
                mast_area ma,
                (
                    select
                        ha.hotel_cd,
                        ha.entry_no,
                        ha.area_id,
                        ha.area_type
                    from
                        hotel_area ha
                    where
                        ha.hotel_cd = :hotel_cd
                ) q1
            where
                ma.area_id = q1.area_id
                and ma.area_type = q1.area_type
            order by
                ma.order_no asc
        SQL;

        // $a_temp_hotel_areas = nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), []);
        $a_temp_hotel_areas = DB::select($s_sql, $a_conditions);

        // 整形
        foreach ($a_temp_hotel_areas as $a_temp_hotel_area) {
            $a_hotel_areas[$a_temp_hotel_area->entry_no]['hotel_cd'] = $a_temp_hotel_area->hotel_cd;
            $a_hotel_areas[$a_temp_hotel_area->entry_no]['entry_no'] = $a_temp_hotel_area->entry_no;
            $a_hotel_areas[$a_temp_hotel_area->entry_no][$a_area_key_names[$a_temp_hotel_area->area_type]] = $a_temp_hotel_area->area_id;

            // ソートキーを作成
            if (!array_key_exists($a_temp_hotel_area->entry_no, $a_sort_keys)) {
                $a_sort_keys[$a_temp_hotel_area->entry_no] = str_pad($a_temp_hotel_area->order_no, 10, 0, STR_PAD_LEFT);
            } else {
                // MEMO: 現行のミスだと思われるため変更した。
                // $a_sort_keys[$a_temp_hotel_area->entry_no] = $a_sort_keys[$a_temp_hotel_area->order_no] . str_pad($a_temp_hotel_area->order_no, 10, 0, STR_PAD_LEFT);
                $a_sort_keys[$a_temp_hotel_area->entry_no] .= str_pad($a_temp_hotel_area->order_no, 10, 0, STR_PAD_LEFT);
            }
        }

        // ソートキーを整形
        // ※小エリアが存在するものとしないもので10桁の差異が生まれるのでそれを解消
        // 大・都道府県・中・小エリア各10桁ずつの合計40桁のコードをソートキーとする
        foreach ($a_sort_keys as $key => $value) {
            if (40 > strlen($value)) {
                $a_sort_keys[$key] = $a_sort_keys[$key] . str_pad('', 40 - strlen($value), 0, STR_PAD_LEFT);
            }

            $a_hotel_areas[$key]['sort'] = $a_sort_keys[$key];
        }

        // エリアマスタの表示順に応じたソートを行う
        array_multisort($a_sort_keys, SORT_STRING, $a_hotel_areas);

        // 地域名称を設定
        foreach ($a_hotel_areas as $key => $a_hotel_area) {
            // 地域IDから地域情報を取得
            $a_area_detail_large  = $this->getArea($a_hotel_area['area_l']);
            $a_area_detail_pref   = $this->getArea($a_hotel_area['area_p']);
            $a_area_detail_middle = $this->getArea($a_hotel_area['area_m']);

            // 取得した地域名を設定
            $a_hotel_areas[$key]['area_nm_l'] = $a_area_detail_large->area_nm;  // 大エリア
            $a_hotel_areas[$key]['area_nm_p'] = $a_area_detail_pref->area_nm;   // 都道府県
            $a_hotel_areas[$key]['area_nm_m'] = $a_area_detail_middle->area_nm; // 中エリア

            // 小エリアは登録されているときのみ名称を取得する
            if (array_key_exists('area_s', $a_hotel_area)) {
                $a_area_detail_small = $this->getArea($a_hotel_area['area_s']);
                $a_hotel_areas[$key]['area_nm_s'] = $a_area_detail_small->area_nm; // 小エリア
            } else {
                $a_hotel_areas[$key]['area_nm_s'] = null;
            }
        }

        return $a_hotel_areas; // MEMO: 移植元では、 return せずに class property に代入している
    }

    /**
     * Undocumented function
     *
     * @param string $an_area_id
     * @return stdClass
     */
    private function getArea($an_area_id): stdClass
    {
        // 地域IDがNull
        if (is_null($an_area_id)) {
            return (object)['area_nm' => null];
        }
        // 地域マスター情報から一致する地域情報を探す
        $mastAreas = $this->getMastAreas();
        foreach ($mastAreas as $a_area) {
            // 見つかった場合は対象の情報を返却する
            if ((int)$a_area->area_id === (int)$an_area_id) {
                return $a_area;
            }
        }
        // 見つからなかった場合
        return (object)['area_nm' => null];
    }

    public function getHotelAreaDefault($an_entry_no = null)
    {
        // 登録番号が指定されているときは地域情報を取得し直す
        if (!is_empty($an_entry_no)) {
            $this->makeHotelAreaDefault($an_entry_no);
        }
        return $this->a_hotel_area_default;
    }

    private function makeHotelAreaDefault($an_entry_no)
    {

        // 初期化
        $n_temp_parent_area_id = null;
        $a_area_detail_large   = array();
        $a_area_detail_pref    = array();
        $a_area_detail_middle  = array();
        $a_area_detail_small   = array();

        // エラーチェック
        if (!$this->_is_set_hotel_cd()) {
            throw new \Exception('施設コードが設定されていません。');
        }

        // 登録番号が未指定の場合
        if (is_empty($an_entry_no)) {
            // 都道府県エリアの取得
            $a_area_detail_pref  = $this->getArea($this->convert_id_pref_to_area($this->o_box->user->hotel['pref_id']));
            $this->a_hotel_area_default['area_pref'] = $a_area_detail_pref['area_id'];

            // 大エリアの取得
            $a_area_detail_large = $this->getArea($a_area_detail_pref['parent_area_id']);
            $this->a_hotel_area_default['area_large'] = $a_area_detail_large['area_id'];

            return;
        }

        foreach (nvl($this->a_hotel_areas, array()) as $a_hotel_area) {
            // 指定の登録番号をもつ地域情報を設定
            if ((int)$an_entry_no === (int)$a_hotel_area['entry_no']) {
                // 大エリアの取得
                $this->a_hotel_area_default['area_large'] = $a_hotel_area['area_l'];

                // 都道府県エリアの取得
                $this->a_hotel_area_default['area_pref'] = $a_hotel_area['area_p'];

                // 中エリアの取得
                $this->a_hotel_area_default['area_middle'] = $a_hotel_area['area_m'];

                // 小エリアの取得
                if (!is_empty($a_hotel_area['area_s'])) {
                    $this->a_hotel_area_default['area_small'] = $a_hotel_area['area_s'];
                }

                return;
            }
        }
    }
    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\HotelArea.php > _make_mast_areas()
     * 移植元では、どこかで class property に設定(make) して、呼び出し時は class property を参照している
     *
     * MEMO: MastArea model で置き換えられるか。
     *
     * @return stdClass[]
     */
    private function getMastAreas()
    {
        $sql = <<< SQL
            select
                area_id,
                parent_area_id,
                area_nm,
                area_type
            from
                mast_area
            order by
                order_no asc
        SQL;
        // $a_rows = $this->o_oracle->find_by_sql($sql, array());
        $a_rows = DB::select($sql);

        if (!is_null($a_rows)) {
            $a_mast_areas = $a_rows;
        } else {
            $a_mast_areas = [];
        }

        return $a_mast_areas; // MEMO: 移植元では、 return せずに class property に代入している
    }
    // TODO: to be deleted
    // public function dummyHotelArea($targetCd)
    // {
    //     return (object)[
    //         'entry_no'      => 'entry_no_val' . Str::random(5),
    //         'area_nm_l'     => 'area_nm_l_val' . Str::random(5),
    //         'area_nm_m'     => 'area_nm_m_val' . Str::random(5),
    //         'area_nm_p'     => 'area_nm_p_val' . Str::random(5),
    //         'area_nm_s'     => 'area_nm_s_val' . Str::random(5),
    //         'hotel_cd'      => $targetCd,
    //     ];
    // }
}
