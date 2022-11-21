 {{-- priority_list表示用--}}
 <hr size="1"/>
 {{-- メッセージ --}}
 @section('message')
 @include('ctl.common.message', $messages)
 
   <table border="1" cellpadding="4" cellspacing="0" height="67">
     <tr>
       <td height="16" bgcolor="#EEFFEE">都道府県</td>
       {{-- null追記 --}}
       <td height="16">{{strip_tags($views->select_pref['pref_nm']??null)}}</td>
     </tr>
     <tr>
       <td height="16" bgcolor="#EEFFEE">宿泊対象期間</td>
       <td>
         @if ($views->priority_cd['span'] == 0)
           検索日から 0 - 6 日後
         @else
           検索日から 7 - 35 日後
         @endif
       </td>
     </tr>
   </table>
<br>
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#eeFFee">順番<br>入替</td>
      <td nowrap bgcolor="#eeFFee"><br></td>
      <td nowrap bgcolor="#EEFFEE">表示<br>順位</td>
      <td nowrap bgcolor="#EEFFEE">宿泊対象曜日</td>
    </tr>
    @if (count($views->priority_list) >= $views->priority_cnt)
      @php $loops = $views->priority_cnt @endphp
    @elseif (count($errors) > 0 && $views->priority_cd['action'] == "create")
      @php $loops = count($views->priority_list) @endphp
    @else
      @php $loops = count($views->priority_list) + 1 @endphp
    @endif
    {{--↑↓$loopだとループ変数に引っかかってしまう？ので$loopsに名前変更 {section loop=$loop start = 0 name = list} --}}
    @for ($list = array_key_first($views->priority_list); $list <= $loops; $list++)
  <tr>
    <td>
    {!! Form::open(['route' => ['ctl.brroomplanpriority2.sort'], 'method' => 'post']) !!} 
      {{-- null追記 --}}
      @if (!$service->is_empty($views->priority_list[$list]??null)) 
      <input type="hidden" name="priority[pref_id]" value={{strip_tags($views->select_pref['pref_id'])}}>
      <input type="hidden" name="priority[span]" value={{strip_tags($views->priority_cd['span'])}}>
      <input type="hidden" name="priority[priority]" value={{$list}}>      
      <input type="hidden" name="priority[other_priority]" value={{$list-1}}>
        @if (($list-1) >= 1)
          <input type="submit" value="↑">
        @endif
      @endif
    {!! Form::close() !!}
    {!! Form::open(['route' => ['ctl.brroomplanpriority2.sort'], 'method' => 'post']) !!} 
    {{-- null追記 --}}
    @if (!$service->is_empty($views->priority_list[$list]??null)) 
      <input type="hidden" name="priority[pref_id]" value={{strip_tags($views->select_pref['pref_id'])}}>
      <input type="hidden" name="priority[span]" value={{strip_tags($views->priority_cd['span'])}}>
      <input type="hidden" name="priority[priority]" value={{$list}}>
      <input type="hidden" name="priority[other_priority]" value={{$list+1}}>
        @if (count($views->priority_list) !== ($list))
          <input type="submit" value="↓">
        @endif      
    @endif
    {!! Form::close() !!}
    </td>

    {!! Form::open(['route' => ['ctl.brroomplanpriority2.registration'], 'method' => 'post']) !!} 
    <td nowrap>
    @if ($service->is_empty($views->priority_list[$list]??null))
      <input type="submit" value=" 登録する ">
    @else
      <input type="submit" value=" 更新する ">
    @endif
    </td>
    <td nowrap align="right">{{$list}}</td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0" bgcolor="#C0C0C0">
        <tr>
          <td>
            <table border="0" cellpadding="4" cellspacing="1">
              <tr>
                <td bgcolor="#FFFFFF">　</td>
                @foreach ($views->week as $wday => $week_nm)
                {{--null追記、他を参考に書き換え <td {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status) || ($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1)}bgcolor="#FFFFFF"{else}bgcolor="#EEEEEE"{/if}> --}}
                  <td @if ($service->is_empty($views->priority_list[$list][$wday]['display_status']??null) || ($views->priority_list[$list][$wday]['display_status'] == 1))bgcolor="#FFFFFF"@else bgcolor="#EEEEEE" @endif>
                    <table border="0" cellpadding="0" cellspacing="0" width="150">
                    <tr>
                        <td>
                          {{-- 土曜の場合 --}}
                          @if ($wday == 7)
                            <font color="#0000FF">{{$week_nm}}</font>
                          {{-- 日曜の場合 --}}
                          @elseif ($wday == 1)
                            <font color="#FF0000">{{$week_nm}}</font>
                          @else
                            {{$week_nm}}
                          @endif
                        </td>
                        <td align="right">
                          {{--null追記、他を参考に書き換え {if !is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].hotel_cd)}  --}}
                          @if (!$service->is_empty($views->priority_list[$list][$wday]['hotel_cd']??null)) 
                            {{-- null追記 --}}
                            @if (!$service->is_empty($views->priority_list[$list][$wday]['link_param']??null) and $views->priority_list[$list][$wday]['entry_status'] == 0)
                              <a target="_blank" href="http://{$v->config->system->rsv_host_name}/vacant/{$v->helper->form->strip_tags($v->assign->priority_list[$smarty.section.list.iteration][$wday].hotel_cd)}/{$v->helper->form->strip_tags($v->assign->priority_list[$smarty.section.list.iteration][$wday].room_id)}/{$v->helper->form->strip_tags($v->assign->priority_list[$smarty.section.list.iteration][$wday].plan_id)}/{$v->helper->form->strip_tags($v->assign->priority_list[$smarty.section.list.iteration][$wday].link_param)}">>></a>
                            @else
                              <span style="color:#cdcdcd;">>></span>
                            @endif
                          @endif
                          <br />
                        </td>
                      </tr>
                    </table>
                  </td>
                @endforeach
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" nowrap>施設コード</td>
                @foreach ($views->week as $wday => $week_nm)
                  {{--null追記、他を参考に書き換え <td nowrap {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status) || ($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1)}bgcolor="#FFFFFF"{else}bgcolor="#EEEEEE"{/if}> --}}
                  <td nowrap @if ($service->is_empty($views->priority_list[$list][$wday]['display_status']??null) || ($views->priority_list[$list][$wday]['display_status'] == 1))bgcolor="#FFFFFF"@else bgcolor="#EEEEEE"@endif>
                    {{-- {assign var="column_nm" value="hotel_cd`$wday`_`$smarty.section.list.iteration`"} --}}
                    @php $column_nm = "hotel_cd".$wday."_".$list; @endphp
                    {{--null追記、他を参考に書き換え {if $smarty.section.list.first != true && is_empty($v->assign->priority_list[$tmp_iteration][$wday])} --}}
                    @if ($list == 0 && $service->is_empty($views->priority_list[$tmp_iteration??null][$wday]??null))
                      <input type="text" size="20" maxlength="20" disabled style="background-color: #EEEEEE;">
                    @else
                      {{--仮置きで書き換え <input type="text" name="priority[{$column_nm}]" value="{$v->helper->form->set_default($v->assign->priority_list[$smarty.section.list.iteration][$wday].hotel_cd, 'priority', $column_nm)}" size="20" maxlength="20"> --}}
                      <input type="text" name="priority[{{$column_nm}}]" value="{{$views->priority_list[$list][$wday]['hotel_cd']??null}}" size="20" maxlength="20">
                    @endif<br>
                    {{--null追記、他を参考に書き換え {if !is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].hotel_nm)} --}}
                    @if (!$service->is_empty($v->assign->priority_list[$list][$wday]['hotel_nm']??null))
                      <small>
                        <font color="#999999">{{strip_tags($views->priority_list[$list][$wday]['hotel_nm'])}}</font>
                      </small>
                    @else
                      <br>
                    @endif
                  </td>
                @endforeach
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" nowrap>PMSコード（部屋）<br />部屋コード</td>
                @foreach ($views->week as $wday => $week_nm)
                  {{-- <td nowrap {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status) || ($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1)}bgcolor="#FFFFFF"{else}bgcolor="#EEEEEE"{/if}> --}}
                  <td nowrap @if ($service->is_empty($views->priority_list[$list][$wday]['display_status']??null) || ($views->priority_list[$list][$wday]['display_status'] == 1))bgcolor="#FFFFFF"@else bgcolor="#EEEEEE"@endif>
                    {{-- {assign var="column_nm" value="room_cd`$wday`_`$smarty.section.list.iteration`"} --}}
                    @php $column_nm = "room_cd".$wday."_".$list; @endphp
                    {{--null追記、他を参考に書き換え {if $smarty.section.list.first != true && is_empty($v->assign->priority_list[$tmp_iteration][$wday])} --}}
                    @if ($list == 0 && $service->is_empty($views->priority_list[$tmp_iteration??null][$wday]??null))
                      <input type="text" size="20" maxlength="20" disabled style="background-color: #EEEEEE;">
                    @else
                      {{-- <input type="text" name="priority[{$column_nm}]" value="{$v->helper->form->set_default($v->assign->priority_list[$smarty.section.list.iteration][$wday].room_cd, 'priority', $column_nm)}" size="20" maxlength="20"> --}}
                      <input type="text" name="priority[{{$column_nm}}]" value="{{$views->priority_list[$list][$wday]['room_cd']??null}}" size="20" maxlength="20">
                      @endif<br>
                    {{--null追記、他を参考に書き換え {if !is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].room_nm)} --}}
                    @if (!$service->is_empty($views->priority_list[$list][$wday]['room_nm']??null))
                      <small>
                        <font color="#999999">{{strip_tags($views->priority_list[$list][$wday]['room_nm'])}}</font>
                      </small>
                    @else
                      <br>
                    @endif
                  </td>
                @endforeach
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" nowrap>PMSコード（プラン）<br />プランコード</td>
                @foreach ($views->week as $wday => $week_nm)
                  {{--null追記、他を参考に書き換え <td nowrap {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status) || ($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1)}bgcolor="#FFFFFF"{else}bgcolor="#EEEEEE"{/if}> --}}
                    <td nowrap @if ($service->is_empty($views->priority_list[$list][$wday]['display_status']??null) || ($views->priority_list[$list][$wday]['display_status'] == 1))bgcolor="#FFFFFF" @else bgcolor="#EEEEEE"@endif>
                    {{-- {assign var="column_nm" value="plan_cd`$wday`_`$smarty.section.list.iteration`"} --}}
                    @php $column_nm = "plan_cd".$wday."_".$list; @endphp
                    {{--null追記、他を参考に書き換え {if $smarty.section.list.first != true && is_empty($v->assign->priority_list[$tmp_iteration][$wday])} --}}
                    @if ($list == 0 && $service->is_empty($views->priority_list[$tmp_iteration??null][$wday]??null))
                      <input type="text" size="20" maxlength="20" disabled style="background-color: #EEEEEE;">
                    @else
                      {{-- <input type="text" name="priority[{$column_nm}]" value="{$v->helper->form->set_default($v->assign->priority_list[$smarty.section.list.iteration][$wday].plan_cd, 'priority', $column_nm)}" size="20" maxlength="20"> --}}
                      {{-- set_default→??nullに仮置き --}}
                      <input type="text" name="priority[{{$column_nm}}]" value="{{$views->priority_list[$list][$wday]['plan_cd']??null}}" size="20" maxlength="20">
                    @endif<br>
                    {{--null追記、他を参考に書き換え {if !is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].plan_nm)} --}}
                    @if (!$service->is_empty($views->priority_list[$list][$wday]['plan_nm']??null))
                      <small>
                        <font color="#999999">{{strip_tags($views->priority_list[$list][$wday]['plan_nm'])}}</font>
                      </small>
                    @else
                      <br>
                    @endif
                  </td>
                @endforeach
              </tr>
              <tr>
                <td bgcolor="#FFFFFF" nowrap>重点表示<br>フラグ</td>
                @foreach ($views->week as $wday => $week_nm)
                  {{--null追記、他を参考に書き換え <td {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status) || ($v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1)}bgcolor="#FFFFFF"{else}bgcolor="#EEEEEE"{/if}> --}}
                    <td @if ($service->is_empty($views->priority_list[$list][$wday]['display_status']??null) || ($views->priority_list[$list][$wday]['display_status'] == 1))bgcolor="#FFFFFF"@else bgcolor="#EEEEEE"@endif>
                    {{-- {if $smarty.section.list.first != true && is_empty($v->assign->priority_list[$tmp_iteration][$wday])} --}}
                    @if ($list == 0 && $service->is_empty($views->priority_list[$tmp_iteration??null][$wday]??null))
                      <input type="radio" name="priority[display_status{{$wday}}_{{$list}}]" value="1" checked disabled>表示<br>
                      <input type="radio" name="priority[display_status{{$wday}}_{{$list}}]" value="0" disabled>非表示<br>
                    @else
                      {{--null追記、他を参考に書き換え <input type="radio" id="display_status{$wday}_{$smarty.section.list.iteration}_1" name="priority[display_status{$wday}_{$smarty.section.list.iteration}]" value="1" {if is_empty($v->assign->priority_list[$smarty.section.list.iteration][$wday]) || $v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status == 1}checked {/if}><label for="display_status{$wday}_{$smarty.section.list.iteration}_1">表示</label><br> --}}
                      <input type="radio" id="display_status{{$wday}}_{{$list}}_1" name="priority[display_status{{$wday}}_{{$list}}]" value="1" @if ($service->is_empty($views->priority_list[$list][$wday]??null) || $views->priority_list[$list][$wday]['display_status'] == 1)checked @endif><label for="display_status{{$wday}}_{{$list}}_1">表示</label><br>
                      {{-- null追記,===0を==0に（0="0"で通らない）<input type="radio" id="display_status{$wday}_{$smarty.section.list.iteration}_0" name="priority[display_status{$wday}_{$smarty.section.list.iteration}]" value="0" {if $v->assign->priority_list[$smarty.section.list.iteration][$wday].display_status === "0"}checked {/if}><label for="display_status{$wday}_{$smarty.section.list.iteration}_0">非表示</label> --}}
                      <input type="radio" id="display_status{{$wday}}_{{$list}}_0" name="priority[display_status{{$wday}}_{{$list}}]" value="0" @if (($views->priority_list[$list][$wday]['display_status']??null) == "0")checked @endif><label for="display_status{{$wday}}_{{$list}}_0">非表示</label>
                    @endif
                  </td>
                @endforeach
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    {{-- null追記 --}}
    <input type="hidden" name="priority[pref_id]" value={{strip_tags($views->select_pref['pref_id']??null)}}>
    <input type="hidden" name="priority[span]" value={{strip_tags($views->priority_cd['span'])}}>
    <input type="hidden" name="priority[priority]" value={{$list}}>
    {{-- {assign var=tmp_iteration value=$smarty.section.list.iteration} --}}
    @php $tmp_iteration = $list @endphp
  {!! Form::close() !!}
  @endfor
</table>
<ul style="margin-top:0px">
  <li><small>{{$views->priority_cnt}}件までの登録が可能です。</small></li>
  <li><small>同一都道府県内ですでに登録されているプランを指定してください。</small></li>
  <li><small>施設コード、PMSコード（部屋）または部屋コード、PMSコード（プラン）またはプランコードが無い場合は削除を行います、また削除の際に表示順位の繰上げ処理を行います。</small></li>
  <li><small>新管理画面を利用中の施設はプランメンテナンス画面に表示されている10桁の文字列（ID）またはPMSコードを設定してください。</small></li>
  <li><small>旧管理画面を利用中の施設は部屋コードとプランコードを設定してください。</small></li>
</ul>