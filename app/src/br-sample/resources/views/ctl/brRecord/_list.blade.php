<table border="1" cellspacing="0" cellpadding="3" >
<tr bgcolor="#EEFFEE">
  <th rowspan="3">
    @if (($type == 'f') || (($type == 'y') && !$service->is_empty($date_ymd)))
      年月
    @elseif (($type == 'w') || ($type == 'd'))
      年月日
    @elseif ($type == 'y')
      年
    @endif
  </th>
  <th colspan="8">リザーブ</th>
  <th colspan="8">ストリーム</th>
  <th colspan="4">リザーブ会員</th>
  <th colspan="4">ダッシュ会員</th>
  <th colspan="7">全会員</th>
  <th rowspan="3">施設数</th>
  <th rowspan="3" title="リザーブ操作日別泊数純増 / 会員累計 ">稼働率</th>
  <th colspan="3">訪問数</th>
</tr>
<tr bgcolor="#EEFFEE">
  <th colspan="5">操作日別泊数</th>
  <th colspan="3">宿泊日別泊数</th>
  <th colspan="5">操作日別泊数</th>
  <th colspan="3">宿泊日別泊数</th>
  <th colspan="3">操作日別人数</th>
  <th rowspan="2">累計</th>
  <th colspan="3">操作日別人数</th>
  <th rowspan="2">累計</th>
  <th colspan="3">登録日別人数</th>
  <th colspan="3">操作日別人数</th>
  <th rowspan="2">累計</th>
  <th rowspan="2" title="累計 - Myページ">トップ</th>
  <th rowspan="2">ＭＹページ</th>
  <th rowspan="2">合計</th>
</tr>
<tr bgcolor="#EEFFEE">
  <th>予約</th>
  <th>取消</th>
  <th title="予約 - 取消">純増</th>
  <th title="即日取消">即消</th>
  <th title="予約 - 即消">有効</th>
  <th>予約</th>
  <th>取消</th>
  <th title="予約 - 取消">宿泊</th>
  <th>予約</th>
  <th>取消</th>
  <th title="予約 - 取消">純増</th>
  <th title="即日取消">即消</th>
  <th title="予約 - 即消">有効</th>
  <th>予約</th>
  <th>取消</th>
  <th title="予約 - 取消">宿泊</th>
  <th>確定</th>
  <th>退会</th>
  <th title="確定 - 退会">純増</th>
  <th>確定</th>
  <th>退会</th>
  <th title="確定 - 退会">純増</th>
  <th>申込</th>
  <th>確定</th>
  <th title="確定 / 申込">確定率</th>
 <th>確定</th>
  <th>退会</th>
  <th title="確定 - 退会">純増</th>
