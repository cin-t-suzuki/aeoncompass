<?php

namespace App\Services;

use App\Models\HotelMedia;
use App\Models\Media;
use App\Models\MediaOrg;
use App\Models\Plan;
use App\Models\PlanMedia;
use App\Models\Room2;
use App\Models\RoomMedia2;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HtlMediaService
{
    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_all_media_list()
     *
     * @return void
     */
    public function getAllMediaList($hotelCd, $conditions): array
    {
        $whereSql = '';

        // ラベルコードを指定
        if (count($conditions) > 0) {
            // MEMO: 0/1 からなる文字列でラベルの組み合わせパターンを表現
            $whereSql = " and (";
            if (array_key_exists('outside', $conditions) && $conditions['outside']) {
                $whereSql .= " media.label_cd like '1____' or ";
            }
            if (array_key_exists('map', $conditions) && $conditions['map']) {
                $whereSql .= " media.label_cd like '_1___' or ";
            }
            if (array_key_exists('inside', $conditions) && $conditions['inside']) {
                $whereSql .= " media.label_cd like '__1__' or ";
            }
            if (array_key_exists('room', $conditions) && $conditions['room']) {
                $whereSql .= " media.label_cd like '___1_' or ";
            }
            if (array_key_exists('other', $conditions) && $conditions['other']) {
                $whereSql .= " media.label_cd like '____1' or ";
            }
            if (array_key_exists('nothing', $conditions) && $conditions['nothing']) {
                $whereSql .= " media.label_cd like '00000' or ";
            }
            $whereSql = substr($whereSql, 0, -4); // 最後の 'or' を消す。
            $whereSql .= ")";
        }

        $sql = <<< SQL
            select
                media.media_no,
                media.label_cd,
                media.title,
                media.file_nm,
                ifnull(media_org.org_file_nm, media.file_nm) as disp_file_nm,
                media.order_no,
                media.order_no + 1 as order_no_plus,
                media.order_no - 1 as order_no_minus
                -- , media.upload_dtm
                , media.modify_ts
                -- ,
                -- TODO: これは何をやっている？ -> UNIX タイムスタンプに変換しているように見える
                -- to_number(
                --     to_date(
                --         to_char(
                --             cast(
                --                 SYS_EXTRACT_UTC(
                --                     to_timestamp(
                --                         to_char(media.upload_dtm, 'YYYY-MM-DD HH24:MI:SS'),
                --                         'YYYY-MM-DD HH24:MI:SS'
                --                     )
                --                 ) as date
                --             ),
                --             'YYYY-MM-DD'
                --         ),
                --         'YYYY-MM-DD'
                --     ) - to_date('1970-01-01', 'YYYY-MM-DD')
                -- ) * 24 * 60 * 60 + to_number(
                --     to_char(
                --         cast(
                --             SYS_EXTRACT_UTC(
                --                 to_timestamp(
                --                     to_char(media.upload_dtm, 'YYYY-MM-DD HH24:MI:SS'),
                --                     'YYYY-MM-DD HH24:MI:SS'
                --                 )
                --             ) as date
                --         ),
                --         'SSSSS'
                --     )
                -- ) as upload_dtm,
                -- to_number(
                --     to_date(
                --         to_char(
                --             cast(
                --                 SYS_EXTRACT_UTC(
                --                     to_timestamp(
                --                         to_char(media.modify_ts, 'YYYY-MM-DD HH24:MI:SS'),
                --                         'YYYY-MM-DD HH24:MI:SS'
                --                     )
                --                 ) as date
                --             ),
                --             'YYYY-MM-DD'
                --         ),
                --         'YYYY-MM-DD'
                --     ) - to_date('1970-01-01', 'YYYY-MM-DD')
                -- ) * 24 * 60 * 60 + to_number(
                --     to_char(
                --         cast(
                --             SYS_EXTRACT_UTC(
                --                 to_timestamp(
                --                     to_char(media.modify_ts, 'YYYY-MM-DD HH24:MI:SS'),
                --                     'YYYY-MM-DD HH24:MI:SS'
                --                 )
                --             ) as date
                --         ),
                --         'SSSSS'
                --     )
                -- ) as modify_ts
            from
                media
                left outer join media_org
                    on media.hotel_cd = media_org.hotel_cd
                    and media.media_no = media_org.media_no
            where
                media.hotel_cd = :hotel_cd
                {$whereSql}
            order by
                media.order_no asc
        SQL;
        $allMediaList = DB::select($sql, ['hotel_cd' => $hotelCd]);

        // 利用状況一覧の取得
        $hotelMediaList = $this->getHotelMediaList($hotelCd);
        $roomMediaList  = $this->getRoomMediaList($hotelCd);
        $planMediaList  = $this->getPlanMediaList($hotelCd);

        // テンプレート用に整形
        foreach ($allMediaList as $key => $media) {
            /* 旧ソース
            $allMediaList[$key]['is_use']['hotel'] = $hotelMediaList[$media['media_no']][HotelMedia::TYPE_HOTEL]; // 施設写真に利用しているかどうか
            $allMediaList[$key]['is_use']['map']   = $hotelMediaList[$media['media_no']][HotelMedia::TYPE_MAP]; // 地図写真に利用しているかどうか
            $allMediaList[$key]['is_use']['other'] = $hotelMediaList[$media['media_no']][HotelMedia::TYPE_OTHER]; // その他写真に利用しているかどうか
            $allMediaList[$key]['is_use']['room']  = $roomMediaList[$media['media_no']];     // 部屋写真に利用しているかどうか
            $allMediaList[$key]['is_use']['plan']  = $planMediaList[$media['media_no']];     // プラン写真に利用しているかどうか
            */

            $allMediaList[$key]->is_use = [
                'hotel' => array_key_exists($media->media_no, $hotelMediaList) && array_key_exists(HotelMedia::TYPE_HOTEL, $hotelMediaList[$media->media_no]),  // 施設写真に利用しているかどうか
                'map'   => array_key_exists($media->media_no, $hotelMediaList) && array_key_exists(HotelMedia::TYPE_MAP, $hotelMediaList[$media->media_no]),    // 地図写真に利用しているかどうか
                'other' => array_key_exists($media->media_no, $hotelMediaList) && array_key_exists(HotelMedia::TYPE_OTHER, $hotelMediaList[$media->media_no]),  // その他写真に利用しているかどうか
                'room'  => array_key_exists($media->media_no, $roomMediaList),                                                                                  // 部屋写真に利用しているかどうか
                'plan'  => array_key_exists($media->media_no, $planMediaList),                                                                                  // プラン写真に利用しているかどうか
            ];
        }

        return $allMediaList;
    }

    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_hotel_media_list()
     *
     * @return array
     */
    private function getHotelMediaList($hotelCd): array
    {
        $sql = <<< SQL
            select distinct
                hotel_cd,
                type,
                media_no
            from
                hotel_media
            where
                hotel_cd = :hotel_cd
        SQL;
        $hotelMediaList = DB::select($sql, ['hotel_cd' => $hotelCd]);

        $results = [];

        // media_noをキーに整形
        foreach ($hotelMediaList as $media) {
            $results[$media->media_no][$media->type] = true;
        }

        return $results;
    }

    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_room_media_list()
     *
     * @param [type] $hotelCd
     * @return array
     */
    private function getRoomMediaList($hotelCd): array
    {
        $sql = <<< SQL
            select distinct
                hotel_cd,
                media_no
            from
                room_media2
            where
                hotel_cd = :hotel_cd
                and
                exists (
                    select *
                    from
                        room2
                    where
                        hotel_cd = room_media2.hotel_cd
                        and room_id = room_media2.room_id
                        and display_status = 1
                        and active_status = 1
                )
        SQL;
        $roomMediaList = DB::select($sql, ['hotel_cd' => $hotelCd]);

        $results = [];

        // media_noをキーに整形
        foreach ($roomMediaList as $media) {
            $results[$media->media_no] = true;
        }

        return $results;
    }

    /**
     * Undocumented function
     *
     * MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_plan_media_list()
     *
     * @param [type] $hotelCd
     * @return array
     */
    private function getPlanMediaList($hotelCd): array
    {
        $sql = <<< SQL
            select distinct
                hotel_cd,
                media_no
            from
                plan_media
            where
                hotel_cd = :hotel_cd
                and exists (
                    select *
                    from
                        plan
                    where
                        hotel_cd = plan_media.hotel_cd
                        and plan_id = plan_media.plan_id
                        and display_status = 1
                        and active_status = 1
                )
        SQL;
        $planMediaList = DB::select($sql, ['hotel_cd' => $hotelCd]);

        $results = [];

        // media_noをキーに整形
        foreach ($planMediaList as $media) {
            $results[$media->media_no] = true;
        }

        return $results;
    }

    /**
     * 施設メディア情報の取得
     *
     * @param [type] $hotelCd
     * @param [type] $type: 1:外観 2:地図 3:その他
     * @return array
     */
    public function getHotelMedia($hotelCd, $type): array
    {
        $sql = <<< SQL
            select
                q1.media_no,
                q1.order_no,
                (q1.order_no + 1) as order_no_plus,
                (q1.order_no - 1) as order_no_minus,
                m.label_cd,
                m.title,
                m.file_nm,
                m.mime_type
            from
                media as m
                inner join (
                    select
                        media_no,
                        order_no
                    from
                        hotel_media
                    where
                        hotel_cd = :hotel_cd
                        and type = :type
                ) as q1
                    on m.media_no = q1.media_no
            where
                m.hotel_cd = :hotel_cd2
            order by
                q1.order_no
        SQL;

        $results = DB::select($sql, [
            'hotel_cd'  => $hotelCd,
            'hotel_cd2' => $hotelCd,
            'type'      => $type
        ]);

        return $results;
    }

    /**
     * メディア取得
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @return Media
     */
    private function findMedia($hotelCd, $mediaNo): Media
    {
        return Media::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->first();
    }

    /**
     * メディア取得（オリジナルメディア名付き）
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @return Media
     */
    public function findMediaWithOriginal($hotelCd, $mediaNo): Media
    {
        $media = Media::where('hotel_cd', $hotelCd)->where('media_no', $mediaNo)->first();

        $mediaOrg = MediaOrg::where('hotel_cd', $hotelCd)->where('media_no', $mediaNo)->first();
        if (is_null($mediaOrg)) {
            $media->disp_file_nm = $media->file_nm;
        } else {
            $media->disp_file_nm = $mediaOrg->org_file_nm;
        }

        return $media;
    }

    /**
     * 画像ラベル内での順序変更を行う。
     *
     * 標準所の繰上げ、繰り下げは移動元と移動先の設定を逆にする。
     *
     * HACK: refactor $up は引数で受け取らなくても、移動元と移動先の order_no の大小で判定できる。
     *
     * @param string $hotelCd
     * @param int $mediaNo:         移動元のメディア番号
     * @param int $targetMediaNo:   移動先のメディア番号
     * @param bool $up:             true:繰上げ（順位を上げる） false 繰り下げ（順位を下げる）
     * @return bool
     */
    public function sortMedia($hotelCd, $mediaNo, $targetMediaNo, $up): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $mediaNo, $targetMediaNo, $up) {
                if ($up) {
                    $this->changeOrderUp($hotelCd, $mediaNo, $targetMediaNo);
                } else {
                    $this->changeOrderDown($hotelCd, $mediaNo, $targetMediaNo);
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
    private function changeOrderUp($hotelCd, $mediaNo, $targetMediaNo): void
    {
        $media = $this->findMedia($hotelCd, $mediaNo);
        $targetMedia = $this->findMedia($hotelCd, $targetMediaNo);

        $targetOrderNo = $targetMedia->order_no;

        Media::where([
            ['hotel_cd', '=', $hotelCd],
            ['order_no', '>=', $targetMedia->order_no],
            ['order_no', '<', $media->order_no],
        ])->increment('order_no');

        Media::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->update(['order_no' => $targetOrderNo]);
    }
    private function changeOrderDown($hotelCd, $mediaNo, $targetMediaNo): void
    {
        $media = $this->findMedia($hotelCd, $mediaNo);
        $targetMedia = $this->findMedia($hotelCd, $targetMediaNo);

        $targetOrderNo = $targetMedia->order_no;

        Media::where([
            ['hotel_cd', '=', $hotelCd],
            ['order_no', '<=', $targetMedia->order_no],
            ['order_no', '>', $media->order_no],
        ])->decrement('order_no');

        Media::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->update(['order_no' => $targetOrderNo]);
    }

    /**
     * 施設画像並べ替え
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @param int $targetMediaNo
     * @return bool
     */
    public function sortGallery($hotelCd, $mediaNo, $targetMediaNo): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $mediaNo, $targetMediaNo) {
                // 移動元の表示順番号を取得
                $orderNo = HotelMedia::where([
                    'hotel_cd' => $hotelCd,
                    'type' => HotelMedia::TYPE_OTHER,
                    'media_no' => $mediaNo,
                ])->first()->order_no;

                // 移動先の表示順番号を取得
                $targetOrderNo = HotelMedia::where([
                    'hotel_cd' => $hotelCd,
                    'type' => HotelMedia::TYPE_OTHER,
                    'media_no' => $targetMediaNo,
                ])->first()->order_no;

                // 移動元の表示順番号を、移動先のものに更新
                HotelMedia::where([
                    'hotel_cd' => $hotelCd,
                    'type' => HotelMedia::TYPE_OTHER,
                    'media_no' => $mediaNo,
                ])->update(['order_no' => $targetOrderNo]);

                // 移動先の表示番号を、移動元のものに更新
                HotelMedia::where([
                    'hotel_cd' => $hotelCd,
                    'type' => HotelMedia::TYPE_OTHER,
                    'media_no' => $targetMediaNo,
                ])->update(['order_no' => $orderNo]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
    public function sortRoom($hotelCd, $roomId, $sourceMediaNo, $targetMediaNo): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $roomId, $sourceMediaNo, $targetMediaNo) {
                // 移動元の表示順番号を取得
                $sourceOrderNo = RoomMedia2::where([
                    'hotel_cd' => $hotelCd,
                    'room_id' => $roomId,
                    'media_no' => $sourceMediaNo,
                ])->first()->order_no;

                // 移動先の表示順番号を取得
                $targetOrderNo = RoomMedia2::where([
                    'hotel_cd' => $hotelCd,
                    'room_id' => $roomId,
                    'media_no' => $targetMediaNo,
                ])->first()->order_no;

                // 移動元の表示順番号を、移動先のものに更新
                RoomMedia2::where([
                    'hotel_cd' => $hotelCd,
                    'room_id' => $roomId,
                    'media_no' => $sourceMediaNo,
                ])->update(['order_no' => $targetOrderNo]);

                // 移動先の表示番号を、移動元のものに更新
                RoomMedia2::where([
                    'hotel_cd' => $hotelCd,
                    'room_id' => $roomId,
                    'media_no' => $targetMediaNo,
                ])->update(['order_no' => $sourceOrderNo]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
    public function sortPlan($hotelCd, $planId, $sourceMediaNo, $targetMediaNo): bool
    {
        // dd($hotelCd, $planId, $sourceMediaNo);
        try {
            DB::transaction(function () use ($hotelCd, $planId, $sourceMediaNo, $targetMediaNo) {
                // 移動元の表示順番号を取得
                $sourceOrderNo = PlanMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $sourceMediaNo,
                ])->first()->order_no;

                // 移動先の表示順番号を取得
                $targetOrderNo = PlanMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $targetMediaNo,
                ])->first()->order_no;

                // 移動元の表示順番号を、移動先のものに更新
                PlanMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $sourceMediaNo,
                ])->update(['order_no' => $targetOrderNo]);

                // 移動先の表示番号を、移動元のものに更新
                PlanMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $targetMediaNo,
                ])->update(['order_no' => $sourceOrderNo]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }

    /**
     * メディア情報更新
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @param string $title
     * @param string $labelCd
     * @return bool
     */
    public function updateMedia($hotelCd, $mediaNo, $title, $labelCd): bool
    {
        $media = Media::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->first();
        if ($media->isLabeledAsMap()) {
            $labelCd = '01000';
        }

        try {
            DB::transaction(function () use ($hotelCd, $mediaNo, $title, $labelCd) {
                Media::where([
                    'hotel_cd'  => $hotelCd,
                    'media_no'  => $mediaNo,
                ])->update([
                    'title'     => $title,
                    'label_cd'  => $labelCd,
                ]);
                throw new \Exception('dummy exception');
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }

    /**
     * 施設画像更新
     *
     * @param string $hotelCd
     * @param int $type
     * @param int $oldMediaNo
     * @param int $newMediaNo
     * @param int $orderNo
     * @return array errorMessages
     */
    public function updateHotel($hotelCd, $type, $oldMediaNo, $newMediaNo, $orderNo): array
    {
        $errorMessages = [];
        try {
            DB::transaction(function () use ($hotelCd, $type, $oldMediaNo, $newMediaNo, $orderNo) {
                // レコードがあるかどうかチェック
                // MEMO: 存在すれば「更新」、存在しなければ「追加」
                if (
                    HotelMedia::where([
                        'hotel_cd'  => $hotelCd,
                        'type'      => $type,
                        'media_no'  => $oldMediaNo,
                    ])->exists()
                ) {
                    // 「更新」の場合、削除して登録
                    HotelMedia::where([
                        'hotel_cd'  => $hotelCd,
                        'type'      => $type,
                        'media_no'  => $oldMediaNo,
                    ])->delete();
                }

                // 登録画像が重複していないかチェック
                if (
                    $type == HotelMedia::TYPE_OTHER
                    && HotelMedia::where([
                        'hotel_cd' => $hotelCd,
                        'type'      => HotelMedia::TYPE_OTHER,
                        'media_no' => $newMediaNo,
                    ])->exists()
                ) {
                    throw new \Exception('error_duplicate_image');
                }

                // データを登録
                HotelMedia::create([
                    'hotel_cd'  => $hotelCd,
                    'type'      => $type,
                    'media_no'  => $newMediaNo,
                    'order_no'  => $orderNo,
                ]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getMessage() == 'error_duplicate_image') {
                $errorMessages[] = 'フォトギャラリーに同じ画像が設定されています。';
            }
            $errorMessages[] = '施設画像の編集に失敗しました。';
            return $errorMessages;
        }
        return [];
    }
    public function updateRoom($hotelCd, $roomId, $oldMediaNo, $newMediaNo, $orderNo): array
    {
        $errorMessages = [];
        try {
            DB::transaction(function () use ($hotelCd, $roomId, $oldMediaNo, $newMediaNo, $orderNo) {
                // レコードがあるかどうかチェック
                // MEMO: 存在すれば「更新」、存在しなければ「追加」
                if (
                    RoomMedia2::where([
                        'hotel_cd'  => $hotelCd,
                        'room_id'   => $roomId,
                        'media_no'  => $oldMediaNo,
                    ])->exists()
                ) {
                    // 「更新」の場合、削除して登録
                    RoomMedia2::where([
                        'hotel_cd'  => $hotelCd,
                        'room_id'   => $roomId,
                        'media_no'  => $oldMediaNo,
                    ])->delete();
                }

                // 登録画像が重複していないかチェック
                if (
                    RoomMedia2::where([
                        'hotel_cd'  => $hotelCd,
                        'room_id'   => $roomId,
                        'media_no'  => $newMediaNo,
                    ])->exists()
                ) {
                    throw new \Exception('error_duplicate_image');
                }

                // データを登録
                RoomMedia2::create([
                    'hotel_cd'  => $hotelCd,
                    'room_id'   => $roomId,
                    'media_no'  => $newMediaNo,
                    'order_no'  => $orderNo,
                ]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getMessage() == 'error_duplicate_image') {
                $errorMessages[] = 'この部屋には既に同じ画像が設定されています。';
            }
            $errorMessages[] = '部屋画像の編集に失敗しました。';
            return $errorMessages;
        }
        return [];
    }
    public function updatePlan($hotelCd, $planId, $oldMediaNo, $newMediaNo, $orderNo): array
    {
        $errorMessages = [];
        try {
            DB::transaction(function () use ($hotelCd, $planId, $oldMediaNo, $newMediaNo, $orderNo) {
                // レコードがあるかどうかチェック
                // MEMO: 存在すれば「更新」、存在しなければ「追加」
                if (
                    PlanMedia::where([
                        'hotel_cd'  => $hotelCd,
                        'plan_id'   => $planId,
                        'media_no'  => $oldMediaNo,
                    ])->exists()
                ) {
                    // 「更新」の場合、削除して登録
                    PlanMedia::where([
                        'hotel_cd'  => $hotelCd,
                        'plan_id'   => $planId,
                        'media_no'  => $oldMediaNo,
                    ])->delete();
                }

                // 登録画像が重複していないかチェック
                if (
                    PlanMedia::where([
                        'hotel_cd'  => $hotelCd,
                        'plan_id'   => $planId,
                        'media_no'  => $newMediaNo,
                    ])->exists()
                ) {
                    throw new \Exception('error_duplicate_image');
                }

                // データを登録
                PlanMedia::create([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $newMediaNo,
                    'order_no'  => $orderNo,
                ]);
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getMessage() == 'error_duplicate_image') {
                $errorMessages[] = 'このプランには既に同じ画像が設定されています。';
            }
            $errorMessages[] = 'プラン画像の編集に失敗しました。';
            return $errorMessages;
        }
        return [];
    }
    /**
     * メディア削除
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @return array errorMessages
     */
    public function destroyMedia($hotelCd, $mediaNo): array
    {
        $errorMessages = [];
        try {
            DB::transaction(function () use ($hotelCd, $mediaNo) {
                // 対象のメディア情報取得
                $media = $this->findMedia($hotelCd, $mediaNo);

                // 削除対象が存在するかチェック
                if (is_null($media)) {
                    throw new \Exception('media_info_not_exist');
                }
                $fileName = $media->file_nm;

                // メディア関連情報の削除
                $this->destroyRelatedMedia($hotelCd, $mediaNo);

                // 同一ファイル名の登録がない場合、画像ファイルを削除
                if (!$this->existsFile($hotelCd, $fileName)) {
                    Storage::delete('public/images/hotel/' . $hotelCd . '/' . $fileName);
                }
            });
        } catch (\Exception $e) {
            Log::error($e);
            if ($e->getMessage() == 'media_info_not_exist') {
                $errorMessages[] = 'ファイルが見つかりません。';
            }
            $errorMessages[] = '画像の削除に失敗しました。';
            return $errorMessages;
        }
        return [];
    }
    private function destroyRelatedMedia($hotelCd, $mediaNo): void
    {
        // 部屋メディア
        RoomMedia2::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->delete();

        // プランメディア
        PlanMedia::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->delete();

        // 施設メディア
        HotelMedia::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->delete();

        // メディアオリジナル名称
        MediaOrg::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->delete();

        // メディア
        Media::where([
            'hotel_cd' => $hotelCd,
            'media_no' => $mediaNo,
        ])->delete();
    }

    /**
     * 同一ファイル名の存在確認
     *
     * @param string $hotelCd
     * @param string $fileName
     * @return bool
     */
    private function existsFile($hotelCd, $fileName): bool
    {
        return Media::where([
            'hotel_cd' => $hotelCd,
            'file_nm' => $fileName,
        ])->exists();
    }

    // MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_room_media
    public function getRoomMedia($hotelCd, $roomId)
    {
        $a_find_room2 = Room2::where([
            'hotel_cd' => $hotelCd,
            'room_id' => $roomId,
        ])->first();

        $s_sql = <<< SQL
            select
                q1.media_no,
                q1.order_no,
                (q1.order_no + 1) as order_no_plus,
                (q1.order_no - 1) as order_no_minus,
                m.label_cd,
                m.title,
                m.file_nm,
                ifnull(mo.org_file_nm, m.file_nm) as disp_file_nm,
                m.mime_type
            from
                media as m
                inner join room_media2 as q1
                    on m.hotel_cd = q1.hotel_cd
                        and m.media_no = q1.media_no
                        and q1.room_id = :room_id
                left outer join media_org as mo
                    on m.hotel_cd = mo.hotel_cd
                        and m.media_no = mo.media_no
            where 1 = 1
                and m.hotel_cd = :hotel_cd
            order by q1.order_no
        SQL;
        $a_rows = DB::select($s_sql, [
            'hotel_cd' => $hotelCd,
            'room_id'  => $roomId,
        ]);

        $a_find_room2->medias = $a_rows;
        return $a_find_room2;
    }
    // MEMO: 移植元 public\app\ctl\models\Hotel\Media2.php get_room_plan_media
    public function getRoomPlanMedia($hotelCd, $roomId)
    {
        $s_sql = <<< SQL
            select
                q3.plan_id,
                q3.plan_nm,
                q3.plan_type,
                q3.accept_status,
                q3.plan_order_no,
                q3.modify_ts,
                q3.media_no,
                q3.order_no,
                m.label_cd,
                m.title,
                m.file_nm,
                ifnull(mo.org_file_nm, m.file_nm) as disp_file_nm,
                m.mime_type
            from
                (
                    select
                        q2.plan_id,
                        q2.plan_nm,
                        q2.plan_type,
                        q2.accept_status,
                        q2.plan_order_no,
                        q2.modify_ts,
                        pm.media_no,
                        pm.order_no
                    from
                        (
                            select
                                p.plan_id,
                                p.plan_nm,
                                p.plan_type,
                                p.accept_status,
                                p.order_no as plan_order_no,
                                p.modify_ts
                            from
                                plan as p
                                inner join (
                                    select
                                        plan_id
                                    from
                                        room_plan_match
                                    where 1 = 1
                                        and hotel_cd = :hotel_cd1
                                        and room_id  = :room_id
                                ) q1
                                    on 1 = 1
                                    and p.plan_id = q1.plan_id
                            where 1 = 1
                                and p.hotel_cd = :hotel_cd2
                                and p.display_status = 1
                        ) q2
                        left outer join plan_media as pm
                            on 1 = 1
                            and q2.plan_id = pm.plan_id
                            and pm.hotel_cd = :hotel_cd3
                    where 1 = 1
                    order by
                        pm.order_no
                ) q3
                left outer join media as m
                    on 1 = 1
                        and m.hotel_cd = :hotel_cd4
                        and q3.media_no = m.media_no
                left outer join media_org as mo
                    on 1 = 1
                        and mo.hotel_cd = :hotel_cd5
                        and q3.media_no = mo.media_no
            where 1 = 1
            order by
                q3.plan_order_no,
                q3.order_no
        SQL;
        $a_rows = DB::select($s_sql, [
            'hotel_cd1' => $hotelCd,
            'hotel_cd2' => $hotelCd,
            'hotel_cd3' => $hotelCd,
            'hotel_cd4' => $hotelCd,
            'hotel_cd5' => $hotelCd,
            'room_id'  => $roomId,
        ]);

        $a_result = [];

        // 整形
        foreach ($a_rows as $a_row) {
            // // メディア情報
            // $a_media = array();
            // $a_media['media_no']  = $a_row['media_no'];
            // $a_media['order_no']  = $a_row['order_no'];
            // $a_media['label_cd']  = $a_row['label_cd'];
            // $a_media['title']     = $a_row['title'];
            // $a_media['file_nm']   = $a_row['file_nm'];
            // $a_media['mime_type'] = $a_row['mime_type'];

            // // プランメディア情報
            // $a_result[$a_row['plan_id']]['plan_id']       = $a_row['plan_id'];
            // $a_result[$a_row['plan_id']]['plan_nm']       = $a_row['plan_nm'];
            // $a_result[$a_row['plan_id']]['plan_type']     = $a_row['plan_type'];
            // $a_result[$a_row['plan_id']]['accept_status'] = $a_row['accept_status'];
            // $a_result[$a_row['plan_id']]['plan_order_no'] = $a_row['plan_order_no'];
            // $a_result[$a_row['plan_id']]['modify_ts']     = $a_row['modify_ts'];
            // $a_result[$a_row['plan_id']]['medias'][]      = $a_media;

            // メディア情報
            $a_media = [
                'media_no'  => $a_row->media_no,
                'order_no'  => $a_row->order_no,
                'label_cd'  => $a_row->label_cd,
                'title'     => $a_row->title,
                'file_nm'   => $a_row->file_nm,
                'mime_type' => $a_row->mime_type,
            ];

            // プランメディア情報
            $a_result[$a_row->plan_id] = [
                'plan_id'       => $a_row->plan_id,
                'plan_nm'       => $a_row->plan_nm,
                'plan_type'     => $a_row->plan_type,
                'accept_status' => $a_row->accept_status,
                'plan_order_no' => $a_row->plan_order_no,
                'modify_ts'     => $a_row->modify_ts,
            ];
            $a_result[$a_row->plan_id]['medias'][]      = $a_media;
        }

        return $a_result;
    }
    // MEMO: 移植元 C:\AeonCompass\hotel\svn_trunk\public\app\ctl\models\Hotel\Room2.php is_room_akf
    public function isRoomAkf($hotelCd, $roomId): bool
    {
        $s_sql = <<< SQL
            select
                q1.hotel_cd
            from
                room_akafu_relation as ra
                inner join (
                    select
                        r2.hotel_cd,
                        r2.room_id
                    from
                        room2 as r2
                    where
                        r2.hotel_cd = :hotel_cd
                        and r2.room_id  = :room_id
                ) as q1
                    on ra.hotel_cd = q1.hotel_cd
                    and ra.room_id  = q1.room_id
        SQL;
        $a_rows = DB::select($s_sql, [
            'hotel_cd' => $hotelCd,
            'room_id' => $roomId,
        ]);

        return count($a_rows) > 0;
    }

    // MEMO: 移植元 C:\AeonCompass\hotel\svn_trunk\public\app\ctl\models\Hotel\Room2.php get_plan_media
    public function getPlanMedia($hotelCd, $planId)
    {
        $a_find_plan = Plan::where([
            'hotel_cd' => $hotelCd,
            'plan_id' => $planId
        ])->first();

        $s_sql = <<< SQL
            select
                q1.media_no,
                    q1.order_no,
                    (q1.order_no + 1) as order_no_plus,
                    (q1.order_no - 1) as order_no_minus,
                    m.label_cd,
                    m.title,
                    m.file_nm,
                    m.mime_type
            from
                media m
                inner join (
                    select
                        media_no,
                        order_no
                    from
                        plan_media
                    where 1 = 1
                        and hotel_cd = :hotel_cd
                        and plan_id  = :plan_id
                ) as q1
                    on m.media_no = q1.media_no
            where 1 = 1
                and m.hotel_cd = :hotel_cd2
            order by q1.order_no
        SQL;
        $a_rows = DB::select($s_sql, [
            'hotel_cd' => $hotelCd,
            'hotel_cd2' => $hotelCd,
            'plan_id'  => $planId
        ]);

        $a_find_plan->medias = $a_rows;

        return $a_find_plan;
    }

    // プランに紐付く部屋と部屋メディア情報取得
    // MEMO: 移植元 C:\AeonCompass\hotel\svn_trunk\public\app\ctl\models\Hotel\Room2.php get_plan_room_media
    public function getPlanRoomMedia($hotelCd, $planId)
    {
        $s_sql = <<< SQL
            select
                q3.room_id,
                q3.room_nm,
                q3.room_type,
                q3.accept_status,
                q3.room_order_no,
                q3.media_no,
                q3.order_no,
                m.label_cd,
                m.title,
                m.file_nm,
                m.mime_type
            from
                media as m
                right outer join (
                    select
                        q2.room_id,
                        q2.room_nm,
                        q2.room_type,
                        q2.accept_status,
                        q2.room_order_no,
                        rm2.media_no,
                        rm2.order_no
                    from
                        room_media2 as rm2
                        right outer join (
                            select
                                r2.room_id,
                                r2.room_nl as room_nm,
                                r2.room_type,
                                r2.accept_status,
                                r2.order_no as room_order_no
                            from
                                room2 as r2 
                                inner join (
                                    select
                                        room_id
                                    from
                                        room_plan_match
                                    where 1 = 1
                                        and hotel_cd = :hotel_cd1
                                        and plan_id  = :plan_id
                                ) as q1
                                    on r2.room_id  = q1.room_id
                            where 1 = 1
                                and r2.hotel_cd = :hotel_cd2
                                and r2.display_status = 1
                        ) as q2
                            on rm2.room_id = q2.room_id
                    where 1 = 1
                        and rm2.hotel_cd = :hotel_cd3
                    order by
                        rm2.order_no
                ) as q3
                    on m.media_no = q3.media_no
            where 1 = 1
                and m.hotel_cd = :hotel_cd4
            order by
                q3.room_order_no,
                q3.order_no
        SQL;
        $a_rows = DB::select($s_sql, [
            'hotel_cd1' => $hotelCd,
            'hotel_cd2' => $hotelCd,
            'hotel_cd3' => $hotelCd,
            'hotel_cd4' => $hotelCd,
            'plan_id'   => $planId,
        ]);
        $a_result = [];

        // 整形
        foreach ($a_rows as $a_row) {
            // // メディア情報
            // $a_media = [];
            // $a_media['media_no']  = $a_row['media_no'];
            // $a_media['order_no']  = $a_row['order_no'];
            // $a_media['label_cd']  = $a_row['label_cd'];
            // $a_media['title']     = $a_row['title'];
            // $a_media['file_nm']   = $a_row['file_nm'];
            // $a_media['mime_type'] = $a_row['mime_type'];

            // // プランメディア情報
            // $a_result[$a_row['room_id']]['room_id']       = $a_row['room_id'];
            // $a_result[$a_row['room_id']]['room_nm']       = $a_row['room_nm'];
            // $a_result[$a_row['room_id']]['room_type']     = $a_row['room_type'];
            // $a_result[$a_row['room_id']]['accept_status'] = $a_row['accept_status'];
            // $a_result[$a_row['room_id']]['room_order_no'] = $a_row['room_order_no'];
            // $a_result[$a_row['room_id']]['medias'][]      = $a_media;

            // メディア情報
            $a_media = [
                'media_no'  => $a_row->media_no,
                'order_no'  => $a_row->order_no,
                'label_cd'  => $a_row->label_cd,
                'title'     => $a_row->title,
                'file_nm'   => $a_row->file_nm,
                'mime_type' => $a_row->mime_type,
            ];

            // プランメディア情報
            $a_result[$a_row->room_id]['room_id']       = $a_row->room_id;
            $a_result[$a_row->room_id]['room_nm']       = $a_row->room_nm;
            $a_result[$a_row->room_id]['room_type']     = $a_row->room_type;
            $a_result[$a_row->room_id]['accept_status'] = $a_row->accept_status;
            $a_result[$a_row->room_id]['room_order_no'] = $a_row->room_order_no;
            $a_result[$a_row->room_id]['medias'][]      = $a_media;
        }

        return $a_result;
    }

    /**
     * フォトギャラリー画像削除
     *
     * @param string $hotelCd
     * @param int $mediaNo
     * @return bool
     */
    public function removeGalleryMedia($hotelCd, $mediaNo): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $mediaNo) {
                HotelMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'type'      => HotelMedia::TYPE_OTHER,
                    'media_no'  => $mediaNo,
                ])->delete();
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }

    /**
     * 部屋画像削除
     *
     * @param string $hotelCd
     * @param string $roomId
     * @param int $mediaNo
     * @return bool
     */
    public function removeRoomMedia($hotelCd, $roomId, $mediaNo): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $roomId, $mediaNo) {
                RoomMedia2::where([
                    'hotel_cd'  => $hotelCd,
                    'room_id'   => $roomId,
                    'media_no'  => $mediaNo,
                ])->delete();
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }

    /**
     * プラン画像削除
     *
     * @param string $hotelCd
     * @param string $planId
     * @param int $mediaNo
     * @return bool
     */
    public function removePlanMedia($hotelCd, $planId, $mediaNo): bool
    {
        try {
            DB::transaction(function () use ($hotelCd, $planId, $mediaNo) {
                PlanMedia::where([
                    'hotel_cd'  => $hotelCd,
                    'plan_id'   => $planId,
                    'media_no'  => $mediaNo,
                ])->delete();
            });
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
        return true;
    }
}
