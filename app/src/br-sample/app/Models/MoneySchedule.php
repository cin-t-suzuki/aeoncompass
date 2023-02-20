<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneySchedule extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'money_schedule';

    /**
     * テーブルに関連付ける主キー
     *
     * MEMO: (ym, money_schedule_id) で PK になっているが、
     * Laravel では複合キーに対応していない
     *
     * @var string
     */
    // protected $primaryKey = 'ym';

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
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        // 'money_schedule_id',
        // 'ym',
        // 'date_ymd',
        // 'entry_cd',
        // 'modify_cd',
    ];

    // カラム定数
    public const MONEY_SCHEDULE_ID_CLOSING_DATE             = 1; // 締め日
    public const MONEY_SCHEDULE_ID_CUSTOMER_TRANSFER_DATE   = 2; // 送客日
    public const MONEY_SCHEDULE_ID_PAYMENT_EXPECTED_DATE    = 3; // 支払予定日
    public const MONEY_SCHEDULE_ID_TRANSFER_DEADLINE        = 4; // 振込期日
}