</tr>
{{-- 背景色のループ回数判定のために$key追記 --}}
@foreach ($records['values'] as $key => $record)
{{--以下へ書き換え <tr bgcolor="cycle" values="#FFFFFF,#E0E0E0"> --}}
@php
if($key % 2  == 0){
  $color = "#FFFFFF";
} else {
  $color = "#E0E0E0";
}
@endphp
<tr style="background-color:{{$color}};">
                    {{--↓$date_weekの定義元が不明、??null追記しておく --}}
{{--     宿泊日 --}}<td nowrap>@include('ctl.common._date', ['timestamp' => $record->date_ymd, 'format' => $date_format, 'color_on' => $date_color]){{$date_week ??null}}</td>
{{-- リザーブ   --}}
{{--       予約 --}}<td align="right">@if (!$service->is_empty($record->reserve_submit_reserve_count)){{number_format($record->reserve_submit_reserve_count)}} @else <br />@endif</td>
{{--       取消 --}}<td align="right">@if (!$service->is_empty($record->reserve_submit_cancel_count)){{number_format($record->reserve_submit_cancel_count)}} @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($record->reserve_submit_reserve_count)
                                    || !$service->is_empty($record->reserve_submit_cancel_count)){{number_format($record->reserve_submit_reserve_count-$record->reserve_submit_cancel_count)}} @else <br />@endif</td>
{{--       即消 --}}<td align="right">@if (!$service->is_empty($record->reserve_submit_immediate_count))
                                                                                      {{number_format($record->reserve_submit_immediate_count)}} @else <br />@endif</td>
{{--       有効 --}}<td align="right">@if (!$service->is_empty($record->reserve_submit_cancel_count)
                                    || !$service->is_empty($record->reserve_submit_immediate_count))
                                                                                      {{number_format($record->reserve_submit_reserve_count-$record->reserve_submit_immediate_count)}} @else <br />@endif</td>
{{--  宿泊泊数  --}}<td align="right">@if (!$service->is_empty($record->reserve_stay_reserve_count)) {{number_format($record->reserve_stay_reserve_count)}} @else <br />@endif</td>
{{-- 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($record->reserve_stay_cancel_count))  {{number_format($record->reserve_stay_cancel_count)}} @else <br />@endif</td>
{{-- 宿泊泊数 - 宿泊キャンセル泊数
               --}}<td align="right">@if (!$service->is_empty($record->reserve_stay_reserve_count)
                                     || !$service->is_empty($record->reserve_stay_cancel_count)) {{number_format($record->reserve_stay_reserve_count-$record->reserve_stay_cancel_count)}} @else <br />@endif</td>
{{-- ストリーム --}}
{{--       予約 --}}<td align="right">@if (!$service->is_empty($record->stream_submit_reserve_count)){{number_format($record->stream_submit_reserve_count)}} @else <br />@endif</td>
{{--       即消 --}}<td align="right">@if (!$service->is_empty($record->stream_submit_cancel_count)) {{number_format($record->stream_submit_cancel_count)}} @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($record->stream_submit_reserve_count)
                                    || !$service->is_empty($record->stream_submit_cancel_count)) {{number_format($record->stream_submit_reserve_count-$record->stream_submit_cancel_count)}} @else <br />@endif</td>
{{--       即日 --}}<td align="right">@if (!$service->is_empty($record->stream_submit_immediate_count))
                                                                                      {{number_format($record->stream_submit_immediate_count)}} @else <br />@endif</td>
{{--       有効 --}}<td align="right">@if (!$service->is_empty($record->stream_submit_reserve_count)
                                    || !$service->is_empty($record->stream_submit_immediate_count))
                                                                                      {{number_format($record->stream_submit_reserve_count-$record->stream_submit_immediate_count)}} @else <br />@endif</td>
{{--   宿泊泊数 --}}<td align="right">@if (!$service->is_empty($record->stream_stay_reserve_count))  {{number_format($record->stream_stay_reserve_count)}} @else <br />@endif</td>
{{-- 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($record->stream_stay_cancel_count))   {{number_format($record->stream_stay_cancel_count)}} @else <br />@endif</td>
{{-- 宿泊泊数 - 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($record->stream_stay_reserve_count)
                                    || !$service->is_empty($record->stream_stay_cancel_count))   {{number_format($record->stream_stay_reserve_count-$record->stream_stay_cancel_count)}} @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($record->member_rsv_commit_count))    {{number_format($record->member_rsv_commit_count)}} @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($record->member_rsv_withdraw_count))  {{number_format($record->member_rsv_withdraw_count)}} @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($record->member_rsv_commit_count)
                                    || !$service->is_empty($record->member_rsv_withdraw_count))  {{number_format($record->member_rsv_commit_count-$record->member_rsv_withdraw_count)}} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}<td align="right">@if (!$service->is_empty($record->member_rsv_total))           {{number_format($record->member_rsv_total)}} @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($record->member_dash_commit_count))   {{number_format($record->member_dash_commit_count)}} @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($record->member_dash_withdraw_count)) {{number_format($record->member_dash_withdraw_count)}} @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($record->member_dash_commit_count)
                                    || !$service->is_empty($record->member_dash_withdraw_count)) {{number_format($record->member_dash_commit_count-$record->member_dash_withdraw_count)}} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}<td align="right">@if (!$service->is_empty($record->member_dash_total))          {{number_format($record->member_dash_total)}} @else <br />@endif</td>
{{-- 会員       --}}
{{-- 会員申込数 --}}<td align="right">@if (!$service->is_empty($record->member_entry_count))         {{number_format($record->member_entry_count)}} @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($record->member_commit_count))        {{number_format($record->member_commit_count)}} @else <br />@endif</td>
{{--     確定率 --}}<td align="right">@if (!$service->is_empty($record->member_entry_count)
                                   && !$service->is_empty($record->member_commit_count))
                                     @if ($record->member_entry_count == 0)          0
                                                                                   @else{{number_format($record->member_commit_count/$record->member_entry_count*100)}}@endif @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($record->member_commit_count))        {{number_format($record->member_commit_count)}} @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($record->member_withdraw_count))      {{number_format($record->member_withdraw_count)}} @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($record->member_commit_count)
                                    || !$service->is_empty($record->member_withdraw_count))      {{number_format($record->member_commit_count-$record->member_withdraw_count)}} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}<td align="right">@if (!$service->is_empty($record->member_total))               {{number_format($record->member_total)}} @else <br />@endif</td>
{{--       施設 --}}
{{-- 施設登録累計数
              --}}<td align="right">@if (!$service->is_empty($record->hotel_total))                {{number_format($record->hotel_total)}} @else <br />@endif</td>
{{--     稼働率 --}}<td align="right">@if (!$service->is_empty($record->hotel_total))
              @php $reserve_submit_up_count = $record->reserve_submit_reserve_count-$record->reserve_submit_cancel_count @endphp
                                                                                      {{sprintf("%.2f", $reserve_submit_up_count/$record->member_total*100)}} @else <br />@endif</td>
                                                                                      {{-- $reserve_submit_up_count/$record->member_total*100|string_format:"%.2f" --}}
{{-- 訪問         --}}
{{--   top訪問数  --}}<td align="right">@if (!$service->is_empty($record->first_visit_count_top))    {{number_format($record->first_visit_count_top)}} @else <br />@endif</td>
{{-- mypege訪問数 --}}<td align="right">@if (!$service->is_empty($record->first_visit_count_mypage)) {{number_format($record->first_visit_count_mypage)}} @else <br />@endif</td>
{{--   訪問合計数  --}}<td align="right">@if (!$service->is_empty($record->first_visit_count))       {{number_format($record->first_visit_count)}} @else <br />@endif</td>
</tr>

