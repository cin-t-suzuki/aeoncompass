<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MastStation extends CommonDBModel
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'mast_stations';
}
