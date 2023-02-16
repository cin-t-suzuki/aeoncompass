<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelStation extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_stations';
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

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'station_id',
        'traffic_way',
        'order_no',
        'minute',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    /**
     * 施設情報ページの更新依頼
     *
     * @param array $aa_attributes 施設テーブルへの登録データ内容
     * @return bool
     */
    public function hotelModify($aa_attributes)
    {
        $hotel_status = new HotelStatus();
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
