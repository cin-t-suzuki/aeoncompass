<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Models\HotelModify;

/**
 * 施設情報
 */
class HotelInfo extends CommonDBModel
{
	protected $table = "hotel_info";

	/**
	 * テーブルに関連付ける主キー
	 *
	 * @var string
	 */
	protected $primaryKey = 'hotel_cd';

	/**
	 * モデルのIDを自動増分するか
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * モデルにタイムスタンプを付けるか
	 *
	 * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
	 * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
	 *
	 * @var bool
	 */
	public $timestamps = false;
	const CREATED_AT = 'entry_ts';
	const UPDATED_AT = 'modify_ts';

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_PARKING_INFO = "parking_info";
	public string $COL_CARD_INFO = "card_info";
	public string $COL_INFO = "info";

	protected $fillable = [
		'hotel_cd',
		'parking_info',
		'card_info',
		'info',
		'entry_cd',
		'entry_ts',
		'modify_cd',
		'modify_ts'
	];

	/** コンストラクタ
	 */
	function __construct()
	{
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colParkingInfo = new ValidationColumn();
		$colParkingInfo->setColumnName($this->COL_PARKING_INFO, "駐車場詳細")->length(0, 150)->notHalfKana();
		$colCardInfo = new ValidationColumn();
		$colCardInfo->setColumnName($this->COL_CARD_INFO, "カード利用条件")->length(0, 75)->notHalfKana();
		$colInfo = new ValidationColumn();
		$colInfo->setColumnName($this->COL_INFO, "特色")->require()->length(0, 1000)->notHalfKana();

		parent::setColumnDataArray([$colHotelCd, $colParkingInfo, $colCardInfo, $colInfo]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd)
	{
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if (!is_null($data) && count($data) > 0) {
			return [
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_PARKING_INFO => $data[0]->parking_info,
				$this->COL_CARD_INFO => $data[0]->card_info,
				$this->COL_INFO => $data[0]->info
			];
		}
		return null;
	}

	/**  キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByKey($con, $data)
	{
		$result = $con->table($this->table)->where($this->COL_HOTEL_CD, $data[$this->COL_HOTEL_CD])->update($data);
		if (!$result) {
			return "更新に失敗しました";
		}
		return "";
	}

	// 施設情報ページの更新依頼
	//
	//  as_hotel_cd       施設コード
	//  aa_attributes     施設*テーブルの登録データ内容
	public function hotel_modify($aa_attributes)
	{
		$hotel_status = new HotelStatus;
		$a_hotel_status = $hotel_status->where(['hotel_cd' => $aa_attributes['hotel_cd']])->first();

		// 解約状態の場合は必ず削除依頼
		if ($a_hotel_status['entry_status'] == 2) {
			$modify_status = 2;
		} else {
			$modify_status = 1;
		}

		// 施設情報ページを更新するに設定
		$hotel_modify = new HotelModify();
		$a_hotel_modify = $hotel_modify->where(['hotel_cd' => $aa_attributes['hotel_cd']])->first();

		if (empty($a_hotel_modify)) {
			$hotel_modify_create = $hotel_modify->create([
				'hotel_cd'      => $aa_attributes['hotel_cd'],
				'modify_status' => $modify_status,
				'entry_cd'      => $aa_attributes['entry_cd'],
				'entry_ts'      => $aa_attributes['entry_ts'],
				'modify_cd'     => $aa_attributes['modify_cd'],
				'modify_ts'     => $aa_attributes['modify_ts'],
			]);
			if (!$hotel_modify_create) {
				return false;
			}

			// 削除状態で無い場合に設定
		} else {
			$hotel_modify_upadte = $hotel_modify->where([
				'hotel_cd'      => $aa_attributes['hotel_cd']
			])->update([
				'modify_status' => $modify_status,
				'modify_cd'     => $aa_attributes['modify_cd'],
				'modify_ts'     => $aa_attributes['modify_ts'],
			]);

			if (!$hotel_modify_upadte) {
				return false;
			}
		}
	}
}