{{-- これより↓undifinedになりそうな箇所（変数未定義）は??null追記でいいか？ --}}

{{-- 値を加算   --}}
{{-- リザーブ   --}}
{{--      予約  --}}@if (!$service->is_empty($record->reserve_submit_reserve_count)) @php $reserve_submit_reserve_count = ($reserve_submit_reserve_count ?? null)+$record->reserve_submit_reserve_count    @endphp @endif
{{--      取消  --}}@if (!$service->is_empty($record->reserve_submit_cancel_count))  @php $reserve_submit_cancel_count = ($reserve_submit_cancel_count ?? null) + $record->reserve_submit_cancel_count      @endphp @endif
{{--      即消  --}}@if (!$service->is_empty($record->reserve_submit_immediate_count))@php $reserve_submit_immediate_count = ($reserve_submit_immediate_count ?? null) + $record->reserve_submit_immediate_count @endphp @endif
{{--  宿泊泊数  --}}@if (!$service->is_empty($record->reserve_stay_reserve_count))   @php $reserve_stay_reserve_count = ($reserve_stay_reserve_count ?? null) + $record->reserve_stay_reserve_count        @endphp @endif
{{-- 宿泊キャンセル泊数
              --}}@if (!$service->is_empty($record->reserve_stay_cancel_count))    @php $reserve_stay_cancel_count = ($reserve_stay_cancel_count ?? null) + $record->reserve_stay_cancel_count          @endphp @endif
{{-- ストリーム --}}
{{--       予約 --}}@if (!$service->is_empty($record->stream_submit_reserve_count))  @php $stream_submit_reserve_count = ($stream_submit_reserve_count ?? null) + $record->stream_submit_reserve_count      @endphp @endif
{{--       即消 --}}@if (!$service->is_empty($record->stream_submit_cancel_count))   @php $stream_submit_cancel_count = ($stream_submit_cancel_count ?? null) + $record->stream_submit_cancel_count        @endphp @endif
{{--       即日 --}}@if (!$service->is_empty($record->stream_submit_immediate_count))@php $stream_submit_immediate_count = ($stream_submit_immediate_count ?? null) + $record->stream_submit_immediate_count  @endphp @endif
{{--   宿泊泊数 --}}@if (!$service->is_empty($record->stream_stay_reserve_count))    @php $stream_stay_reserve_count = ($stream_stay_reserve_count ?? null) + $record->stream_stay_reserve_count          @endphp @endif
{{-- 宿泊キャンセル泊数
              --}}@if (!$service->is_empty($record->stream_stay_cancel_count))     @php $stream_stay_cancel_count = ($stream_stay_cancel_count ?? null) + $record->stream_stay_cancel_count            @endphp @endif
{{-- 会員確定数 --}}@if (!$service->is_empty($record->member_rsv_commit_count))      @php $member_rsv_commit_count = ($member_rsv_commit_count ?? null) + $record->member_rsv_commit_count              @endphp @endif
{{-- 会員退会数 --}}@if (!$service->is_empty($record->member_rsv_withdraw_count))    @php $member_rsv_withdraw_count = ($member_rsv_withdraw_count ?? null) + $record->member_rsv_withdraw_count          @endphp @endif
{{-- 会員申込数 --}}@if (!$service->is_empty($record->member_rsv_entry_count))       @php $member_rsv_entry_count = ($member_rsv_entry_count ?? null) + $record->member_rsv_entry_count                @endphp @endif
{{-- 会員登録累計数
              --}}@if (!$service->is_empty($record->member_rsv_total))             @php $member_rsv_total = $record->member_rsv_total                                              @endphp @endif
{{-- 会員申込数 --}}@if (!$service->is_empty($record->member_dash_entry_count))      @php $member_dash_entry_count = ($member_dash_entry_count ?? null) + $record->member_dash_entry_count              @endphp @endif
{{-- 会員確定数 --}}@if (!$service->is_empty($record->member_dash_commit_count))     @php $member_dash_commit_count = ($member_dash_commit_count ?? null) + $record->member_dash_commit_count            @endphp @endif
{{-- 会員退会数 --}}@if (!$service->is_empty($record->member_dash_withdraw_count))   @php $member_dash_withdraw_count = ($member_dash_withdraw_count ?? null) + $record->member_dash_withdraw_count        @endphp @endif
{{-- 会員登録累計数
              --}}@if (!$service->is_empty($record->member_dash_total))            @php $member_dash_total = $record->member_dash_total                                             @endphp @endif
{{-- 会員       --}}
{{-- 会員申込数 --}}@if (!$service->is_empty($record->member_entry_count))           @php $member_entry_count = ($member_entry_count ?? null) + $record->member_entry_count                        @endphp @endif
{{-- 会員確定数 --}}@if (!$service->is_empty($record->member_commit_count))          @php $member_commit_count = ($member_commit_count ?? null) + $record->member_commit_count                      @endphp @endif
{{-- 会員退会数 --}}@if (!$service->is_empty($record->member_withdraw_count))        @php $member_withdraw_count = ($member_withdraw_count ?? null) + $record->member_withdraw_count                  @endphp @endif
{{-- 会員登録累計数
              --}}@if (!$service->is_empty($record->member_total))                 @php $member_total = $record->member_total                                                  @endphp @endif
{{-- 施設       --}}
{{-- 施設登録累計数
              --}}@if (!$service->is_empty($record->hotel_total))                  @php $hotel_total = $record->hotel_total                                                   @endphp @endif
{{-- 訪問       --}}
{{-- top訪問数    --}}@if (!$service->is_empty($record->first_visit_count_top))      @php $first_visit_count_top = ($first_visit_count_top ?? null) + $record->first_visit_count_top                  @endphp @endif
{{-- mypage訪問数 --}}@if (!$service->is_empty($record->first_visit_count_mypage))   @php $first_visit_count_mypage = ($first_visit_count_mypage ?? null) + $record->first_visit_count_mypage            @endphp @endif
{{-- 訪問合計数    --}}@if (!$service->is_empty($record->first_visit_count))         @php $first_visit_count = ($first_visit_count ?? null) + $record->first_visit_count                          @endphp @endif
@endforeach


