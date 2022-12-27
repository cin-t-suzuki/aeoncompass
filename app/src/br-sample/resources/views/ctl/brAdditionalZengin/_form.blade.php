{{--不要？ {literal} --}}
<style type="text/css">
 /* <!-- -->の書き替えはコメントアウトで合っている？ */
 /* form {margin:0px} */
</style>
<script language="javascript"  type="text/javascript">
  $(document).ready(function () {

  exec_searchlist();

    $('input[name="query"]').click(function () {
        exec_searchlist();

    });
  });
  function exec_searchlist(){
    // var uri = '{/literal}{$v->env.source_path}{$v->env.module}{literal}/bradditionalzengin/searchList?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&year=' + $('select[name="year"]').val() + '&month=' + $('select[name="month"]').val() + '&ym=' + $('select[name="direct_debit_ym"]').val();
    var uri = '{{ route('ctl.brAdditionalZengin.searchlist')}}?keywords=' + encodeURI($('input[name="keyword"]').val()) + '&year=' + $('select[name="year"]').val() + '&month=' + $('select[name="month"]').val() + '&ym=' + $('select[name="direct_debit_ym"]').val();
      if ($('#unuse_ym').is(':checked')) {
          uri += '&unuse_check=0';
      } else if ($('#unuse_ddym').is(':checked')){
          uri += '&unuse_check=1';
      } else if ($('#unuse_ymd').is(':checked')){
          uri += '&unuse_check=2';
      }

    console.log(uri);
    //window.location.href =uri;
    $.get(uri, function(html){
      $('#zengin_list').html(html);
    });
  }


</script>
{{-- {/literal} --}}

  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td  bgcolor="#EEFFEE">キーワード</td>
      <td>
        <input maxlength="20" name="keyword" size="30" colspan="2">
      </td>
      <td rowspan="3"><input name="query" type="submit" value="検索" style="width: 80px;"></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE">検索方法選択</td>
      <td>
        <input type="radio" style="margin: 5px 5px;" value="0" name="unuse_check" id="unuse_ym" checked="checked"><label for="unuse_ym">請求対象年月</label>
          <select size="1" name="year">
            {{--書き換え合っている？ @if ($v->helper->date->set($v->assign->reserve_select_year))@endif --}}
            @php        
              if (!$service->is_empty($views->reserve_select_year)) {
                $date_Y = date('Y', strtotime($views->reserve_select_year));
              } else {
                $date_Y = null;
              }
            @endphp
            {{--書き替えはforで合っているか？ {section name = year start = 0 loop = $v->assign->s_cnt} --}}
            @for ($y = 0; $y <= $views->s_cnt; $y++) 
              {{--書き替え以下であっている？ <option value="{{$v->helper->date->to_format('Y')}" --}}
              <option value="{{$date_Y}}"
                {{-- $views->searchでの取得データ、初期表示では値がないためnull追記でいいか --}}
              @if (!$service->is_empty($views->search['year'] ?? null))
                @if ($date_Y == $views->search['year'] ?? null)
                  selected="selected"
                @endif
              @else
                {{--書き換え以下で合っている？ @if ($v->helper->date->to_format('Y') == $smarty.now|date_format:"%Y") --}}
                @if ($date_Y == date('Y'))
                  selected="selected"
                @endif
              @endif
              >
              {{--書き替え以下であっている？？ {$v->helper->form->strip_tags($v->helper->date->to_format('Y'))} --}}
              {{strip_tags($date_Y)}}
              {{--書き替え以下であっている？？ {if $v->helper->date->add('y',1)}{/if} --}}
              @php        
              if (!$service->is_empty($date_Y)) {
                $date_Y = $date_Y + 1;
              } 
              @endphp
              </option>
            @endfor
          </select>&nbsp;年
          <select size="1" name="month">
            {{-- 月表示のための12回ループ --}}
            {{--書き換えはforで合っているか？ {section name = month start = 1 loop = 13} --}}
            @for($m = 1; $m < 13; $m++)
              {{--書き替え以下であっている？？ <option value="{$v->helper->form->strip_tags($smarty.section.month.index)|string_format:"%02d"}" --}}
                <option value="{{sprintf('%02d',strip_tags($m))}}"
                {{-- $views->searcでの取得データ、初期表示では値がないためnull追記でいいか --}}
              @if (!$service->is_empty($views->search['month'] ?? null))
                {{--書き替え以下であっている？？ @if ($smarty.section.month.index|string_format:"%02d" == $v->assign->search.month) --}}
                @if (sprintf('%02d',$m) == $views->search['month'] ?? null)
                  selected="selected"
                @endif
              {{--書き替え以下であっている？？ @elseif ($smarty.section.month.index|string_format:"%02d" == $smarty.now|date_format:'%m') --}}
              @elseif (sprintf('%02d',$m) == date('m'))
                selected="selected"
              @endif >
              {{--書き替え以下であっている？？ {$v->helper->form->strip_tags($smarty.section.month.index)|string_format:"%02d"} --}}
              {{sprintf('%02d', strip_tags($m))}}
              </option>
            @endfor
          </select>&nbsp;月<br>
        <input type="radio" style="margin: 5px 5px;" value="1" name="unuse_check" id="unuse_ddym" value="" ><label for="unuse_ddym">引落入金予定日</label>
          <select size="1" name="direct_debit_ym">
            @foreach ($views->direct_debit_ym_select as $k => $v)
              {{--書き替え以下であっている？？ <option value="{$v.ym|date_format:'%Y%m'}">{$v.date_ymd|date_format:"%Y/%m/%d"}</option> --}}
              @php
                if (!$service->is_empty($v->ym)) {
                  $ym = date('Ym', strtotime($v->ym));
                } else {
                  $ym = null;
                }
              @endphp
              <option value="{{$ym}}">@include('ctl.common._date',["timestamp" => $v->date_ymd, "format" =>"ymd" ] )</option>
            @endforeach
          </select>&nbsp;<br>
        <input type="radio" style="margin: 5px 5px;" value="2" name="unuse_check" id="unuse_ymd"><label for="unuse_ymd">年月の指定をしない</label>
      </td>
    </tr>
  </table>
<br />