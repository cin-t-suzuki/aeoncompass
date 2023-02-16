<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecureLicense extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'secure_license';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'license_id';

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
        // 'license_id',
        // 'license_token',
        // 'applicant_staff_id',
        // 'approver_staff_id',
        // 'license_status',
        // 'license_limit_dtm',
        // 'entry_cd',
        // 'modify_cd',
    ];

    // カラム定数
    public const LICENSE_STATUS_VALID   = 0; // 有効
    public const LICENSE_STATUS_INVALID = 1; // 無効
}
