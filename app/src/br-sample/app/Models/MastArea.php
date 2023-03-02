<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MastArea extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'mast_area';

    // カラム
    const COL_AREA_ID              = "area_id";
    const COL_PARENT_AREA_ID       = "parent_area_id";
    const COL_AREA_NM              = "area_nm";
    const COL_ORDER_NO             = "order_no";
    const COL_AREA_TYPE              = "area_type";

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'area_id';

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
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
    ];

    const AREA_TYPE_JAPAN   = 0; // 日本全域 
    const AREA_TYPE_LARGE   = 1; // 大エリア 
    const AREA_TYPE_PREF    = 2; // 都道府県 
    const AREA_TYPE_MIDDLE  = 3; // 中エリア 
    const AREA_TYPE_SMALL   = 4; // 小エリア

    /**
     * 主キーで取得
     */
    public function selectByKey($area_id)
    {
        $data = $this->where($this->COL_WARDZONE_ID, $area_id)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_WARDZONE_ID => $data[0]->wardzone_id,
                $this->COL_WARDZONE_NM => $data[0]->wardzone_nm
            ];
        }
        return null;
    }

}
