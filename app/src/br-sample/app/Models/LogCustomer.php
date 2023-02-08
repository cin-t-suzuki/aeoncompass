<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCustomer extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'log_customer';

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
        'customer_id',
        'branch_no',
        'section_nm',
        'person_post',
        'person_nm',
        'tel',
        'fax',
        'email',
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts'
    ];
}