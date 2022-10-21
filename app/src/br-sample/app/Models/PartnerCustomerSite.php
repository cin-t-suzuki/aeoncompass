<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCustomerSite extends Model
{
    // use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'partner_customer_site';

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     * 
     * MEMO: (customer_id, site_cd, fee_type) で PK になっているが、
     * Laravel では複合キーに対応していない
     */
    protected $primaryKey = 'site_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

}
