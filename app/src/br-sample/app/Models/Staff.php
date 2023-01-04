<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'staff';

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
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        // 'staff_id',
        // 'staff_nm',
        // 'staff_cd',
        // 'email',
        // 'staff_status',
        // 'entry_cd',
        // 'entry_ts',
        // 'modify_cd',
        // 'modify_ts',
    ];

    // リレーション (hasOne) 設定
    public function staffAccount()
    {
        return $this->hasOne(StaffAccount::class, 'staff_id');
    }
}
