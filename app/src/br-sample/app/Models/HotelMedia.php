<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelMedia extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_media';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, type, media_no
     *
     * MEMO: Laravel は複合主キーに対応していない
     * MEMO: 設定しない場合デフォルトで `id` カラムがあるものとして動作する。
     *      $primaryKey の値を参照する操作は、実行不可能。他の実装で代替。
     *
     * @var string
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
        'type',
        'media_no',
        'order_no',
    ];

    // カラム定数
    public const TYPE_HOTEL = 1; // 施設
    public const TYPE_MAP   = 2; // 地図
    public const TYPE_OTHER = 3; // その他
}
