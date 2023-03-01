
  {{--  引数：$calendar_base  ・・・ カレンダー基礎情報 --}}
  {{-- $capacity_range ・・・ 定員幅情報 --}}
  {{-- $charge_values  ・・・ 料金情報 --}}
  {{-- $charge_type    ・・・ 料金登録タイプ（RC, MC）--}}
  {{-- $low_price_info ・・・ 低価格料金アラート情報 --}}

  <div class="gen-container" id="jqs-charge-calendar">

    <h2 class="contents-header">料金カレンダー</h2>

    {{-- ※運用側で連携在庫の登録時にカレンダー部分をコピー&ペーストでExcelに張り付けて作業するときに 
        marginやHRタグなどを使用して改行するとFirefox, Chrome, IEのブラウザごとに空行の数が一致しなかった為
        仕方なく使用しています。--}}
    <br />

    @foreach($calendar_base as $week)
    {{--  移植前 --}}
    {{-- foreach from=$calendar_base item=week name=calendar_week --}}

      <table class="tbl-charge-calendar jqs-charge-calendar-week">
        @foreach($capacity_range as $capacity)
        {{-- 移植前--}}
        {{-- foreach from=$capacity_range item=capacity name=calendar_capacity --}}

          {{-- 人数でのループ初回のみ --}}
          @if ($loop->first)

          {{-- 移植前 --}}
          {{-- @if ($smarty['foreach']['calendar_capacity']['first']) --}}

            {{-- 年月ヘッダ生成 --}}
            <tr>
              <th class="cap-hd" rowspan="3"><br /></th>
              @foreach ($week['header_month'] as $column_month)
                <th colspan="{{ $column_month['col_count'] }}" class="calendar-hd ym-hd">{{ $column_month['col_value'] }}</th>
              @endforeach
            </tr>

            <tr>
              {{-- 曜日ヘッダ生成 --}}
              <th class="wkd-sun">日</th>
              <th>月</th>
              <th>火</th>
              <th>水</th>
              <th>木</th>
              <th>金</th>
              <th class="wkd-sat">土</th>
            </tr>
            <tr>
              @foreach ($week['values'] as $day)

                {{-- 背景色を判断 --}}
                {{-- assign var=class_nm value='' --}}

                @php
                $class_nm = '';
                @endphp

                @if ($day['dow_num'] == 6)
                    @php
                    $class_nm ="wkd-sat"
                    @endphp
                @elseif (isset($day['is_bfo']))
                    @php
                    $class_nm ="wkd-bfo"
                    @endphp
                @elseif (isset($day['is_hol']))
                    @php
                    $class_nm ="wkd-hol"
                    @endphp
                @elseif ($day['dow_num'] == 0 )
                    @php
                    $class_nm ="wkd-sun"
                    @endphp
                @endif

                <td $class_nm>{{ $day['md_str'] }}</td>
              @endforeach
            </tr>
          @endif

          <tr>
            <td class="cap-hd">
              {{ $capacity }}名&nbsp;
              @if ($charge_type == 1)(/人)
              @else (/室)
              @endif
            </td>

            @foreach ($week['values'] as $day)

              {{-- 背景色の指定 --}}
              {{-- assign var=class_nm value='' --}}
              @php
              $class_nm = '';
              @endphp

              {{-- 曜日別の背景色 --}}
              @if ($day['dow_num'] == 6)
                {{-- 土曜日 --}}
                {{-- $class_nm ='wkd-sat jqs-adapt-'|cat:$day['dow_num']|cat:'-'|cat:$capacity --}}
                @php
                $class_nm ='wkd-sat jqs-adapt-'.$day['dow_num'].'-'.$capacity
                @endphp
              @elseif (isset($day['is_bfo']))
                {{-- 休前日 --}}
                {{-- $class_nm ='wkd-bfo jqs-adapt-7-'|cat:$capacity --}}
                @php
                $class_nm ='wkd-bfo jqs-adapt-7-'.$capacity
                @endphp
              @elseif (isset($day['is_hol']))
                {{-- 祝日 --}}
                {{-- $class_nm ='wkd-hol jqs-adapt-8-'|cat:$capacity --}}
                @php
                $class_nm ='wkd-hol jqs-adapt-8-'.$capacity
                @endphp
              @elseif ($day['dow_num'] == 0)
                {{-- 日曜日 --}}
                {{-- $class_nm ='wkd-sun jqs-adapt-'|cat:$day['dow_num']|cat:'-'|cat:$capacity --}}
                @php
                $class_nm ='wkd-sun jqs-adapt-'.$day['dow_num'].'-'.$capacity
                @endphp
              @else
                {{-- 平日 --}}
                {{-- $class_nm ='jqs-adapt-'|cat:$day['dow_num']|cat:'-'|cat:$capacity --}}
                @php
                $class_nm ='jqs-adapt-'.$day['dow_num'].'-'.$capacity
                @endphp

              @endif


              {{-- 文字色を変更 --}}

                @php
                $key_nm = '';
                @endphp

              {{-- 編集不可な日程（過去の日程）を示す文字色 --}}
              @if (isset($day['is_not_edit']))
                {{-- $class_nm =$class_nm|cat:' msg-text-deactive' --}}
                @php
                $class_nm =$class_nm.'msg-text-deactive'
                @endphp

              @else
                {{-- 1円以上1000円未満の料金が設定されている文字色 --}}
                {{-- $class_nm =key_nm value=$day['ymd_num']|cat:'_'|cat:$capacity --}}
                @php
                $key_nm =$day['ymd_num'].'_'.$capacity
                @endphp

                @if (isset($low_price_info['ymdc'][$key_nm]))
                {{-- $class_nm =key_nm value=$class_nm|cat:' msg-text-error' --}}
                @php
                $class_nm =$class_nm.' msg-text-error'
                @endphp
                @endif
              @endif

              {{-- 日程・人数別料金を表示 --}}
              <td class="{{ $class_nm }}">

              @php
              $sales_charge = '';
              $is_low_price = '';
              @endphp
                {{-- 販売料金 --}}
                {{-- assign var=key_nm value='sales_charge_'|cat:$day['ymd_num']|cat:'_'|cat:$capacity --}}
                @php
                $key_nm = 'sales_charge_'.$day['ymd_num'].'_'.$capacity;
                $sales_charge = $charge_values[$key_nm];
                @endphp
                {{-- assign var=sales_charge value=$charge_values[$key_nm] --}}

                {{-- 低価格料金警告 --}}
                {{-- assign var=key_nm value=$day['ymd_num']|cat:'_'|cat:$capacity --}}
                @php
                $key_nm = $day['ymd_num'].'_'.$capacity
                @endphp
                {{-- assign var=is_low_price value=$low_price_info['ymdc'][$key_nm] --}}
                {{-- $is_low_price = {{$low_price_info['ymdc'][$key_nm]}} --}}


                {{-- 編集不可フラグが設定されているときは表示のみ --}}
                @if (isset($day['is_not_edit']))
                  {{-- MEMO: ↓ もとは is_empty() --}}
                  @if (!is_null($sales_charge))
                    <span class="msg-text-deactive">{{ number_format((int)$sales_charge) }}</span>
                  @else
                    <br />
                  @endif
                @else
                  {{-- MEMO: ↓ もとは is_empty() --}}
                  @if (!is_null($sales_charge))
                    {{-- 1人単価で1円以上N円未満の料金が設定されている場合 --}}
                    {{--  シティホテル、旅館は5000円以下の料金登録でアラート、ビジネスホテル、カプセルホテルは1000円 --}}

                    @if ($is_low_price)
                      <span class="msg-text-error">{{ number_format($sales_charge) }}</span>
                    @else
                      {{ number_format((int)$sales_charge) }}
                    @endif
                  @else
                    &nbsp;
                  @endif
                @endif
              </td>

            @endforeach

          </tr>

        @endforeach
      </table>

        {{-- ※運用側で連携在庫の登録時にカレンダー部分をコピー&ペーストでExcelに張り付けて作業するときに 
        marginやHRタグなどを使用して改行するとFirefox, Chrome, IEのブラウザごとに空行の数が一致しなかった為
        仕方なく使用しています。--}}
      <br />

    @endforeach

  </div>
