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

    // カラム
    const COL_STAFF_ID  = "staff_id";
    const COL_STAFF_NM  = "staff_nm";
    const COL_STAFF_CD  = "staff_cd";
    const COL_STAFF_STATUS  = "staff_status";
    const COL_EMAIL  = "email";


    /**
     * コンストラクタ
     */
    public function __construct() //publicでいいか？使用しないが削除するとエラー
    {
        // カラム情報の設定
    }


    /**
     * 主キーで取得
     */
    public function selectByKey($staff_id)
    {
        $data = $this->where("staff_id", $staff_id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_STAFF_ID  => $data[0]->staff_id,
                self::COL_STAFF_NM  => $data[0]->staff_nm,
                self::COL_STAFF_CD  => $data[0]->staff_cd,
                self::COL_STAFF_STATUS  => $data[0]->staff_status,
                self::COL_EMAIL  => $data[0]->email
            ];
        }
        return null;
    }
    
}
