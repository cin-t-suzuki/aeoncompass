{{--TODO @if $no_print == true}<div class="noprint">{/if--}
<br>
{{-- ログインしていれば --}}
{{--@if $v->user->operator->is_login() == true--}}
<table border="0" WIDTH="100%" cellspacing="0" cellpadding="0" bgcolor="#EEFFEE" >
  <tr>
    <td  bgcolor="#EEFFEE" >
      {{--TODO if !$v->user->operator->is_staff()
        <small>
          操作者変更（<A HREF="{$v->env.source_path}{$v->env.module}/logout/">Logout</A>）
        </small>
      {else}
        {if $v->env.controller == "brtop" and $v->env.action == "index"}
          <small>
            操作者変更（<A HREF="{$v->env.source_path}{$v->env.module}/logout/">Logout</A>）
          </small>
        {/if}
      {/if}--}}
    </td>
    <td bgcolor="#EEFFEE" ALIGN="right">
      <small>
        画面更新日時({{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }})
      </small>
    </td>
  </tr>
</table>
{{--TODO /if--}}
{{--if $no_print == true}</div>{/if--}}
</body>
</HTML>