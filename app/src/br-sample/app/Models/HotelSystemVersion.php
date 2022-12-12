<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSystemVersion extends Model
{
    use HasFactory;

    protected $table = 'hotel_system_version';
    /**
     * テーブルに関連付ける主キー
     *
     * MEMO: (hotel_cd, system_type) で PK になっているが、
     * Laravel では複合キーに対応していない
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
        'system_type',
        'version',
        'entry_cd',
        // 'entry_ts',
        'modify_cd',
        // 'modify_ts',
    ];

    // カラム定数
    // システムページタイプ
    public const SYSTEM_TYPE_PLAN = 'plan'; // プランメンテナンス

    // システムバージョン（複数選択可）
    // ビット列による集合表現で管理
    // public const VERSION_1 = 1; // Ver1 (旧システム) 使用しない
    public const VERSION_2 = 2; // Ver2 (新システム)
}
