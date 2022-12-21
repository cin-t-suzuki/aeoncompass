<table border="1" cellspacing="0" cellpadding="3"  width="600" style="margin: auto">
  <tr>
    <td  bgcolor="#EEEEFF"  colspan="2" align="center">
      <strong>部屋の管理</strong>
    </td>
  </tr>
  
  {{-- 新画面利用施設にのみ表示 --}}
  @if (!in_array(1, ($v->assign->is_disp_room_plan_list ?? [])))
    <tr>
      <td colspan="2" align="center">
        <table border="0" cellpadding="4" cellspacing="0">
          <tr align="center">
            <td>
              <form action="{$v->env.source_path}{$v->env.module}/htlsroomoffer/" method="POST" style="display:inline;">
                <input type="hidden" name="target_cd"        value="{{$views->target_cd}}" />
                <input type="submit" value="室数・料金・期間の調整" />
              </form>
            </td>
            <td>
              <form action="{$v->env.source_path}{$v->env.module}/htlreserve/" method="POST" style="display:inline;">
                <input type="hidden" name="target_cd"        value="{{$views->target_cd}}" />
                <input type="submit" value="予約情報の確認" />
              </form>
            </td>
          </tr>
          <tr>
            <td>
              <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="POST" style="display:inline;">
                <input type="hidden" name="target_cd"        value="{{$views->target_cd}}" />
                <input type="submit" value="プランメンテナンス" />
              </form>
            </td>
            <td>
              <form action="{$v->env.source_path}{$v->env.module}/htlextend/" method="POST">
                <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                <input type="hidden" name="display_type" value="htls" />
                <input type="submit" value="販売自動延長">
              </form>
            </td>
            <td>
              <form action="{$v->env.source_path}{$v->env.module}/htlsextendoffer/edit/" method="POST" style="display:inline;">
                <input type="hidden" name="target_cd" value="{{$views->target_cd}}" />
                <input type="hidden" name="plan_id" value="" />
                <input type="submit" value="期間延長" /><font color="#ff0000">NEW!!</font>
              </form>  
           </td>
             
          </tr>
        </table>
      </td>
    </tr>
  @else
    <form action="{$v->env.source_path}{$v->env.module}/htlreroom2/" method="POST">
      <tr>
        <td colspan="2" align="center"><p><br></p>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
            <input type="submit" value="簡単増返室・料金変更">
            <p><br></p>
        </td>
      </tr>
    </form>
  @endif
  
  <tr>
    <td width="40%">予約受付状態の変更</td>
    <form action="{$v->env.source_path}{$v->env.module}/htlacceptance/edit/" method="POST">
      <td>
        <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
        <input type="submit" value="変更">　
        {{--一時的にnull追記 {if $v->user->hotel.accept_status == 1} --}}
        @if ($views->hotel['accept_status'] ??null == 1)
          <font color="#0000ff">予約受付中</font>
        @else
          <font color="#ff0000">予約受付停止中</font>
        @endif
      </td>
    </form>
  </tr>
  
  {{-- 旧画面利用施設にのみ表示 --}}
  @if (in_array(1, ($views->is_disp_room_plan_list ?? [])))
    <tr>
      <td>旧部屋管理総合ページ</td>
      <td>
        <table border="0" cellspacing="0" cellpading="0">
          <tr>
            <form action="{$v->env.source_path}{$v->env.module}/htlstock/" method="POST">
              <td>
                <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
                <input type="submit" value="表示">
              </td>
            </form>
            <form action="{$v->env.source_path}{$v->env.module}/htlstock/dispcondition/" method="POST">
              <td>
                <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
                <input type="hidden" name="partner_group_id" value="{$v->helper->form->strip_tags($v->assign->partner_group_id)}" />
                <input type="submit" value="表示条件を指定する">
              </td>
            </form>
          </tr>
        </table>
        <small><font color="#339933">登録件数が多い場合は条件を指定してご利用下さい。</font></small>
      </td>
    </tr>
    <tr>
      <td>部屋プランメンテナンス</td>
      <td>
        <form method="post" action="{$v->env.source_path}{$v->env.module}/htlroomplan/" style="margin: 0pt;">
          <input value="表示" type="submit">
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
          <input name="partner_group_id" value="0" type="hidden">
          <input name="display_status" value="1" type="hidden">
        </form>
      </td>
    </tr>
    <tr>
      <td>当日料金の設定</td>
      <form action="{$v->env.source_path}{$v->env.module}/htlroomcharge/chargetoday/" method="POST">
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
      <td><input type="submit" value="変更"><br><small>本日の特別料金を設定します。（当日料金が割引価格の場合は優先表示の対象となります）</small></td>
      </form>
    </tr>
    <tr>
      <td>部屋数・料金の月別一覧表</td>
      <form action="{$v->env.source_path}{$v->env.module}/htlroomcharge/listshow/" method="POST">
        <td nowrap>
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
          <input type="submit" value="表示">
        </td>
      </form>
    </tr>
  @else
        @if ($views->stock_type == 0)
            <tr>
              <td>当日料金の設定</td>
              <form action="{$v->env.source_path}{$v->env.module}/htlschargetoday/" method="POST">
                  <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
              <td><input type="submit" value="変更"><br><small>本日の特別料金を設定します。（当日料金が割引価格の場合は優先表示の対象となります）</small></td>
              </form>
            </tr>
        @endif
  @endif
  {{--不要？？ @if ($views->stock_type == 0)
  <tr>
    <td>キャンペーン</td>
    <form action="{$v->env.source_path}{$v->env.module}/htlcamp/" method="POST">
      <td>
        <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
        <input type="submit" value="表示">
      </td>
    </form>
  </tr>
  @endif --}}
</table>