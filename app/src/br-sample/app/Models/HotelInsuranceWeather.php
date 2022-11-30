<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelInsuranceWeather extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_insurance_weather';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'hotel_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * HACK: auto increment でもよいのではないか？
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
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'insurance_status',
        'amedas_cd',
        'entry_cd',
        'modify_cd',
    ];

    // カラム定数
    public const INSURANCE_STATUS_STOP_ETERNAL  = -1;   // 加入停止（ずっと）
    public const INSURANCE_STATUS_STOP_WINTER   = 0;    // 加入停止（冬季10月から3月）
    public const INSURANCE_STATUS_AVAILABLE     = 1;    // 加入可
}
