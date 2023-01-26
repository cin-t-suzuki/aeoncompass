<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HotelLink extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_link';
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
        'branch_no',
        'type',
        'title',
        'url',
        'order_no',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];

    	// 削除処理
		//
		//  同一の施設で複数存在するものを更新します。
		//  これは異なる施設コードで施設情報ページを同一の内容にするためです。
		public function destroyAction($aa_conditions){

			$a_attributes = $aa_conditions;

			// 表示順序を繰り上げます。
			$s_sql =
<<<SQL
				select	hotel_link.hotel_cd,
						hotel_link.branch_no
				from	hotel_link,
					(
						select	hotel_cd,
								order_no
						from	hotel_link
						where	hotel_cd  = :hotel_cd
							and	branch_no = :branch_no
					) q1
				where	hotel_link.hotel_cd = q1.hotel_cd
					and	hotel_link.order_no > q1.order_no
SQL;

            $a_target = DB::select($s_sql, ['hotel_cd' => $a_attributes['hotel_cd'], 'branch_no'   => $a_attributes['branch_no']]);

            $decrement_link_delete = $this->where([
                'hotel_cd'   => $aa_conditions['hotel_cd'],
                'branch_no'  => $aa_conditions['branch_no']
            ])->delete();

            if (!$decrement_link_delete){
                return false;
            }

			for($i = 0; $i < count($a_target); $i++){
				$hotel_link = $this->where(['hotel_cd' => $a_target[$i]->hotel_cd, 'branch_no' => $a_target[$i]->branch_no])->first();

                $decrement_link_update = $this->where([
                    'hotel_cd'   => $hotel_link['hotel_cd'],
                    'branch_no'  => $hotel_link['branch_no']
    
                ])->update([
					'order_no'    => $hotel_link['order_no'] - 1,
					'modify_cd'   => basename(__FILE__) .'->'. __METHOD__,
					'modify_ts'   => now()
				]);

				if (!$decrement_link_update){
					return false;
				}
			}
        
        // 施設情報ページを更新に設定
        $this->hotel_modify($a_attributes);

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
