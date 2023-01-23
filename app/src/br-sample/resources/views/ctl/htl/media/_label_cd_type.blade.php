{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_label_cd_type.tpl --}}

{{--
    HACK: (refactor, 工数次第) ビット列による集合管理のようなものを、文字列型で実装している。
    改修（ラベルの追加）に弱いため、整数型で書き換えるほうが理にかなっている。
    app/Models/Media.php を参照
--}}

{{-- 外観: 1____ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_OUTSIDE] == '1')
    <font color="#FF9999" title="外観">■</font>
@endif
{{-- 地図: _1___ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_MAP] == '1')
    <font color="#FFCC66" title="地図">■</font>
@endif
{{-- フォトギャラリー: __1__ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_INSIDE] == '1')
    <font color="#99FF99" title="フォトギャラリー">■</font>
@endif
{{-- 客室: ___1_ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_ROOM] == '1')
    <font color="#66CCFF" title="客室">■</font>
@endif
{{-- その他: ____1 --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_OTHER] == '1')
    <font color="#FF99FF" title="その他">■</font>
@endif
{{-- ラベル無し --}}
@if ($label_cd == '00000')
    <font color="#cccccc" title="ラベル無し">■</font>
@endif
