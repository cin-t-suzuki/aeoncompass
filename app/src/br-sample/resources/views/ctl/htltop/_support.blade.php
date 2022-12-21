<table border="1" cellspacing="0" cellpadding="3" width="600" style="margin: auto">
  <tr>
    <td  bgcolor="#ffeded"  colspan="2" align="center">
<strong>予約の管理と会員へのサポート</strong></td>
  </tr>
  <tr>
    <td width="40%" bgcolor="#ffeded">予約情報の確認</td>
  <form action="{$v->env.source_path}{$v->env.module}/htlreserve/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td>
      <input type="submit" value="検索">
<small>予約の検索と電話キャンセルへの対応</small>
<br>
<font color="#660000">キャンセルしますと、お部屋が再販されます。</font>

      </td>
  </form>
  </tr>

@if ($views->stock_type == 0)
  <tr>
    <td bgcolor="#ffeded">送客実績・料金変更</td>
  <form action="{$v->env.source_path}{$v->env.module}/htlreserveck/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td>
      <input type="submit" value="検索">
<small>宿泊実績の確認、NOSHOWへの対応など</small><br>
<font color="#000000">（当日以前のキャンセル、Noshow処理）</font><br>
<font color="#660000">キャンセルしたプランが販売終了日時（手仕舞日時）を迎えていないと部屋は再販されます。</font>
    </td>
    </form>

  </tr>
  <tr>
    <td bgcolor="#ffeded">送客請求実績　確認</td>
    <form action="{$v->env.source_path}{$v->env.module}/htldemand/" method="POST">
      <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
      <td>
        <input type="submit" value="確認">
        <small>送客月ごとの請求実績と明細の確認</small>
      </td>
    </form>
  </tr>
  <tr>
    <td bgcolor="#ffeded"><font color="#000066">会員からの意見　確認と返答</font></td>

  <form action="{$v->env.source_path}{$v->env.module}/htlvoice/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td><input type="submit" value="表示"></td>
  </form>
  </tr>
@endif

  <tr>
    <td bgcolor="#ffeded"><font color="#000066">予約者へのメール送信履歴</td>
  <form action="{$v->env.source_path}{$v->env.module}/htlmailhistory/" method="POST">
        <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
    <td><input type="submit" value="表示"></td>

  </form>
  </tr>
</table>