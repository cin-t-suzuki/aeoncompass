<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMedia extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'room_media2';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, room_id, media_no
     * MEMO: Laravel は複合主キーに対応していない
     *
     * @var string
     */
    // protected $primaryKey = 'hotel_cd';
}
