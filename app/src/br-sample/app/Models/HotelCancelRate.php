<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HotelCancelRate extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_cancel_rate';

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = true;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'cancel_rate',
        'days',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
        'policy_status'
    ];

    // 追加処理
    //
    public function saveAction($a_attributes)
    {
        $hotel_cancel_rate_create = $this->create([
            'hotel_cd'      => $a_attributes['hotel_cd'],
            'days'          => $a_attributes['days'],
            'cancel_rate'   => $a_attributes['cancel_rate'],
            'policy_status' => $a_attributes['policy_status'],
            'entry_cd'      => $a_attributes['entry_cd'],
            'entry_ts'      => $a_attributes['entry_ts'],
            'modify_cd'     => $a_attributes['entry_cd'],
            'modify_ts'     => $a_attributes['entry_ts']
        ]);

        if (!$hotel_cancel_rate_create) {
            return false;
        }

        // 施設情報ページを更新に設定
        $this->hotel_modify($a_attributes);

        // 不泊の情報が存在するかチェック
        $s_sql =
            <<<SQL
					select	hotel_cd
					from	hotel_cancel_rate
					where	hotel_cd = :hotel_cd
						and	days = -1
SQL;

        $a_row = DB::select($s_sql, [$a_attributes['hotel_cd']]);

        // 不泊の情報が存在しない場合は自動で登録　※必須なため
        if (empty($a_row)) {
            $o_hotel_cancel_rate = new HotelCancelRate();

            $no_show_create = $o_hotel_cancel_rate->create([
                'hotel_cd'         => $a_attributes['hotel_cd'],
                'days'             => -1,
                'cancel_rate'      => 100,
                'policy_status'    => $a_attributes['policy_status'],
                'entry_cd'         => $a_attributes['entry_cd'],
                'entry_ts'         => $a_attributes['entry_ts'],
                'modify_cd'        => $a_attributes['modify_cd'],
                'modify_ts'        => $a_attributes['modify_ts']
            ]);

            if (!$no_show_create) {
                return false;
            }
        }
        return true;
    }

    // 施設情報ページの更新依頼
    //
    //  as_hotel_cd       施設コード
    //  aa_attributes     施設*テーブルの登録データ内容
    public function hotel_modify($aa_attributes)
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
