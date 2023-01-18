<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenyList extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'deny_list';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'deny_cd';

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
        'deny_cd',
        'partner_cd',
        'hotel_cd',
        'deny_type',
        'entry_cd',
        'modify_cd',
    ];

    // カラム定数
    public const DENY_TYPE_HOTEL        = 0; // 施設
    public const DENY_TYPE_PARTNER      = 1; // 提携先
    public const DENY_TYPE_OPERATION    = 2; // 運用
}
