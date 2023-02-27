<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Exception;

class RoomCount extends CommonDBModel
{

	/**
	 * モデルに関連付けるテーブル
	 *
	 * @var string
	 */
	protected $table = 'room_count';
	protected $primaryKey = ['hotel_cd', 'room_cd', 'date_ymd'];

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ROOM_CD = "room_cd";
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

	protected $fillable = [
		'hotel_cd',
		'room_cd',
		'date_ymd',
		'rooms',
		'reserve_rooms',
		'accept_status',
		'entry_cd',
		'entry_ts',
		'modify_cd',
		'modify_ts'
	];

	function __construct()
	{

		parent::__construct();

		// // 施設コード
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

		// // 部屋コード
		$colRoomCd = (new ValidationColumn())->setColumnName($this->COL_ROOM_CD, "部屋コード")->require()->length(0, 10)->notHalfKana()->numberAndUpperAlphabet();

		// // 宿泊日
		$colDateYmd = (new ValidationColumn())->setColumnName($this->COL_DATE_YMD, "宿泊日")->require()->correctDate();

		// // 在庫数
		$colRooms = (new ValidationColumn())->setColumnName($this->COL_ROOMS, "在庫数")->length(0, 3)->currencyOnly(); // 独自チェック（在庫数/予約部屋数の比較）はコントローラー側で行う

		// // 予約部屋数
		$colReserveRooms = (new ValidationColumn())->setColumnName($this->COL_RESERVE_ROOMS, "予約部屋数")->length(0, 3)->currencyOnly();

		// // 予約受付状態
		$colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "予約受付状態")->require();

		parent::setColumnDataArray([
			$colHotelCd,
			$colRoomCd,
			$colDateYmd,
			$colRooms,
			$colReserveRooms,
			$colAcceptStatus,
		]);
	}


	public function insert_htls_room_offer($a_attributes)
	{
		$room_count_insert = $this->create(
			[
				'hotel_cd' => $a_attributes['hotel_cd'],
				'room_cd'  => $a_attributes['room_cd'],
				'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd'])),
				'rooms'    => $a_attributes['edit_rooms'],
				'reserve_rooms' => 0,
				'accept_status' => 1,
				'entry_cd'  => "action_cd",  // TODO $this->box->info->env->action_cd
				'entry_ts'      => now(),
				'modify_cd' => "action_cd", // TODO $this->box->info->env->action_cd
				'modify_ts'     => now()
			]
		);
		$room_count_insert = true;

		if (!$room_count_insert) {
			return false;
		} else {
			return true;
		}
	}

	public function update_htls_room_offer($a_attributes)
	{
		if (isset($a_attributes['accept_status']) && $a_attributes['ui_type'] != 'accept') {
			$room_count_update = $this->where(
				[
					'hotel_cd' => $a_attributes['hotel_cd'],
					'room_cd' => $a_attributes['room_cd'],
					'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
				]
			)->update([
				'rooms' => $a_attributes['rooms'],
				'modify_cd' => $a_attributes['modify_cd'],
				'modify_ts' => $a_attributes['modify_ts'],
				'accept_status' => $a_attributes['accept_status'],
			]);
			$room_count_update = true;
		} elseif (isset($a_attributes['accept_status']) && $a_attributes['ui_type'] === 'accept') {
			$room_count_update = $this->where(
				[
					'hotel_cd' => $a_attributes['hotel_cd'],
					'room_cd' => $a_attributes['room_cd'],
					'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
				]
			)->update([
				'modify_cd' => $a_attributes['modify_cd'],
				'modify_ts' => $a_attributes['modify_ts'],
				'accept_status' => $a_attributes['accept_status']
			]);
			$room_count_update = true;
		} else {
			$room_count_update = $this->where(
				[
					'hotel_cd' => $a_attributes['hotel_cd'],
					'room_cd' => $a_attributes['room_cd'],
					'date_ymd' => date('Y/m/d H:i:s', strtotime($a_attributes['date_ymd']))
				]
			)->update([
				'rooms' => $a_attributes['rooms'],
				'modify_cd' => $a_attributes['modify_cd'],
				'modify_ts' => $a_attributes['modify_ts'],
			]);
			$room_count_update = true;
		}

		if (!$room_count_update) {
			return false;
		} else {
			return true;
		}
	}
}