{{-- 合計 --}}
<tr bgcolor="#EEFFEE">
  <td>合計</td>
{{-- リザーブ   --}}
{{--       予約 --}}<td align="right">@if (!$service->is_empty($reserve_submit_reserve_count ?? null)) {{ $reserve_submit_reserve_count ?? null }}    @else <br />@endif</td>
{{--       取消 --}}<td align="right">@if (!$service->is_empty($reserve_submit_cancel_count ?? null))  {{ $reserve_submit_cancel_count ?? null }}     @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($reserve_submit_reserve_count ?? null)
                                    || !$service->is_empty($reserve_submit_cancel_count ?? null))  {{ ($reserve_submit_reserve_count ?? null)-($reserve_submit_cancel_count ?? null) }} @else <br />@endif</td>
{{--       即消 --}}<td align="right">@if (!$service->is_empty($reserve_submit_immediate_count ?? null)){{ $reserve_submit_immediate_count ?? null }}  @else <br />@endif</td>
{{--       有効 --}}<td align="right">@if (!$service->is_empty($reserve_submit_cancel_count ?? null)
                                    || !$service->is_empty($reserve_submit_immediate_count ?? null)){{ ($reserve_submit_reserve_count ?? null)-($reserve_submit_immediate_count ?? null) }} @else <br />@endif</td>
{{-- 宿泊泊数   --}}<td align="right">@if (!$service->is_empty($reserve_stay_reserve_count ?? null))   {{ $reserve_stay_reserve_count ?? null }}      @else <br />@endif</td>
{{-- 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($reserve_stay_cancel_count ?? null))    {{ $reserve_stay_cancel_count ?? null }}       @else <br />@endif</td>
{{-- 宿泊泊数 - 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($reserve_stay_reserve_count ?? null)
                                    || !$service->is_empty($reserve_stay_cancel_count ?? null))    {{ ($reserve_stay_reserve_count ?? null)-($reserve_stay_cancel_count ?? null) }} @else <br />@endif</td>
{{-- ストリーム --}}
{{--       予約 --}}<td align="right">@if (!$service->is_empty($stream_submit_reserve_count ?? null))  {{ $stream_submit_reserve_count ?? null }}     @else <br />@endif</td>
{{--       即消 --}}<td align="right">@if (!$service->is_empty($stream_submit_cancel_count ?? null))   {{ $stream_submit_cancel_count ?? null }}      @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($stream_submit_reserve_count ?? null)
                                    || !$service->is_empty($stream_submit_cancel_count ?? null))   {{ ($stream_submit_reserve_count ?? null)-($stream_submit_cancel_count ?? null) }} @else <br />@endif</td>
{{--       即日 --}}<td align="right">@if (!$service->is_empty($stream_submit_immediate_count ?? null)){{ $stream_submit_immediate_count ?? null }}   @else <br />@endif</td>
{{--       有効 --}}<td align="right">@if (!$service->is_empty($stream_submit_reserve_count ?? null)
                                    || !$service->is_empty($stream_submit_immediate_count ?? null)){{ ($stream_submit_reserve_count ?? null)-($stream_submit_immediate_count ?? null) }} @else <br />@endif</td>
{{--   宿泊泊数 --}}<td align="right">@if (!$service->is_empty($stream_stay_reserve_count ?? null))    {{ $stream_stay_reserve_count ?? null }}       @else <br />@endif</td>
{{-- 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($stream_stay_cancel_count ?? null))     {{ $stream_stay_cancel_count ?? null }}        @else <br />@endif</td>
{{-- 宿泊泊数 - 宿泊キャンセル泊数
              --}}<td align="right">@if (!$service->is_empty($stream_stay_reserve_count ?? null)
                                    || !$service->is_empty($stream_stay_cancel_count ?? null))     {{ ($stream_stay_reserve_count ?? null)-($stream_stay_cancel_count ?? null) }} @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($member_rsv_commit_count ?? null))      {{ $member_rsv_commit_count ?? null }}         @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($member_rsv_withdraw_count ?? null))    {{ $member_rsv_withdraw_count ?? null }}       @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($member_rsv_commit_count ?? null)
                                    || !$service->is_empty($member_rsv_withdraw_count ?? null))    {{ ($member_rsv_commit_count ?? null)-($member_rsv_withdraw_count ?? null) }} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}  <td align="right">@if (!$service->is_empty($member_rsv_total ?? null))           {{ $member_rsv_total ?? null }}                @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($member_dash_commit_count ?? null))     {{ $member_dash_commit_count ?? null }}        @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($member_dash_withdraw_count ?? null))   {{ $member_dash_withdraw_count ?? null }}      @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($member_dash_commit_count ?? null)
                                    || !$service->is_empty($member_dash_withdraw_count ?? null))   {{ ($member_dash_commit_count ?? null)-($member_dash_withdraw_count ?? null) }} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}  <td align="right">@if (!$service->is_empty($member_dash_total ?? null))          {{ $member_dash_total ?? null }}               @else <br />@endif</td>
{{-- 会員       --}}
{{-- 会員申込数 --}}<td align="right">@if (!$service->is_empty($member_entry_count ?? null))           {{ $member_entry_count ?? null }}              @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($member_commit_count ?? null))          {{ $member_commit_count ?? null }}             @else <br />@endif</td>
{{--     確定率 --}}<td align="right">@if (!$service->is_empty($member_entry_count ?? null)
                                   || !$service->is_empty($member_commit_count ?? null))
                                    @if (($member_entry_count ?? null) == 0)             0
	                                                                          @else{{ ($member_commit_count ?? null)/($member_entry_count ?? null)*100 }}@endif @else <br />@endif</td>
{{-- 会員確定数 --}}<td align="right">@if (!$service->is_empty($member_commit_count ?? null))          {{ $member_commit_count ?? null }}             @else <br />@endif</td>

