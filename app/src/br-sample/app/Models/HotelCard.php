<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Models\HotelModify;
use App\Models\HotelElementRemoved;

class HotelCard extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_card';
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
    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'card_id',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];
    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_CARD_ID = "card_id";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";

    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
        $colCardId = new ValidationColumn();
        $colCardId->setColumnName($this->COL_CARD_ID, "カードID")->require()->length(0, 2)->intOnly();


        parent::setColumnDataArray([$colHotelCd, $colCardId]);
    }


    // 追加処理
    //
    //  同一の施設で複数存在するものを更新します。
    //  これは異なる施設コードで施設情報ページを同一の内容にするためです。
    public static function saveAction($aa_conditions)
    {

        $a_attributes = $aa_conditions;
        $o_model_hotel_card_save = new HotelCard();
        $hotel_card_insert = $o_model_hotel_card_save->create(
            [
                'hotel_cd' => $a_attributes['hotel_cd'],
                'card_id' => $a_attributes['card_id'],
                'entry_cd' => $a_attributes['entry_cd'],
                'entry_ts' => $a_attributes['entry_ts'],
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => $a_attributes['modify_ts']
            ]
        );

        if (!$hotel_card_insert) {
            return false;
        }

        // 施設情報ページを更新に設定
        $o_model_hotel_card_save->hotel_modify($a_attributes);

        return true;
    }

    // 削除処理
    //
    //  同一の施設で複数存在するものを更新します。
    //  これは異なる施設コードで施設情報ページを同一の内容にするためです。
    public static function destroyAction($aa_conditions)
    {
        $a_attributes = $aa_conditions;
        // リレーションが存在しないことがあるので最初に更新
        $o_model_hotel_card = new HotelCard();
        $o_model_hotel_card->where(
            [
                'hotel_cd' => $a_attributes['hotel_cd'],
                'card_id' => $a_attributes['card_id'],
            ]
        )->delete();

        // 施設情報ページを更新に設定
        $o_model_hotel_card->hotel_modify($a_attributes);

        // 施設属性削除情を更新（外部連携などで削除されたデータを反映）
        $o_model_hotel_card->hotel_element_removed($a_attributes['hotel_cd']);

        return true;
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

    // 施設情報ページの更新依頼
    //
    //  as_hotel_cd       施設コード
    protected function hotel_element_removed($as_hotel_cd)
    {

        $hotel_element_removed = new HotelElementRemoved();

        $a_hotel_element_removed = $hotel_element_removed->where(['hotel_cd' => $as_hotel_cd, 'table_name' => $this->table])->get();

        if (is_null($a_hotel_element_removed)) {

            $hotel_element_removed_create = $hotel_element_removed->create([
                'hotel_cd'      => $as_hotel_cd,
                'table_name'    => $this->table,
                'destroy_dtm'   => now(),
                'entry_cd'      => 'action_cd',  // $this->box->info->env->action_cd,
                'entry_ts'      => now(),
                'modify_cd'     => 'modify_cd',  // $this->box->info->env->action_cd,
                'modify_ts'     => now(),
            ]);

            if (!$hotel_element_removed_create) {
                return false;
            }
        } else {

            $hotel_element_removed_update = $hotel_element_removed
                ->where(
                    [
                        'hotel_cd' => $as_hotel_cd,
                        'table_name' => $this->table
                    ]
                )
                ->update([
                    'destroy_dtm'   => now(),
                    'modify_cd'     => 'action_cd',  // $this->box->info->env->action_cd,
                    'modify_ts'     => now(),
                ]);

            if (!$hotel_element_removed_update) {
                return false;
            }

            $a_hotel_element_removed = $hotel_element_removed->where(['hotel_cd' => $as_hotel_cd, 'table_name' => $this->table])->get();
        }
    }
}
