<table border="1" cellspacing="0" cellpadding="3">
  <tr>
    <td bgcolor="#EEFFEE">投稿日</td>
    <td>
      {{-- ↓null追記でいいか？ --}}
      <input type="checkbox" name="search[exp_check]" id="ex" value="on" @if (!$service->is_empty(strip_tags($search['exp_check'] ?? null)))checked @endif>
      <select name="after[exp_year]">
        {{--書き替えあっている？ @if ($v->helper->date->set($v->assign->year)}@endif --}}
        @php   
          if (!$service->is_empty($year ?? '')) {
            $after_Y = date('Y', strtotime($year));
          } else {
            $after_Y = null;
          }
        @endphp
        {{--書き替えあっている？ {section name = year start = 0 loop = $v->assign->date_ymd_cnt} --}}
        @for ($y = 0; $y < $date_ymd_cnt; $y++)
          <option value="{{$after_Y}}"
          {{-- $afterでの取得データ、初期表示では値がないためnull追記でいいか(monthの方も同様) --}}
          @if (!$service->is_empty($after['exp_year'] ?? null))
            {{--書き替えあっている？ @if ($v->helper->date->to_format('Y') == $v->assign->after.exp_year} --}}
            @if ($after_Y == $after['exp_year'] ?? null)
              selected="selected"
            @endif
          @else
            {{--書き替えあっている？ @if ($v->helper->date->to_format('Y') == $smarty.now|date_format:"%Y"} --}}
            @if ($after_Y == date('Y'))
              selected="selected"
            @endif
          @endif
          >
          {{strip_tags($after_Y)}}
          {{--書き替えあっている？ @if ($v->helper->date->add('y',1)}@endif --}}
          @php        
            if (!$service->is_empty($after_Y)) {
              $after_Y = $after_Y + 1;
            } 
          @endphp
          </option>
        @endfor
      </select><label for="ex">年</label>

      <select name="after[exp_month]">
        {{-- 月表示のための12回ループ  --}}
        @for ($m = 1; $m < 13; $m++)
          <option value="{{sprintf("%02d",$m)}}"
          @if (!$service->is_empty($after['exp_month'] ?? null))
            {{-- string_format→sprintfでいいか --}}
            @if (sprintf("%02d",$m)  == $after['exp_month'] ?? null)
              selected="selected"
            @endif
          @elseif ($m == date('m'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$m)}}
          </option>
        @endfor
      </select><label for="ex">月</label>

      <select name="after[exp_day]">
        {{-- 日表示のための12回ループ  --}}
        @for ($d = 1; $d < 32; $d++)        
          <option value="{{sprintf("%02d",$d)}}"
          @if (!$service->is_empty($after['exp_day']))
            @if ($d == $after['exp_day'])
              selected="selected"
            @endif
          @elseif ($d == date('d'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$d)}}
          </option>
        @endfor
      </select><label for="ex">日 ～ </label>

      <select name="before[exp_year]">
        {{-- ここから下も上と同じように書き換え --}}
        @php   
          if (!$service->is_empty($year ?? '')) {
            $before_Y = date('Y', strtotime($year));
          } else {
            $before_Y = null;
          }
        @endphp

        @for ($y = 0; $y < $date_ymd_cnt; $y++)
          <option value="{{$before_Y}}"
          @if (!$service->is_empty($before['exp_year'] ?? null))
            @if ($before_Y == $before['exp_year'] ?? null)
              selected="selected"
            @endif
          @else
            @if ($before_Y == date('Y'))
              selected="selected"
            @endif
          @endif
          >
          {{$before_Y}}
          @php        
            if (!$service->is_empty($before_Y)) {
              $before_Y = $before_Y + 1;
            } 
          @endphp
          </option>
        @endfor
        </select><label for="ex">年</label>

      <select name="before[exp_month]">
        {{-- 月表示のための12回ループ  --}}
        @for ($m = 1; $m < 13; $m++)
          <option value="{{sprintf("%02d",$m)}}"
          @if (!$service->is_empty($before['exp_month'] ?? null))
            @if ($m  == $before['exp_month'] ?? null)
              selected="selected"
            @endif
          @elseif ($m == date('m'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$m)}}
          </option>
        @endfor
      </select><label for="ex">月</label>

      <select name="before[exp_day]">
        {{-- 日表示のための12回ループ  --}}
        @for ($d = 1; $d < 32; $d++)        
          <option value="{{sprintf("%02d",$d)}}"
          @if (!$service->is_empty($before['exp_day']))
            @if ($d == $before['exp_day'])
              selected="selected"
            @endif
          @elseif ($d == date('d'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$d)}}
          </option>
        @endfor
      </select><label for="ex">日</label>
    </td>
  </tr>

  <tr>
    <td bgcolor="#EEFFEE">返答日</td>
    <td>
      {{-- 以下null追記 --}}
      <input type="checkbox" name="search[rep_check]" id="re" value="on" @if (!$service->is_empty(strip_tags($search['rep_check'] ?? null)))checked @endif>
      <select name="after[rep_year]">
        @php   
          if (!$service->is_empty($year ?? '')) {
            $after_rep_Y = date('Y', strtotime($year));
          } else {
            $after_rep_Y = null;
          }
        @endphp

        @for ($y = 0; $y < $date_ymd_cnt; $y++)
          <option value="{{$after_rep_Y}}"
          @if (!$service->is_empty($after['rep_year'] ?? null))
            @if ($after_rep_Y == $after['rep_year'] ?? null)
              selected="selected"
            @endif
          @else
            @if ($after_rep_Y == date('Y'))
              selected="selected"
            @endif
          @endif
          >
          {{$after_rep_Y}}
          @php        
            if (!$service->is_empty($after_rep_Y)) {
              $after_rep_Y = $after_rep_Y + 1;
            } 
          @endphp
          </option>
        @endfor
        </select><label for="re">年</label>

      <select name="after[rep_month]">
        {{-- 月表示のための12回ループ  --}}
        @for ($m = 1; $m < 13; $m++)
          <option value="{{sprintf("%02d",$m)}}"
          @if (!$service->is_empty($after['rep_month'] ?? null))
            @if ($m  == $after['rep_month'] ?? null)
              selected="selected"
            @endif
          @elseif ($m == date('m'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$m)}}
          </option>
        @endfor
      </select><label for="re">月</label>

      <select name="after[rep_day]">
        {{-- 日表示のための12回ループ  --}}
        @for ($d = 1; $d < 32; $d++)        
          <option value="{{sprintf("%02d",$d)}}"
          @if (!$service->is_empty($after['rep_day']))
            @if ($d == $after['rep_day'])
              selected="selected"
            @endif
          @elseif ($d == date('d'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$d)}}
          </option>
        @endfor
      </select><label for="re">日 ～ </label>

      <select name="before[rep_year]">
        @php   
          if (!$service->is_empty($year ?? '')) {
            $before_rep_Y = date('Y', strtotime($year));
          } else {
            $before_rep_Y = null;
          }
        @endphp

        @for ($y = 0; $y < $date_ymd_cnt; $y++)
          <option value="{{$before_rep_Y}}"
          @if (!$service->is_empty($before['rep_year'] ?? null))
            @if ($before_rep_Y == $before['rep_year'] ?? null)
              selected="selected"
            @endif
          @else
            @if ($before_rep_Y == date('Y'))
              selected="selected"
            @endif
          @endif
          >
          {{$before_rep_Y}}
          @php        
            if (!$service->is_empty($before_rep_Y)) {
              $before_rep_Y = $before_rep_Y + 1;
            } 
          @endphp
          </option>
        @endfor
        </select><label for="re">年</label>

      <select name="before[rep_month]">
        {{-- 月表示のための12回ループ  --}}
        @for ($m = 1; $m < 13; $m++)
          <option value="{{sprintf("%02d",$m)}}"
          @if (!$service->is_empty($before['rep_month'] ?? null))
            @if ($m  == $before['rep_month'] ?? null)
              selected="selected"
            @endif
          @elseif ($m == date('m'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$m)}}
          </option>
        @endfor
      </select><label for="re">月</label>

      <select name="before[rep_day]">
        {{-- 日表示のための12回ループ  --}}
        @for ($d = 1; $d < 32; $d++)        
          <option value="{{sprintf("%02d",$d)}}"
          @if (!$service->is_empty($before['rep_day']))
            @if ($d == $before['rep_day'])
              selected="selected"
            @endif
          @elseif ($d == date('d'))
            selected="selected"
          @endif>
          {{sprintf("%02d",$d)}}
          </option>
        @endfor
      </select><label for="re">日</label>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">施設コード</td>
    {{-- null追記でいいか？ --}}
    <td><input type="text" name="search[hotel_cd]" size="30" value="{{strip_tags($search['hotel_cd'] ?? null)}}"></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">キーワード</td>
    {{-- null追記でいいか？ --}}
    <td><input type="text" name="search[keywords]" size="30" value="{{strip_tags($search['keywords'] ?? null)}}"></td>
  </tr>
</table>