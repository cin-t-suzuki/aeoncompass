<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class HotelService extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_service';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * モデルにタイムスタンプを付けるか
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
        'element_id',
        'element_value_id',
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
        try {
            DB::beginTransaction();

            if (empty($a_hotel_modify)) {
                $hotel_modify->create([
                    'hotel_cd'      => $aa_attributes['hotel_cd'],
                    'modify_status' => $modify_status,
                    'entry_cd'      => $aa_attributes['entry_cd'],
                    'entry_ts'      => $aa_attributes['entry_ts'],
                    'modify_cd'     => $aa_attributes['modify_cd'],
                    'modify_ts'     => $aa_attributes['modify_ts'],
                ]);
            } else {
                $hotel_modify->where([
                    'hotel_cd'      => $aa_attributes['hotel_cd']
                ])->update([
                    'modify_status' => $modify_status,
                    'modify_cd'     => $aa_attributes['modify_cd'],
                    'modify_ts'     => $aa_attributes['modify_ts'],
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
