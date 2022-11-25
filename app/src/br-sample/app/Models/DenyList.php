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
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'entry_cd',
        'entry_ts',
        'modify_cd',
        'modify_ts',
    ];


}