{{-- 会員退会数 --}}<td align="right">@if (!$service->is_empty($member_withdraw_count ?? null))        {{ $member_withdraw_count ?? null }}           @else <br />@endif</td>
{{--       純増 --}}<td align="right">@if (!$service->is_empty($member_commit_count ?? null)
                                    || !$service->is_empty($member_withdraw_count ?? null))        {{ ($member_commit_count ?? null)-($member_withdraw_count ?? null) }} @else <br />@endif</td>
{{-- 会員登録累計数
              --}}  <td align="right">@if (!$service->is_empty($member_total ?? null))               {{ $member_total ?? null }}                    @else <br />@endif</td>
{{-- 施設       --}}
{{-- 施設登録累計数
              --}}  <td align="right">@if (!$service->is_empty($hotel_total ?? null))                {{ $hotel_total ?? null }}                     @else <br />@endif</td>
{{--     稼働率 --}}  <td align="right">@if (!$service->is_empty($hotel_total ?? null))
      @php $reserve_submit_up_count = $reserve_submit_reserve_count-$reserve_submit_cancel_count @endphp
                                                                                 {{sprintf("%.2f", $reserve_submit_up_count/$member_total*100)}} @else <br />@endif</td>
                                                                                 {{-- $reserve_submit_up_count/$member_total*100|string_format:"%.2f" --}}
{{-- 訪問         --}}
{{--  top訪問数   --}}  <td align="right">@if (!$service->is_empty($first_visit_count_top ?? null))    {{ $first_visit_count_top ?? null }}           @else <br />@endif</td>
{{-- mypage訪問数 --}}  <td align="right">@if (!$service->is_empty($first_visit_count_mypage ?? null)) {{ $first_visit_count_mypage ?? null }}        @else <br />@endif</td>
{{--    訪問合計数 --}}  <td align="right">@if (!$service->is_empty($first_visit_count ?? null))       {{ $first_visit_count ?? null }}               @else <br />@endif</td>
</tr>
</table>
