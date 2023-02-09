<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertMailHotel extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'alert_mail_hotel';

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
    public $timestamps = true;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    protected $fillable = [
        'hotel_cd',
        'branch_no',
        'alert_system_cd',
        'customer_email_check',
        'email',
        'email_type',
        'email_notify',
        'note',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];
}
