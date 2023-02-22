{!! Form::open(['route' => ['ctl.brReserveCk.search'], 'method' => 'get']) !!}
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td  bgcolor="#EEFFEE" colspan="2">施設キーワード</td>
      <td>
        {{-- $views->searchでの取得データ、初期表示では値がないためnull追記でいいか --}}
        <input type="text" name="Search[keywords]" value="{{strip_tags($search['keywords'] ?? null)}}" />
      </td>
      <td rowspan="2"><input type="submit" value="施設検索"></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE">請求対象年月</td>
        <td colspan="2">
          <select size="1" name="Search[year]">
            {{--書き替えあっている？ {if $v->helper->date->set($v->assign->reserve_select_year)}{/if} --}}
            @php
              $date = $reserve_select_year;      
              if (!$service->is_empty($reserve_select_year ?? '')) {
                $date_Y = date('Y', strtotime($reserve_select_year));
              } else {
                $date_Y = null;
              }
            @endphp
            {{--書き替えあっている？ {section name = year start = 0 loop = $v->assign->s_cnt} --}}
            @for ($year = 0; $year < $s_cnt; $year++)
              <option value="{{$date_Y}}"
              {{-- $views->searchでの取得データ、初期表示では値がないためnull追記でいいか(monthの方も同様) --}}
              @if (!$service->is_empty($search['year'] ?? null))
                {{--書き替えあっている？ {if $v->helper->date->to_format('Y') == $v->assign->search.year} --}}
                @if ($date_Y == $search['year'] ?? null)
                  selected="selected"
                @endif
              @else
                {{--書き替えあっている？ {if $v->helper->date->to_format('Y') == $smarty.now|date_format:"%Y"} --}}
                @if ($date_Y == date('Y'))
                  selected="selected"
                @endif
              @endif
              >
              {{strip_tags($date_Y)}}
              {{--書き替えあっている？ {if $v->helper->date->add('y',1)}{/if} --}}
              @php        
              if (!$service->is_empty($date_Y)) {
                $date_Y = $date_Y + 1;
              } 
              @endphp
              </option>
            @endfor
          </select>&nbsp;年
            <select size="1" name="Search[month]">
              {{-- 月表示のための12回ループ  --}}
              @for ($m = 1; $m < 13; $m++)
                <option value="{{sprintf("%02d",strip_tags($m))}}"
                @if (!$service->is_empty($search['month'] ?? null))
                  {{-- string_format→sprintfでいいか --}}
                  @if (sprintf("%02d",$m)  == $search['month'] ?? null)
                    selected="selected"
                  @endif
                @elseif (sprintf("%02d",$m) == date('m'))
                  selected="selected"
                @endif>
                {{sprintf("%02d",strip_tags($m))}}
                </option>
              @endfor
            </select>&nbsp;月
        </td>
    </tr>
  </table>
{!! Form::close() !!}
<br />
