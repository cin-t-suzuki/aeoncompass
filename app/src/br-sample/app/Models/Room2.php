<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room2 extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'room2';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, room_id
     * MEMO: Laravel は複合主キーに対応していない
     *
     * @var string
     */
    // protected $primaryKey = 'hotel_cd';

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';


    // カラム定数
    public const ROOM_TYPE_CAPSULE                      = 0;    // カプセル
    public const ROOM_TYPE_SINGLE                       = 1;    // シングル
    public const ROOM_TYPE_TWIN                         = 2;    // ツイン
    public const ROOM_TYPE_SEMI_DOUBLE                  = 3;    // セミダブル
    public const ROOM_TYPE_DOUBLE                       = 4;    // ダブル
    public const ROOM_TYPE_TRIPLE                       = 5;    // トリプル
    public const ROOM_TYPE_4_BED                        = 6;    // 4ベッド
    public const ROOM_TYPE_SWEET                        = 7;    // スイート
    public const ROOM_TYPE_MAISONNETTE                  = 8;    // メゾネット
    public const ROOM_TYPE_JAPANESE_STYLE               = 9;    // 和室
    public const ROOM_TYPE_JAPANESE_AND_WESTERN_STYLES  = 10;   // 和洋室
    public const ROOM_TYPE_OTHER                        = 11;   // その他
}
