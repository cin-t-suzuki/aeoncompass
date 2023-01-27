{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_label_cd_type.tpl --}}

{{--
    HACK: (refactor, 工数次第) ビット列による集合管理のようなものを、文字列型で実装している。
    改修（ラベルの追加）に弱いため、整数型で書き換えるほうが理にかなっている。
    app/Models/Media.php を参照
--}}

{{-- 外観: 1____ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_OUTSIDE] == '1')
    <font title="外観" color="#FF9999">■</font>
@endif
{{-- 地図: _1___ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_MAP] == '1')
    <font title="地図" color="#FFCC66">■</font>
@endif
{{-- フォトギャラリー: __1__ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_INSIDE] == '1')
    <font title="フォトギャラリー" color="#99FF99">■</font>
@endif
{{-- 客室: ___1_ --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_ROOM] == '1')
    <font title="客室" color="#66CCFF">■</font>
@endif
{{-- その他: ____1 --}}
@if ($label_cd[\App\Models\Media::LABEL_CD_OTHER] == '1')
    <font title="その他" color="#FF99FF">■</font>
@endif
{{-- ラベル無し --}}
@if ($label_cd == '00000')
    <font title="ラベル無し" color="#cccccc">■</font>
@endif
