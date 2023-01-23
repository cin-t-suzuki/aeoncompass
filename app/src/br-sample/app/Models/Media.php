<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * テーブルに関連付ける主キー
     *
     * 複合主キー: hotel_cd, media_no
     * MEMO: Laravel は複合主キーに対応していない
     * MEMO: 設定しない場合デフォルトで `id` カラムがあるものとして動作する。
     *      $primaryKey の値を参照する操作は、実行不可能。他の実装で代替。
     *
     * @var string
     */
    // protected $primaryKey = 'hotel_cd';

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
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'hotel_cd',
        'media_no',
        'order_no',
        'label_cd',
        'title',
        'file_nm',
        'mime_type',
        'width',
        'height',
    ];

    /**
     * リレーション
     */
    public function mediaOriginal()
    {
        return $this->hasOne(MediaOrg::class);
    }

    /*
        0/1 からなる5文字の文字列で、5つのラベルの選択非選択の組み合わせパターンを表現している。
        label_cd の x 文字目 が '1' であれば、ラベル X が選択されている。
        (理論上は 2^5 = 32 パターン)

        HACK: (refactor, 工数次第?) 整数型の2進数表示による組み合わせパターン表現のほうがふさわしいか。
        新しいラベルを追加したいとなった時、整数型のほうがスムーズに改修できる。

        MEMO: 地図だけ特別扱いされている。地図 + その他 の選択パターンは存在しない。
        おそらくあとから追加された仕様。
    */
    public const LABEL_CD_OUTSIDE   = 0; // 外観
    public const LABEL_CD_MAP       = 1; // 地図
    public const LABEL_CD_INSIDE    = 2; // フォトギャラリー（館内）
    public const LABEL_CD_ROOM      = 3; // 客室
    public const LABEL_CD_OTHER     = 4; // その他

    public function isLabeledAs($labelTypeIndex): bool
    {
        return $this->label_cd[$labelTypeIndex] == '1';
    }
    public function isLabeledAsMap(): bool
    {
        return $this->label_cd == '01000';
    }
}
