<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Models\RoomCount;
use Illuminate\Support\Facades\DB;
use Exception;

class RoomCount2 extends CommonDBModel
{
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'room_count2';
    protected $primaryKey = ['hotel_cd', 'room_id', 'date_ymd'];

    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_ROOM_ID = "room_id";
    public string $COL_DATE_YMD = "date_ymd";
    public string $COL_ROOMS = "rooms";
    public string $COL_RESERVE_ROOMS = "reserve_rooms";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";
    public string $COL_ACCEPT_STATUS = "accept_status";
    public $timestamps = false;
    public $incrementing = false;


    function __construct()
    {
        parent::__construct();

        // 施設コード
        $colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

        // 部屋ID
        $colRoomId = (new ValidationColumn())->setColumnName($this->COL_ROOM_ID, "部屋ID")->require()->length(0, 10)->notHalfKana()->numberAndUpperAlphabet();

        // 宿泊日
        $colDateYmd = (new ValidationColumn())->setColumnName($this->COL_DATE_YMD, "宿泊日")->require()->correctDate();

        // 在庫数
        $colRooms = (new ValidationColumn())->setColumnName($this->COL_ROOMS, "在庫数")->length(0, 3)->currencyOnly(); // 独自チェック（在庫数/予約部屋数の比較）はコントローラー側で行う

        // 予約部屋数
        $colReserveRooms = (new ValidationColumn())->setColumnName($this->COL_RESERVE_ROOMS, "予約部屋数")->length(0, 3)->currencyOnly();

        // 予約受付状態
        $colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "予約受付状態")->require();

        parent::setColumnDataArray([
            $colHotelCd,
            $colRoomId,
            $colDateYmd,
            $colRooms,
            $colReserveRooms,
            $colAcceptStatus,
        ]);
    }

    protected $fillable = [
        'hotel_cd',
        'room_id',
        'date_ymd',
        'rooms',
        'reserve_rooms',
        'accept_status',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    public function dataInsert($a_attributes)
    {
        try {
            $s_sql =
                <<< SQL
    					select	room_cd
    					from	zap_room
    					where	hotel_cd = :hotel_cd
    						and	room_id  = :room_id
    SQL;
            $a_conditions = [];
            $a_conditions = ['hotel_cd' => $a_attributes['hotel_cd'], 'room_id' => $a_attributes['room_id']];
            $a_row = DB::select($s_sql, $a_conditions);
            $a_attributes['room_cd'] = $a_row[0]->room_cd;

            // room_countのバリデーション実行
            $o_validations = [];
            $o_validations['hotel_cd'] = $a_attributes['hotel_cd'];
            $o_validations['room_cd'] = $a_attributes['room_cd'];
            $o_validations['date_ymd'] = $a_attributes['date_ymd'];
            $o_validations['rooms'] = $a_attributes['edit_rooms'];

            // room_count
            $room_count = new RoomCount();
            $errorList = [];
            $errorList = $room_count->validation($o_validations);
            if (count($errorList) > 0) {
                $this->addErrorMessageArray($errorList);
                return false;
            }

            // room_count のinsert
            if (!$room_count->insertHtlsRoomOffer($a_attributes)) {
                return false;
            }

            // room_count2 のinsert
            $room_count2 = new RoomCount2();
            $room_count2_insert = $room_count2->create(
                [
                    'hotel_cd' => $a_attributes['hotel_cd'],
                    'room_id'  => $a_attributes['room_cd'],
                    'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd'])),
                    'rooms'    => $a_attributes['edit_rooms'],
                    'reserve_rooms' => 0,
                    'accept_status' => 1,
                    'entry_cd'  => $a_attributes['entry_cd'],
                    'entry_ts'      => now(),
                    'modify_cd' => $a_attributes['modify_cd'],
                    'modify_ts'     => now()
                ]
            );

            if (is_null($room_count2_insert)) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function dataUpdate($a_attributes)
    {
        try {
            $s_sql =
                <<< SQL
    					select	room_cd
    					from	zap_room
    					where	hotel_cd = :hotel_cd
    						and	room_id  = :room_id
    SQL;
            $a_conditions = [];
            $a_conditions = ['hotel_cd' => $a_attributes['hotel_cd'], 'room_id' => $a_attributes['room_id']];
            $a_row = DB::select($s_sql, $a_conditions);
            $a_attributes['room_cd'] = $a_row[0]->room_cd;
            $room_count = new RoomCount();
            $room_count_date_ymd = date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']));

            $room_count->where([
                'hotel_cd' => $a_attributes['hotel_cd'],
                'room_cd'  => $a_attributes['room_cd'],
                'date_ymd' => $room_count_date_ymd,
            ])->first();

            // room_countのバリデーション実行
            $o_validations = [];
            $o_validations['hotel_cd'] = $a_attributes['hotel_cd'];
            $o_validations['room_cd'] = $a_attributes['room_cd'];
            $o_validations['date_ymd'] = $a_attributes['date_ymd'];

            if (!isset($a_attributes['accept_status'])) {
                $o_validations['rooms'] = $a_attributes['rooms'];
            }

            $room_count = new RoomCount();
            $errorList = [];
            $errorList = $room_count->validation($o_validations);
            if (count($errorList) > 0) {
                $this->addErrorMessageArray($errorList);
                return false;
            }
            // room_countのupdate
            if (!$room_count->updateHtlsRoomOffer($a_attributes)) {
                return false;
            }

            // room_count2 のupdate
            $room_count2 = new RoomCount2();

            if (isset($a_attributes['accept_status']) && $a_attributes['ui_type'] != 'accept') {
                $room_count2->where(
                    [
                        'hotel_cd' => $a_attributes['hotel_cd'],
                        'room_id' => $a_attributes['room_cd'],
                        'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
                    ]
                )->update(
                    [
                        'rooms' => $a_attributes['rooms'],
                        'modify_cd' => $a_attributes['modify_cd'],
                        'modify_ts' => $a_attributes['modify_ts'],
                        'accept_status' => $a_attributes['accept_status'],
                    ]
                );
            } elseif (isset($a_attributes['accept_status']) && $a_attributes['ui_type'] === 'accept') {
                $room_count2->where(
                    [
                        'hotel_cd' => $a_attributes['hotel_cd'],
                        'room_id' => $a_attributes['room_cd'],
                        'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
                    ]
                )->update(
                    [
                        'modify_cd' => $a_attributes['modify_cd'],
                        'modify_ts' => $a_attributes['modify_ts'],
                        'accept_status' => $a_attributes['accept_status']
                    ]
                );
            } else {
                $room_count2->where(
                    [
                        'hotel_cd' => $a_attributes['hotel_cd'],
                        'room_id' => $a_attributes['room_cd'],
                        'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
                    ]
                )->update(
                    [
                        'rooms' => $a_attributes['rooms'],
                        'modify_cd' => $a_attributes['modify_cd'],
                        'modify_ts' => $a_attributes['modify_ts']
                    ]
                );
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
