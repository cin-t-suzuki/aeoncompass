<table border="1" cellspacing="0" cellpadding="3" width="600" style="margin: auto">
  <tr>
    <td  bgcolor="#EEEEFF"  colspan="2" align="center">
<strong>基本情報の管理</strong></td>
  </tr>
  @if ($views->stock_type != 3)

  <tr>
    <form action="{$v->env.source_path}{$v->env.module}/redirect/rsvhotel/" method="POST" target="_blank">
    <td width="40%">施設情報ページ<br /><small>（お客様確認内容）</small></td>
    <td>
      <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
      <input type="submit" value="表示"> <small>お客様が確認される施設情報ページを表示します。</small>
    </td>
    </form>
  </tr>

  @endif
  <tr>
    <td nowrap>施設情報の変更</td>
    <form action="{$v->env.source_path}{$v->env.module}/htlhotel/show/" method="POST">
    <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td>
        <input type="submit" value="変更"> <small>住所、電話番号、設備、備品等の変更</small>
    </td>
    </form>
  </tr>

@if ($views->stock_type == 0 || $views->stock_type == 3)

  <tr>
    <td>画像管理</td>
    {{-- null追記でいいか --}}
    @if (!$service->is_empty($views->is_disp_room_plan_list ?? null) && in_array(2, $views->is_disp_room_plan_list))
      <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/list/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
        <td><input type="submit" value="変更"> <small>画像の登録、変更、削除の設定</small></td>
      </form>
    @else
      <form action="{$v->env.source_path}{$v->env.module}/htlmedia/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
        <td><input type="submit" value="変更"> <small>画像の登録、変更、削除の設定</small></td>
      </form>
    @endif
  </tr>

@endif
</table>

<br>

@if ($views->stock_type == 0)

<table border="1" cellspacing="0" cellpadding="3" width="600" style="margin: auto">
  <tr>
    <td  bgcolor="#eeffee" colspan="2" align="center">
<strong>担当者情報・メール情報の管理</strong></td>
  </tr>

  <tr>
    <td bgcolor="#eeffee"  width="40%">各種メール設定</td>
  <form action="{$v->env.source_path}{$v->env.module}/htlmaillist/list" method="POST">
    <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td><input type="submit" value="変更"></td>
  </form>
  </tr>

  <tr>
    <td bgcolor="#eeffee">ＩＤとパスワードの変更</td>
  <form action="{$v->env.source_path}{$v->env.module}/htlchangepass/" method="POST">
    <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td><input type="submit" value="変更"></td>
  </form>
  </tr>

@endif
</table>
