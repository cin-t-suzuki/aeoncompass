<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelArea extends Model
{
    use HasFactory;
    
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_area';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, entry_no, area_id
     * MEMO: Laravel は複合主キーに対応していない
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
     * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
     * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
     *
     * @var bool
     */
    // public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'entry_no',
        'area_id',
        'area_type',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];
}
