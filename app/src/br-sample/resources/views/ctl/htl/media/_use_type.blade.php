{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_use_type.tpl --}}

<!-- 外観 -->
{if $is_use.hotel}
  外観<br />
{/if}
<!-- 地図 -->
{if $is_use.map}
  地図<br />
{/if}
<!-- その他 -->
{if $is_use.other}
  フォトギャラリー<br />
{/if}
<!-- 部屋 -->
{if $is_use.room}
  部屋<br />
{/if}
<!-- プラン -->
{if $is_use.plan}
  プラン<br />
{else}
  <br />
{/if}