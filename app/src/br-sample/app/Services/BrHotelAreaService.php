<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\HotelArea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrHotelAreaService
{

    // 定数の定義
    const IDX_AREA_LARGE  = 0; // 大エリア
    const IDX_AREA_PREF   = 1; // 都道府県
    const IDX_AREA_MIDDLE = 2; // 中エリア
    const IDX_AREA_SMALL  = 3; // 小エリア

    /**
     * 施設情報の取得
     *
     * @param string $hotelCd
     * @return \stdClass
     */
    public function getHotelInfo($hotelCd): \stdClass
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
     * 施設・地域情報一覧の取得
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
        $a_hotel_areas = [];
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
     * 地域IDから対象の地域情報を取得
     *
     * @param string $an_area_id
     * @return \stdClass
     */
    private function getArea($an_area_id): \stdClass
    {
        // 地域IDがNull
        if (is_null($an_area_id)) {
            return (object)[
                'area_id' => null,
                'parent_area_id' => null,
                'area_nm' => null,
                'area_type' => null,
            ];
        }
        // 地域マスター情報から一致する地域情報を探す
        // HACK: 都度 mast_area model から検索したほうがよいか
        $mastAreas = $this->getMastAreas();
        foreach ($mastAreas as $a_area) {
            // 見つかった場合は対象の情報を返却する
            if ((int)$a_area->area_id === (int)$an_area_id) {
                return $a_area;
            }
        }
        // 見つからなかった場合
        return (object)[
            'area_id' => null,
            'parent_area_id' => null,
            'area_nm' => null,
            'area_type' => null,
        ];
    }

    /**
     * 対象施設の地域情報を取得
     *
     * @param string $hotelCd
     * @param string $an_entry_no
     * @return array
     */
    public function getHotelAreaDefault($hotelCd, $an_entry_no): array
    {
        // 初期化
        $a_hotel_area_default = [
            'area_large'    => -1,
            'area_pref'     => -1,
            'area_middle'   => -1,
            'area_small'    => -1,
        ];

        // 登録番号が未指定の場合
        // ホテルの所在都道府県と、その属する大エリアをセットして返す
        if (is_null($an_entry_no)) {
            // 都道府県エリアの取得
            $prefId = Hotel::find($hotelCd)->pref_id;
            $a_area_detail_pref = $this->getArea($this->convertIdPrefToArea($prefId));
            $a_hotel_area_default['area_pref'] = $a_area_detail_pref->area_id;

            // 大エリアの取得
            $a_area_detail_large = $this->getArea($a_area_detail_pref->parent_area_id);
            $a_hotel_area_default['area_large'] = $a_area_detail_large->area_id;

            return $a_hotel_area_default;
        }

        $a_hotel_areas = $this->getHotelAreas($hotelCd);

        foreach ($a_hotel_areas as $a_hotel_area) {
            // 指定の登録番号をもつ地域情報を設定
            if ((int)$an_entry_no === (int)$a_hotel_area['entry_no']) {
                // 大エリアの取得
                $a_hotel_area_default['area_large'] = $a_hotel_area['area_l'];

                // 都道府県エリアの取得
                $a_hotel_area_default['area_pref'] = $a_hotel_area['area_p'];

                // 中エリアの取得
                $a_hotel_area_default['area_middle'] = $a_hotel_area['area_m'];

                // 小エリアの取得
                if (array_key_exists('area_s', $a_hotel_area)) {
                    $a_hotel_area_default['area_small'] = $a_hotel_area['area_s'];
                }

                return $a_hotel_area_default;
            }
        }

        // 見つからなかった場合
        return $a_hotel_area_default;
    }
    /**
     * 都道府県IDから地域ID（都道府県）を取得
     *
     * @param int 都道府県ID
     * @return int  地域ID（都道府県）
     */
    private function convertIdPrefToArea($as_pref_id)
    {
        // 数値型にキャストしておく
        $n_pref_id = (int)$as_pref_id;

        // 都道府県IDに補正値を加算し地域IDを取得する
        // HACK: magic number
        if (in_array($n_pref_id, [16, 17, 18])) {
            return $n_pref_id + 14;
        } else if (in_array($n_pref_id, [19, 20])) {
            return $n_pref_id + 9;
        } else {
            return $n_pref_id + 12;
        }
    }

    /**
     * 地域データ一覧の取得
     *
     * MEMO: 移植元 public\app\ctl\models\HotelArea.php > _make_mast_areas()
     * 移植元では、どこかで class property に設定(make) して、呼び出し時は class property を参照している
     *
     * MEMO: MastArea model で置き換えられるか。
     *
     * @return stdClass[]
     */
    public function getMastAreas()
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
        // $a_rows = $this->o_oracle->find_by_sql($sql, []);
        $a_rows = DB::select($sql);

        if (!is_null($a_rows)) {
            $a_mast_areas = $a_rows;
        } else {
            $a_mast_areas = [];
        }

        return $a_mast_areas; // MEMO: 移植元では、 return せずに class property に代入している
    }

    /**
     * 登録処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @param array $inputParams
     * @return array: error messages
     */
    public function create($hotelCd, $entryNo, $inputParams): array
    {
        // entry_no の取得
        // if (array_key_exists('entry_no', $inputParams)) {
        //     // entry_no が指定されている場合は「更新」
        //     $entryNo = $inputParams['entry_no'];
        // } else {
        // entry_no が指定されていない場合は「登録」
        // }

        DB::beginTransaction();

        // アクションの処理
        $errorMassages = $this->createMethod($hotelCd, $entryNo, $inputParams);
        if (count($errorMassages) > 0) {
            DB::rollback();
            return $errorMassages;
        }

        // コミット
        DB::commit();
        return $errorMassages;
    }

    /**
     * 新規作成処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @param array $inputParams
     * @return array
     */
    private function createMethod($hotelCd, $entryNo, $inputParams): array
    {
        // 初期化・データの整形
        $a_attributes = [];

        $a_area_ids = [
            self::IDX_AREA_LARGE  => $inputParams['area_large'],  // 大エリア
            self::IDX_AREA_PREF   => $inputParams['area_pref'],   // 都道府県
            self::IDX_AREA_MIDDLE => $inputParams['area_middle'], // 中エリア
            self::IDX_AREA_SMALL  => $inputParams['area_small'],  // 小エリア
        ];

        // レコード作成の為の情報を作成
        // MEMO: 大域、都道府県、中域、小域で、最大4つのレコードが挿入される
        $a_area_detail = [];
        foreach ($a_area_ids as $key => $area_id) {
            // 対象の地域情報を取得
            $a_area_detail = $this->getArea($area_id);

            // 登録用のデータを設定
            $a_attributes[$key] = [
                'hotel_cd'  => $hotelCd,
                'entry_no'  => $entryNo,
                'area_id'   => $a_area_detail->area_id,
                'area_type' => $a_area_detail->area_type,

                // 登録・編集者の設定
                'entry_cd'  => $this->getActionCd(),
                'modify_cd' => $this->getActionCd(),

                // MEMO: 共通カラム、日付は laravel で入れられる
            ];
        }

        // 独自バリデーション
        // HACK: （工数次第）validation は controller か FormObject に回したい
        $validationErrorMessages = $this->customValidation($hotelCd, $entryNo, $a_attributes);
        if (count($validationErrorMessages) > 0) {
            return $validationErrorMessages;
        }

        // Insert の実行
        $dbErrorMessages = [];
        try {
            foreach ($a_attributes as $a_attribute) {
                // HACK: （工数次第）ここで null の判定が必要ない形にしたいが、処理が複雑なため骨が折れそう。
                if (!is_null($a_attribute['area_id'])) {
                    $createdHotelArea = HotelArea::create($a_attribute);
                    if (!$createdHotelArea->wasRecentlyCreated) {
                        $dbErrorMessages[] = '地域・施設情報を登録できませんでした。';
                    }
                }
            }
        } catch (\Exception $e) {
            $dbErrorMessages[] = '地域・施設情報を登録できませんでした。';
            Log::error($e);
        }

        return $dbErrorMessages;
    }

    /**
     * 更新処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @param array $inputData
     * @return array
     */
    public function update($hotelCd, $entryNo, $inputData): array
    {
        DB::beginTransaction();

        // アクションの処理
        $errorMessages = $this->updateMethod($hotelCd, $entryNo, $inputData);
        if (count($errorMessages) > 0) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        return $errorMessages;
    }

    /**
     * 更新処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @param array $inputData
     * @return array
     */
    private function updateMethod($hotelCd, $entryNo, $inputData): array
    {
        $errorMessages = [];
        $deleteAffectedRowCount = $this->deleteMethod($hotelCd, $entryNo);
        if ($deleteAffectedRowCount === 0) {
            $errorMessages[] = '更新時に既存レコード群を削除できませんでした。';
            return $errorMessages;
        }

        $createErrorMessages = $this->createMethod($hotelCd, $entryNo, $inputData);
        if (count($createErrorMessages) > 0) {
            $errorMessages = $createErrorMessages;
            $errorMessages[] = '更新時に既存レコード群を登録できませんでした。';
            return $errorMessages;
        }

        return $errorMessages;
    }

    /**
     * 登録番号の新規発番したものを取得
     *
     * @param string $hotelCd
     * @return int
     */
    public function issueEntryNo($hotelCd): int
    {
        // HACK: HotelArea model から QueryBuilder で取得したほうが可読性あがるか。
        $sql = <<<SQL
            select
                entry_no
            from
                hotel_area
            where
                hotel_cd = :hotel_cd
            group by
                entry_no
            order by
                entry_no asc
        SQL;
        $a_rows = DB::select($sql, ['hotel_cd' => $hotelCd]);

        // 既存の番号に1番が存在していない場合は1とする
        if (count($a_rows) === 0 || $a_rows[0]->entry_no != 1) {
            return 1;
        }

        // 歯抜けが発生している場合はその番号を取得して返す
        $sql = <<<SQL
            select
                ifnull(min(entry_no + 1), 1) as issue_entry_no
            from
                hotel_area
            where
                hotel_cd = :hotel_cd_1
                and (entry_no + 1) not in (
                    select
                        entry_no
                    from
                        hotel_area
                    where
                        hotel_cd = :hotel_cd_2
                )
        SQL;
        $a_row = DB::select($sql, [
            'hotel_cd_1' => $hotelCd,
            'hotel_cd_2' => $hotelCd,
        ]);

        return (int)$a_row[0]->issue_entry_no;
    }

    /**
     * エラーチェック
     *
     * @return array: エラーメッセージ
     */
    private function customValidation($hotelCd, $entryNo, $a_attributes): array
    {
        // 初期化
        $errorMessages = [];
        $a_temp_idx = [
            self::IDX_AREA_LARGE  => '大エリア',
            self::IDX_AREA_PREF   => '都道府県',
            self::IDX_AREA_MIDDLE => '中エリア'
        ];

        foreach ($a_temp_idx as $key => $value) {
            // 必須チェック
            if (is_null($a_attributes[$key]['area_id'])) {
                $errorMessages[] = '「' . $value . '」が選択されていません。';
            }

            // 整合性チェック
            if (!$this->isIntegrityAreaId(
                $a_attributes[$key]['area_id'],
                $a_attributes[$key]['area_type']
            )) {
                $errorMessages[] = '「' . $value . '」に指定された地域データが存在していません。';
            }
        }

        // 地域データ（大エリア・都道府県・中エリア・小エリア）の組み合わせの整合性チェック
        if (
            !$this->isIntegrityAreaPattern(
                $a_attributes[self::IDX_AREA_LARGE]['area_id'],
                $a_attributes[self::IDX_AREA_PREF]['area_id'],
                $a_attributes[self::IDX_AREA_MIDDLE]['area_id'],
                $a_attributes[self::IDX_AREA_SMALL]['area_id']
            )
        ) {
            $errorMessages[] = '地域の組合せが正しくありません。';
        }

        // 地域データ（大エリア・都道府県・中エリア・小エリア）の組み合わせの重複チェック
        if (
            !$this->isUniqueAreaPattern(
                $hotelCd,
                $entryNo,
                $a_attributes[self::IDX_AREA_LARGE]['area_id'],
                $a_attributes[self::IDX_AREA_PREF]['area_id'],
                $a_attributes[self::IDX_AREA_MIDDLE]['area_id'],
                $a_attributes[self::IDX_AREA_SMALL]['area_id']
            )
        ) {
            $errorMessages[] = 'この地域の組み合わせはすでに登録されています。';
        }

        return $errorMessages;
    }

    /**
     * 対象のエリアIDの整合性チェック
     *
     * @param string $an_area_id: エリアID
     * @param string $an_area_type: 地域タイプ
     * @return bool: 整合性の成否(true：正規, false:不正)
     */
    private function isIntegrityAreaId($an_area_id, $an_area_type): bool
    {
        $a_mast_areas = $this->getMastAreas();
        // HACK: 都度 mast_area model から検索したほうがよさそうか？
        foreach ($a_mast_areas as $a_area) {
            // 地域IDのマッチング
            if ((int)$a_area->area_id === (int)$an_area_id) {
                // 地域タイプのマッチング
                if ((int)$a_area->area_type === (int)$an_area_type) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 対象エリアIDの組合せの整合性チェック
     *
     * @param string $an_area_l: エリアID（大エリア）
     * @param string $an_area_p: エリアID（都道府県）
     * @param string $an_area_m: エリアID（中エリア）
     * @param string $an_area_s: エリアID（小エリア）
     * @return bool: 登録の可否（true：登録可, false：登録不可）
     */
    private function isIntegrityAreaPattern(
        $an_area_l,
        $an_area_p,
        $an_area_m,
        $an_area_s
    ): bool {
        // 初期化
        $a_area_detail_pref   = $this->getArea($an_area_p);         // 都道府県
        $a_area_detail_middle = $this->getArea($an_area_m);         // 中エリア
        $a_area_detail_small  = $this->getArea($an_area_s);         // 小エリア
        $a_area_m_children    = $this->getChildAreas($an_area_m);   // 中エリアを親に持つ小エリアID情報

        // 小エリアを指定しているとき
        if (!is_null($an_area_s)) {
            if ((int)$a_area_detail_small->parent_area_id !== (int)$an_area_m) {
                return false;
            }
        }

        // 中エリア
        if ((int)$a_area_detail_middle->parent_area_id !== (int)$an_area_p) {
            return false;
        }

        // 対象の中エリアに小エリアが存在する場合
        if (count($a_area_m_children) > 0) {
            // 小エリアが指定されているかをチェックする
            if (!in_array($an_area_s, $a_area_m_children)) {
                return false;
            }
        }

        // 都道府県
        if ((int)$a_area_detail_pref->parent_area_id !== (int)$an_area_l) {
            return false;
        }

        return true;
    }

    /**
     * 対象エリアIDの組合せの重複チェック
     *
     * @param [type] $hotelCd
     * @param [type] $entryNo
     * @param [type] $an_area_l: エリアID（大エリア）
     * @param [type] $an_area_p: エリアID（都道府県）
     * @param [type] $an_area_m: エリアID（中エリア）
     * @param [type] $an_area_s:エリアID（小エリア）
     * @return boolean: 登録の可否（true：登録可, false：登録不可）
     */
    private function isUniqueAreaPattern(
        $hotelCd,
        $entryNo,
        $an_area_l,
        $an_area_p,
        $an_area_m,
        $an_area_s
    ): bool {
        $a_hotel_areas = $this->getHotelAreas($hotelCd);
        foreach ($a_hotel_areas as $a_row) {
            if (
                (int)$entryNo !== (int)$a_row['entry_no']
                && (int)$a_row['area_l'] === (int)$an_area_l
                && (int)$a_row['area_p'] === (int)$an_area_p
                && (int)$a_row['area_m'] === (int)$an_area_m
                && (!array_key_exists('area_s', $a_row)
                    || (int)$a_row['area_s'] === (int)$an_area_s
                )
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * 対象地域IDを親地域IDにもつ地域IDをすべて取得
     *
     * @param string $an_area_id: 地域ID
     * @return array: 対象地域IDの配列
     */
    private function getChildAreas($an_area_id): array
    {
        // 初期化
        $n_area_id  = (int)$an_area_id;
        $a_area_ids = [];

        // 地域IDがNull
        if (is_null($an_area_id)) {
            return $a_area_ids;
        }

        // 地域マスター情報から一致する地域情報を探す
        // HACK: mast_area model から検索したほうがよさそう？
        $a_mast_areas = $this->getMastAreas();
        foreach ($a_mast_areas as $a_area) {
            // 見つかった場合は対象の情報を返却する
            if ((int)$a_area->parent_area_id === $n_area_id) {
                $a_area_ids[] = $a_area->area_id;
            }
        }

        return $a_area_ids;
    }

    /**
     * 削除処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @return bool
     */
    public function delete($hotelCd, $entryNo): bool
    {
        DB::beginTransaction();
        $affectedRowCount = $this->deleteMethod($hotelCd, $entryNo);
        if ($affectedRowCount === 0) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * 削除処理
     *
     * @param string $hotelCd
     * @param string $entryNo
     * @return int
     */
    private function deleteMethod($hotelCd, $entryNo): int
    {
        // MEMO: 移植元では、PK 指定が必要なためそれぞれで削除処理を行っているが、Laravel では where で絞り込んだレコードを一括削除できる。
        $affectedRowCount =  HotelArea::where('hotel_cd', $hotelCd)
            ->where('entry_no', $entryNo)
            ->delete();

        return $affectedRowCount;
    }

    /**
     * コントローラ名とアクション名を取得して、ユーザーIDと連結
     * ユーザーID取得は暫定の為、書き換え替えが必要です。
     *
     * HACK: app/Models/common/CommonDBModel.php からのコピペ
     * @return string
     */
    private function getActionCd()
    {
        $path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
        $pathList = explode('\\', $path[0]);
        $controllerName = str_replace("Controller", "", end($pathList)); //コントローラ名
        $actionName = $path[1];           // アクション名
        $userId = \Illuminate\Support\Facades\Session::get("user_id"); //TODO ユーザー情報取得のキーは仮です
        $action_cd = $controllerName . "/" . $actionName . "." . $userId;
        return $action_cd;
    }
}
