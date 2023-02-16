<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Exception;
use Illuminate\Support\Facades\DB;

class HotelInform extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_inform';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     *
     * (hotel_cd, branch_no) で PK になっているが、
     * Laravel では複合キーに対応していない
     */
    // protected $primaryKey = 'hotel_cd';

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


    protected $fillable = [
        'hotel_cd',
        'branch_no',
        'inform_type',
        'inform',
        'order_no',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];

    /**
     * カラム
     */
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_BRANCH_NO = "branch_no";
    public string $COL_INFORM_TYPE = "inform_type";
    public string $COL_INFORM = "inform";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
        $colBranchNo = new ValidationColumn();
        $colBranchNo->setColumnName($this->COL_BRANCH_NO, "枝番")->require()->length(0, 2)->currencyOnly();
        $ColInformType = new ValidationColumn();
        $ColInformType->setColumnName($this->COL_INFORM_TYPE, "連絡タイプ")->require();
        $colInform = new ValidationColumn();
        $colInform->setColumnName($this->COL_INFORM, "連絡事項")->require()->notHalfKana()->length(0, 800);
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "連絡事項表示順序")->length(0, 2)->intOnly();

        parent::setColumnDataArray([$colHotelCd, $colBranchNo, $ColInformType, $colInform, $colOrderNo]);
    }

    /**
     * 削除処理
     *
     * 削除と同時に、表示順も整形
     *
     * @return bool
     */
    public function destroyAction($aa_conditions)
    {
        $a_attributes = $aa_conditions;

        // 表示順序を繰り上げます。
        $s_sql =
            <<<SQL
				select	hotel_inform.hotel_cd,
						hotel_inform.branch_no
				from	hotel_inform,
					(
						select	hotel_cd,
								order_no,
								inform_type
						from	hotel_inform
						where	hotel_cd    = :hotel_cd
							and	branch_no   = :branch_no
					) q1
				where	hotel_inform.hotel_cd    = q1.hotel_cd
					and	hotel_inform.inform_type = q1.inform_type
					and	hotel_inform.order_no    > q1.order_no
SQL;

        $a_target = DB::select($s_sql, ['hotel_cd' => $a_attributes['hotel_cd'], 'branch_no'   => $a_attributes['branch_no']]);

        $this->where([
            'hotel_cd'   => $aa_conditions['hotel_cd'],
            'branch_no'  => $aa_conditions['branch_no']
        ])->delete();

        for ($i = 0; $i < count($a_target); $i++) {
            $hotel_inform = $this->where(array('hotel_cd' => $a_target[$i]->hotel_cd, 'branch_no' => $a_target[$i]->branch_no))->first();

            $decrement_inform_update = $this->where([
                'hotel_cd'   => $hotel_inform['hotel_cd'],
                'branch_no'  => $hotel_inform['branch_no']

            ])->update([
                'order_no'    => $hotel_inform['order_no'] - 1,
                'modify_cd'   => basename(__FILE__) . '->' . __METHOD__,
                'modify_ts'   => now()
            ]);
            if (!$decrement_inform_update) {
                return false;
            }
        }

        // 施設情報ページを更新に設定
        $this->hotelModify($a_attributes);

        return true;
    }

    /**
     * 施設情報ページの更新依頼
     *
     * @param $aa_attributes 施設テーブルへの登録データ内容
     *
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
