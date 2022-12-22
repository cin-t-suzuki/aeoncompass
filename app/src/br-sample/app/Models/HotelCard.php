<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelCard extends Model
{
    use HasFactory;
    
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_card';

}
