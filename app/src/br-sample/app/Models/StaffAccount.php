<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StaffAccount extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'staff_account';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'staff_id';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = true;
    public const CREATED_AT = 'entry_ts';
    public const UPDATED_AT = 'modify_ts';

    /**
     * ガードの設定
     *
     * @var string
     */
    protected $guard = 'staff';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        // 'staff_id',
        // 'account_id',
        // 'password',
        // 'accept_status',
        // 'entry_cd',
        // 'entry_ts',
        // 'modify_cd',
        // 'modify_ts',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    // カラム定数
    public const ACCEPT_STATUS_NG = 0; // 利用不可
    public const ACCEPT_STATUS_OK = 1; // 利用可
}
