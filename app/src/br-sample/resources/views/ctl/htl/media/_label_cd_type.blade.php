{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_label_cd_type.tpl --}}

<!-- 外観 -->
{if $label_cd|substr:0:1 == '1'}
  <font color="#FF9999" title="外観">■</font>
{/if}
<!-- 地図 -->
{if $label_cd|substr:1:1 == '1'}
  <font color="#FFCC66" title="地図">■</font>
{/if}
<!-- フォトギャラリー -->
{if $label_cd|substr:2:1 == '1'}
  <font color="#99FF99" title="フォトギャラリー">■</font>
{/if}
<!-- 客室 -->
{if $label_cd|substr:3:1 == '1'}
  <font color="#66CCFF" title="客室">■</font>
{/if}
<!-- その他 -->
{if $label_cd|substr:4:1 == '1'}
  <font color="#FF99FF" title="その他">■</font>
{/if}
<!-- ラベル無し -->
{if $label_cd == '00000'}
  <font color="#cccccc" title="ラベル無し">■</font>
{/if}