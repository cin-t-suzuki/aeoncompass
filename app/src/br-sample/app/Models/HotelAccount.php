<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * 施設認証
 */
class HotelAccount extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_account';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'hotel_cd';

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
     * ガードの設定
     *
     * @var string
     */
    protected $guard = 'hotel';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'account_id',
        'account_id_begin',
        'password',
        'accept_status',
        'entry_cd',
        'modify_cd',
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

    // カラム定数
    public const ACCEPT_STATUS_NG   = 0; // 利用不可
    public const ACCEPT_STATUS_OK   = 1; // 利用可

    // カラム
    public string $COL_HOTEL_CD = 'hotel_cd';
    public string $COL_ACCOUNT_ID_BEGIN = 'account_id_begin';
    public string $COL_ACCOUNT_ID = 'account_id';
    public string $COL_PASSWORD = 'password';
    public string $COL_ACCEPT_STATUS = 'accept_status';

    /**
     * 主キーで取得
     *
     * HACK: 新たに実装する箇所では使わない。
     *      HotelAccount::where(条件)->get() や HotelAccount::find(主キー) などを使う。
     */
    public function selectByKey($hotelCd)
    {
        $data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_HOTEL_CD => $data[0]->hotel_cd,
                $this->COL_ACCOUNT_ID_BEGIN => $data[0]->account_id_begin,
                $this->COL_ACCOUNT_ID => $data[0]->account_id,
                $this->COL_PASSWORD => $data[0]->password,
                $this->COL_ACCEPT_STATUS => $data[0]->accept_status
            ];
        }
        return [];
    }
}
