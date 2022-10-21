<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateProgram extends Model
{
    // use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'affiliate_program';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     * 
     * MEMO: (affiliate_cd, reserve_system) で PK になっているが、 
     * Laravel では複合キーに対応していない
     */
    protected $primaryKey = 'affiliate_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

}
