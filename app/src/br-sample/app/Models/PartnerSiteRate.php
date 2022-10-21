<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerSiteRate extends Model
{
    // use HasFactory;
    
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'partner_site_rate';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     * (site_cd, accept_s_ymd, fee_type, stock_class) で PK になっているが、
     * Laravel では複合キーに対応していない
     */
    protected $primaryKey = 'site_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

    // TODO: implement __construct
}
