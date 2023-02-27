@if($no_print == true)<div class="noprint">@endif
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0" >
  <tr>
    <td colspan="2" width="100%"><hr size="1" width="100%"></td>
  </tr>
  <tr>
    <!-- {* ログインしていれば *} -->
    @if($is_login == true)
      <td nowrap>
        @if(!$is_staff)
          <small><a href="{$v->env.source_path}{$v->env.module}/logout/">ログアウト</a></small>
        @endif
      </td>
    @endif
    <td align="right"><small>画面更新日時({{date('Y-m-d H:i:s')}}) </small></td>

  </tr>
</table>
<br />
<small>(c)Copyright {{date('Y')}} BestReserve Co.,Ltd. All Rights Reserved.</small>
<br><br>
@if($no_print == true)</div>@endif
</body>
</HTML>

