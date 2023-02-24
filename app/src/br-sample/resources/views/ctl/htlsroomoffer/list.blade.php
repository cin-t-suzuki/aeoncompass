{{--JavaScript指定--}}
@section('headScript')
<script type="text/javascript">
<!--
    $(document).ready(function () {
    $('a.move-ptop').scrollPageTop();
    $('.jqs-show-hide-plan').showAndHideRoomPlans({target_selecter:'jqs-room-plan'});
    $('.jqs-tooltip-target').showHideToolTop();
    });
-->
</script>
@endsection

@extends('ctl.common.base3', [
'title' => '提供室数の調整',
'screen_type' => 'htl',
'is_staff_navi' => 'on',
'is_htl_navi'   => 'on',
'is_ctl_menu' => 'on',
])

@section('content')

  {{-- {* 余白 *} --}}
  <hr class="bound-line" />
  
  {{-- 切替メニュー --}}
  <div class="ctl-menu">
    {{-- 室数の調整  --}}
    @if (Route::currentRouteName() === 'ctl.htlsroomoffer.list')
      <div class="elm elm-active">
        【室数の調整】
      </div>
    @else
    {!! Form::open(['route' => ['ctl.htlsroomplan2.list'], 'method' => 'post']) !!}
        <div class="elm">
          <input type="hidden" name="target_cd" value="{{$views->hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
          <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['current']['year'])}}" />
          <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['current']['month'])}}" />
          <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['current']['day'])}}" />
          <input type="submit" value="室数の調整" />
        </div>
    {!! Form::close() !!}
    @endif
    
    {{--日毎プランの調整 --}}
    @if (Route::currentRouteName() === 'ctl.htlsplanoffer')
      <div class="elm elm-active">
        【日毎にプラン売止/販売を行う】
      </div>
    @else
      {!! Form::open(['route' => ['ctl.htlsplanoffer'], 'method' => 'post']) !!} 
        <div class="elm">
          <input type="hidden" name="target_cd" value="{{$views->hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
          <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['current']['year'])}}" />
          <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['current']['month'])}}" />
          <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['current']['day'])}}" />
          <input type="submit" value="日毎にプラン売止/販売を行う" />
        </div>
      {!! Form::close() !!}
    @endif
    
    {{-- 料金を調整する --}}
    @if (Route::currentRouteName() === 'ctl.htlschargeoffer')
      <div class="elm elm-active">
        【料金を調整する】
      </div>
    @else
      {!! Form::open(['route' => ['ctl.htlschargeoffer'], 'method' => 'post']) !!} 
        <div class="elm">
          <input type="hidden" name="target_cd" value="{{$views->hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
          <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['current']['year'])}}" />
          <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['current']['month'])}}" />
          <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['current']['day'])}}" />
          <input type="submit" value="料金を調整する" />          
        </div>
      {!! Form::close() !!}
    @endif
    
    {{-- プランの期間を延長する --}}
    @if (Route::currentRouteName() === 'ctl.htlsextendoffer')
      <div class="elm elm-active">
        【プランの期間を延長する】
      </div>
    @else
      {!! Form::open(['route' => ['ctl.htlsextendoffer'], 'method' => 'post']) !!} 
        <div class="elm">
          <input type="hidden" name="target_cd" value="{{$views->hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
          <input type="submit" value="プランの期間を延長する" />
        </div>
      {!! Form::close() !!}
    @endif
    
    <div class="clear"></div>
  </div>
  
  {{-- 余白 --}}
  <hr class="bound-line" />
  
  @if(is_null($views->room_details))
    <p class="msg-text-error">登録されている部屋がありません</p>
  @else

    {{--部屋プラン一覧 --}}

    {{--部屋IDの一覧を作成 --}}  
    <table class="tbl-room-plan-list">
      <tr>
        <th id="menu" colspan="16">
          {{--操作期間の変更 --}}
          <div class="calendar-menu">
            <div class="menu">
              {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post']) !!}
                <div class="elm">
                  <span>表示期間の変更：</span>
                  <select name="start_ymd[year]">
                    @for($i=$views->start_date['year']; $i < $views->end_date['year']+1; $i++)
                      <option value={{$i}}  @if($i == $views->date_range['current']['year']) selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span>年</span>
                  <select name="start_ymd[month]">
                    @for($i=0; $i <= 12; $i++)
                      <option value={{$i}}  @if($i == $views->date_range['current']['month']) selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span>月</span>
                  <select name="start_ymd[day]">
                    @for($i=0; $i <= 31; $i++)
                      <option value={{$i}}  @if($i == $views->date_range['current']['day']) selected @endif>{{$i}}</option>
                    @endfor
                  </select>
                  <span>日</span>
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="submit" value="切替"  class="btn" />
                </div>
              {!! Form::close() !!}
              {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post']) !!}
                <div class="elm">
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['week_bfo']['year'])}}" />
                  <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['week_bfo']['month'])}}" />
                  <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['week_bfo']['day'])}}" />
                  <input type="submit" value="<< 2週間前" class="btn" />
                </div>
              {!! Form::close() !!}
              {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post']) !!}
                <div class="elm">
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['day_bfo']['year'])}}" />
                  <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['day_bfo']['month'])}}" />
                  <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['day_bfo']['day'])}}" />
                  <input type="submit" value="<前の日" class="btn" />
                </div>
              {!! Form::close() !!}
              {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post']) !!}
                <div class="elm">
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['day_aft']['year'])}}" />
                  <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['day_aft']['month'])}}" />
                  <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['day_aft']['day'])}}" />
                  <input type="submit" value="次の日>" class="btn" />
                </div>
              {!! Form::close() !!}
              {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post', 'style' =>'display:inline;']) !!}
                <div class="elm">
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($views->date_range['week_aft']['year'])}}" />
                  <input type="hidden" name="start_ymd[month]" value="{{strip_tags($views->date_range['week_aft']['month'])}}" />
                  <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($views->date_range['week_aft']['day'])}}" />
                  <input type="submit" value="2週間後 >>" class="btn" />
                </div>
              {!! Form::close() !!}
              <div class="clear"></div>
            </div>
          </div>
        </th>
      </tr>

      {{-- 10日分の日程 --}}
      <tr>
        <th class="emp" rowspan="5">&nbsp;</th>
        <th>日程</th>
          @foreach ($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <th class="md msg-text-deactive">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @elseif($day['dow_num'] == 6)
              <th class="md wkd-sat">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @elseif(isset($day['is_bfo']))
              <th class="md wkd-bfo">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @elseif(isset($day['is_hol']))
              <th class="md wkd-hol">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @elseif($day['dow_num'] == 0)
              <th class="md wkd-sun">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @else
              <th class="md">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
            @endif
          @endforeach 
      </tr>

      {{-- 10日分の各日の一括設定 --}}
      <tr>
        <th>日毎調整</th>
          @foreach ($views->week_days as $day)
            @if($day['dow_num'] == 6)
              <td class="md wkd-sat">
                {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                  <div>
                    <input type="hidden" name="target_cd"   value="{{strip_tags($views->target_cd)}}" />
                    <input type="hidden" name="ui_type"     value="date" />
                    <input type="hidden" name="date_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                    <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                    @foreach($views->room_details as $room_id => $room_detail)
                      <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                    @endforeach
                    <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                  </div>
                {!! Form::close() !!}
              </td>
            @elseif(isset($day['is_bfo']))
              <td class="md wkd-bfo">
                {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                  <div>
                    <input type="hidden" name="target_cd"   value="{{strip_tags($views->target_cd)}}" />
                    <input type="hidden" name="ui_type"     value="date" />
                    <input type="hidden" name="date_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                    <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                    @foreach($views->room_details as $room_id => $room_detail)
                      <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                    @endforeach
                    <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                  </div>
                {!! Form::close() !!}
              </td>
            @elseif(isset($day['is_hol']))
              <td class="md wkd-hol">
                {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                  <div>
                    <input type="hidden" name="target_cd"   value="{{strip_tags($views->target_cd)}}" />
                    <input type="hidden" name="ui_type"     value="date" />
                    <input type="hidden" name="date_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                    <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                    @foreach($views->room_details as $room_id => $room_detail)
                      <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                    @endforeach
                    <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                  </div>
                {!! Form::close() !!}
              </td>
           @elseif($day['dow_num'] == 0)
              <td class="md wkd-sun">
                {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                  <div>
                    <input type="hidden" name="target_cd"   value="{{strip_tags($views->target_cd)}}" />
                    <input type="hidden" name="ui_type"     value="date" />
                    <input type="hidden" name="date_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                    <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                    @foreach($views->room_details as $room_id => $room_detail)
                      <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                    @endforeach
                  <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                  </div>
                {!! Form::close() !!}
              </td>
              @else
              <td class="md">
                {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                  <div>
                    <input type="hidden" name="target_cd"   value="{{strip_tags($views->target_cd)}}" />
                    <input type="hidden" name="ui_type"     value="date" />
                    <input type="hidden" name="date_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                    <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                    @foreach($views->room_details as $room_id => $room_detail)
                      <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                    @endforeach
                  <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                  </div>
                {!! Form::close() !!}
              </td>
            @endif
          @endforeach 
      </tr>
      
      {{-- 10日分の各日の提供室数合計  --}}
      <tr>
        <th>提供室数合計</th>
        @foreach ($views->week_days as $day)
          @if($day['ymd_mn_num'] < time())
            <td class="md msg-text-deactive">
              {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
            </td>
          @elseif($day['dow_num'] == 6)
            <td class="md wkd-sat">
              {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
            </td>
          @elseif(isset($day['is_bfo']))
            <td class="md wkd-bfo">
              {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
            </td>
          @elseif(isset($day['is_hol']))
            <td class="md wkd-hol">
              {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
            </td>
          @elseif($day['dow_num'] == 0)
            <td class="md wkd-sun">
              {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
            </td>
          @else
          <td class="md">
            {{$views->sale_state_room_plan[$day['ymd_num']]['rooms_sum']}}
          </td>
          @endif
        @endforeach
      </tr>

      {{-- 10日分の各日の残室数合計  --}}
      <tr>
        <th>残室数合計</th>
        @foreach ($views->week_days as $day)
          @if($day['ymd_mn_num'] < time())
            <td class="md msg-text-deactive">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @elseif($day['dow_num'] == 6)
            <td class="md wkd-sat">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @elseif(isset($day['is_bfo']))
            <td class="md wkd-bfo">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @elseif(isset($day['is_hol']))
            <td class="md wkd-hol">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @elseif($day['dow_num'] == 0)
            <td class="md wkd-sun">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @else
            <td class="md">
              {{$views->sale_state_room_plan[$day['ymd_num']]['remaining_rooms_sum']}}
            </td>
          @endif
        @endforeach
      </tr>
      
      {{-- 10日分の各日の販売状況  --}}
      <tr>
        <th>販売状況</th>
        @foreach ($views->week_days as $day)
          @if($day['ymd_mn_num'] < time())
            <td class="md msg-text-deactive">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －   
              @endif  
            </td>
          @elseif($day['dow_num'] == 6)
            <td class="md wkd-sat">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －     
              @endif
            </td>
          @elseif(isset($day['is_bfo']))
            <td class="md wkd-bfo">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －     
              @endif
            </td>
          @elseif(isset($day['is_hol']))
            <td class="md wkd-hol">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －     
              @endif
            </td>
          @elseif($day['dow_num'] == 0)
            <td class="md wkd-sun">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －     
              @endif
            </td>
          @else
            <td class="md">
              @if ($day['ymd_mn_num'] < time())
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale_still']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])     &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']))
                      開始前
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_expiration']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      販売<br />終了
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale'])  &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      !isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_sale']))
                      一部売
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']) &&
                      isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_resale']))
                      止(再有)
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_stop']))
                      止
              @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['sale_status']['is_without']))
                      登録無
              @else   －     
              @endif
            </td>
          @endif
        @endforeach
      </tr>
      
      {{-- 部屋情報 --}}
      @foreach($views->room_details as $room_id => $room_detail)
        {{-- 各日の日付 --}}
        <tr>
          <td class="fr room" rowspan="5">
            {{-- 部屋名称 --}}
            <p>{{$room_detail->room_nm}}</p>

            {{-- PMSコードと連携在庫コード --}}
            <p>[{{$room_detail->pms_cd}}]&nbsp; @if($room_detail->akafu_cd) [{{$room_detail->akafu_cd}}] @endif</p>

            {{-- 部屋スペック --}}
            @include('ctl.common._room_spec_icons',['room'  => $room_detail])

            <hr />
            {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
              <div>
                <input type="button" name="show_hide_plan" class="jqs-show-hide-plan" id="room-id-{{$room_id}}" value="+" />&nbsp;
                <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                <input type="hidden" name="ui_type" value="calender" />
                <input type="hidden" name="room_id" value="{{strip_tags($room_id)}}" />
                <input type="hidden" name="date_ym" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                <input type="submit" value="部屋数の設定" />
              </div>
            {!! Form::close() !!}
          </td>

          <th class="fr">日程</th>

          @foreach($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <th class="fr md msg-text-deactive">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @elseif($day['dow_num'] == 6)
              <th class="fr md wkd-sat">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @elseif(isset($day['is_bfo']))
              <th class="fr md wkd-bfo">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @elseif(isset($day['is_hol']))
              <th class="fr md wkd-hol">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @elseif($day['dow_num'] == 0)
              <th class="fr md wkd-sun">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @else
              <th class="fr md">
                {{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}
              </th>
            @endif
          @endforeach
        </tr>

        {{-- 各日の提供室数 --}}
        <tr>
          <th>提供室数</th>
          @foreach($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <td class="md msg-text-deactive">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
              </td>
            @elseif($day['dow_num'] == 6)
              <td class="md wkd-sat">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
            </td>
            @elseif(isset($day['is_bfo']))
              <td class="md wkd-bfo">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
              </td>
            @elseif(isset($day['is_hol']))
              <td class="md wkd-hol">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
              </td>
            @elseif($day['dow_num'] == 0)
              <td class="md wkd-sun">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
              </td>
            @else
              <td class="md">
                {{-- 過去の日程は編集できない --}}
                @if($day['ymd_mn_num'] < time())
                {{-- 連携在庫は提供室数を表示しない --}}
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} 
                  @else
                    無し
                  @endif
                @else  
                  @if ($room_detail->akafu_cd)
                    －
                  @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']))
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">{{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['rooms']}} </a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=room&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}">無し</a>
                  @endif
                @endif
              </td>
            @endif
          @endforeach
        </tr> 

        {{-- 各日の残室数 --}}
        <tr>
          <th>残室数</th>
          @foreach($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <td class="md msg-text-deactive">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @elseif($day['dow_num'] == 6)
              <td class="md wkd-sat">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @elseif(isset($day['is_bfo']))
              <td class="md wkd-bfo">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @elseif(isset($day['is_hol']))
              <td class="md wkd-hol">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @elseif($day['dow_num'] == 0)
              <td class="md wkd-sun">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @else
              <td class="md">
                {{-- 連携在庫は提供室数を表示しない --}}
                @if ($room_detail->akafu_cd)
                  －
                @elseif(!is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']))
                  {{$views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['remaining_rooms']}} 
                @else
                  無し
                @endif
              </td>
            @endif
          @endforeach
        </tr>

        {{-- 各日の手仕舞い状況 --}}
        <tr>
          <th>手仕舞</th>
          @foreach($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <td class="md msg-text-deactive">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @elseif($day['dow_num'] == 6)
              <td class="md wkd-sat">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @elseif(isset($day['is_bfo']))
              <td class="md wkd-bfo">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @elseif(isset($day['is_hol']))
              <td class="md wkd-hol">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @elseif($day['dow_num'] == 0)
              <td class="md wkd-sun">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @else
              <td class="md">
                {{-- 過去日付もしくは連携在庫に関しては編集できない --}}
                @if($day['ymd_mn_num'] < time() || $room_detail->akafu_cd)
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                @else
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['accept_status_room_count'] == 1)
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                  @else
                    <a href="edit/?target_cd={{$views->target_cd}}&amp;ui_type=accept&amp;room_id={{$room_id}}&amp;date_ymd={{$day['ymd_num']}}&amp;current_ymd={{$views->date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                  @endif
                @endif
              </td>
            @endif
          @endforeach

        {{-- 各日の販売状況 --}}
        <tr>
          <th>販売状況</th>
          @foreach ($views->week_days as $day)
            @if($day['ymd_mn_num'] < time())
              <td class="md msg-text-deactive">
                @if ($day['ymd_mn_num'] < time())
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                        (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                  一部売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                  止(再有)
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                        )
                  開始前
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                  止
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                  登録無
                @else  
                  －     
                @endif
              </td>
            @elseif($day['dow_num'] == 6)
              <td class="md wkd-sat">
                @if ($day['ymd_mn_num'] < time())
                    販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                    販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                          (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                    一部売
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                    売
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                    止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                          )
                    開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                    止
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                    登録無
                  @else  
                    －     
                @endif
              </td>
            @elseif(isset($day['is_bfo']))
              <td class="md wkd-bfo">
                @if ($day['ymd_mn_num'] < time())
                    販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                        (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                  一部売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                  止(再有)
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                        )
                  開始前
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                  止
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                  登録無
                @else  
                  －     
                @endif
              </td>
            @elseif(isset($day['is_hol']))
              <td class="md wkd-hol">
                @if ($day['ymd_mn_num'] < time())
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                        (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                  一部売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                  止(再有)
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                        )
                  開始前
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                  止
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                  登録無
                @else  
                  －     
                @endif
              </td>
            @elseif($day['dow_num'] == 0)
              <td class="md wkd-sun">
                @if ($day['ymd_mn_num'] < time())
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                        (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                  一部売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                  止(再有)
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                        )
                  開始前
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                  止
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                  登録無
                @else  
                  －     
                @endif
              </td>
            @else
              <td class="md">
                @if ($day['ymd_mn_num'] < time())
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  販売<br />終了
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale'])  &&
                        (isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) ||
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without'])))
                  一部売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale']))
                  売
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']) &&
                        isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_resale']))
                  止(再有)
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_sale_still']) &&
                        !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_expiration'])
                        )
                  開始前
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_stop']))
                  止
                @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['sale_status']['is_without']))
                  登録無
                @else  
                  －     
                @endif
              </td>
            @endif
          @endforeach
        </tr>


        {{-- プラン情報 --}}
        @foreach($views->match_room_plans_all[$room_id] as $plan_id)
          {{-- 販売期間が未設定 or 期限切れの判定 --}}
          @php
            $is_accept_ymd_validate_fail = false;
          @endphp
          <tr class="default-hide jqs-room-plan-{{$room_id}}">
            <td class="plan" rowspan="3">
              {{-- プラン名称 --}}
              <p>{{$views->plan_details[$plan_id]->plan_nm}}</p>
              {{-- PMSコード --}}
              <p>[{{$views->plan_details[$plan_id]->pms_cd}}]</p>

              <p>
                @if (empty($views->plan_details[$plan_id]->accept_s_ymd) || empty($views->plan_details[$plan_id]->accept_e_ymd))
                    @php
                    $is_accept_ymd_validate_fail = true;
                    @endphp
                  <span class="msg-text-error">販売期間未設定</span>
                @else
                {{substr($views->plan_details[$plan_id]->accept_s_ymd,0,4)}}年{{substr($views->plan_details[$plan_id]->accept_s_ymd,4,2)}}月{{ltrim(substr($views->plan_details[$plan_id]->accept_s_ymd,6,2),'0')}}日
                &nbsp;～&nbsp;
                {{substr($views->plan_details[$plan_id]->accept_e_ymd,0,4)}}年{{substr($views->plan_details[$plan_id]->accept_e_ymd,4,2)}}月{{ltrim(substr($views->plan_details[$plan_id]->accept_e_ymd,6,2),'0')}}日
                  @if(strtotime($views->plan_details[$plan_id]->accept_e_ymd) < strtotime('now'))
                    @php
                    $is_accept_ymd_validate_fail = true;
                    @endphp
                    <span class="msg-text-error">期間切れ</span>
                  @endif
                @endif
              </p>
              {{-- 販売期間が正しくないときはメッセージを表示 --}}
              @if ($is_accept_ymd_validate_fail)
                <p class="msg-text-error">※プランの編集より販売期間の設定を行ってください</p>
              @endif

              @include('ctl.common._plan_spec_icons',['plan'  => $views->plan_details[$plan_id]])

              <hr />

              {!! Form::open(['route' => ['ctl.htlsplan2.edit'], 'method' => 'post']) !!}
                <div style="float: left;">
                  &nbsp;
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="plan_id" value="{{$plan_id}}" />
                  <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                  <input type="submit" value="プランの編集" />
                </div>
              {!! Form::close() !!}

              {!! Form::open(['route' => ['ctl.htlscharge2.single'], 'method' => 'post']) !!}
                <div style="float: left; margin-left: 12px;">
                  <input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
                  <input type="hidden" name="plan_id" value="{{$plan_id}}" />
                  <input type="hidden" name="room_id" value="{{$room_id}}" />
                  <input type="hidden" name="current_ymd" value="{{strip_tags($views->date_range['current']['ymd'])}}" />
                  <input type="submit" value="料金の設定"  @if($is_accept_ymd_validate_fail) disabled @endif />
                </div>
              {!! Form::close() !!}
              <div class="clear"></div>
            </td>

            {{-- 手仕舞 --}}
            <th class="fp">手仕舞</th>
            @foreach ($views->week_days as $day)
              @if($day['ymd_mn_num'] < time())
                <td class="fp md msg-text-deactive">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @elseif($day['dow_num'] == 6)
                <td class="fp md wkd-sat">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @elseif(isset($day['is_bfo']))
                <td class="fp md wkd-bfo">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @elseif(isset($day['is_hol']))
                <td class="fp md wkd-hol">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @elseif($day['dow_num'] == 0)
                <td class="fp md wkd-sun">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @else
                <td class="fp md">
                  @if(is_null($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge']))
                    ×
                  @elseif($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['accept_status_charge'] == 1)
                    －
                  @else
                    手仕舞
                  @endif
                </td>
              @endif
            @endforeach
          </tr>


          {{-- プランの日別販売状況 --}}
          <tr class="default-hide jqs-room-plan-{{$room_id}}">
            <th>販売状況</th>
            @foreach($views->week_days as $day)
              @if($day['ymd_mn_num'] < time())
                <td class="md jqs-tooltip-target msg-text-deactive" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                    @php
                    $is_disable_tooltip = false
                    @endphp
                  @if($day['ymd_mn_num'] < time())
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                          (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                      止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      売
                      @php
                      $is_disable_tooltip = true
                      @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                      登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                      開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                      止
                  @else
                      －
                  @endif
                  @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                  @endif  
                </td>
              @elseif($day['dow_num'] == 6)
                  <td class="md jqs-tooltip-target wkd-sat" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                  @php
                  $is_disable_tooltip = false
                  @endphp
                  @if($day['ymd_mn_num'] < time())
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                          (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                      止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      売
                  @php
                      $is_disable_tooltip = true
                  @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                      登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                      開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                      止
                  @else
                      －
                  @endif
                  @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                  @endif            
                  </td>
              @elseif(isset($day['is_bfo']))
                  <td class="md jqs-tooltip-target wkd-bfo" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                      @php
                      $is_disable_tooltip = false
                      @endphp
                  @if($day['ymd_mn_num'] < time())
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                          (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                      止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      売
                      @php
                      $is_disable_tooltip = true
                      @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                      登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                      開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                      止
                  @else
                      －
                  @endif
                  @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                  @endif  
                  </td>
              @elseif(isset($day['is_hol']))
                  <td class="md jqs-tooltip-target wkd-hol" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                  @php
                  $is_disable_tooltip = false
                  @endphp
                  @if($day['ymd_mn_num'] < time())
                  販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                  販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                      (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                  止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                  売
                  @php
                      $is_disable_tooltip = true
                  @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                  登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                  開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                  止
                  @else
                  －
                  @endif
                  @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                  @endif 
                  </td>
              @elseif($day['dow_num'] == 0)
                  <td class="md jqs-tooltip-target wkd-sun" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                      @php
                      $is_disable_tooltip = false
                      @endphp
                  @if($day['ymd_mn_num'] < time())
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                          (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                      止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      売
                      @php
                      $is_disable_tooltip = true
                      @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                      登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                      開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                      止
                  @else
                      －
                  @endif
                      @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                      @endif  
                  </td>
              @else
                  <td class="md jqs-tooltip-target" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                      @php
                      $is_disable_tooltip = false
                      @endphp
                  @if($day['ymd_mn_num'] < time())
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      販売<br />終了
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_resale']) &&
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']) &&
                          (!isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without']) ||
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])))
                      止(再有)
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale']))
                      売
                      @php
                      $is_disable_tooltip = true
                      @endphp
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])||
                          isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])) 
                      登録無
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                          !isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration']))
                      開始前
                  @elseif(isset($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop']))
                      止
                  @else
                      －
                  @endif
                      @if(!$is_disable_tooltip)
                      <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_sale_still'] && $views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_full'])<p>満室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                          @if($views->sale_state_room_plan[$day['ymd_num']]['room'][$room_id]['plan'][$plan_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                      </div>
                      @endif  
                  </td>
                @endif
            @endforeach
          </tr>


          {{-- 予約室数 --}}
          <tr class="default-hide jqs-room-plan-{{$room_id}}">
            <th>予約室数</th>
            @forelse($views->week_days as $day)
              @if($day['ymd_mn_num'] < time())
                <td class="md msg-text-deactive">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @elseif($day['dow_num'] == 6)
                <td class="md wkd-sat">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @elseif(isset($day['is_bfo']))
                <td class="md wkd-bfo">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @elseif(isset($day['is_hol']))
                <td class="md wkd-hol">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @elseif($day['dow_num'] == 0)
                <td class="md wkd-sun">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @else
                <td class="md">
                  @if(isset($views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']))
                    {{$views->reserve_count_room_plan[$day['ymd_num']]['room_plan']['room'][$room_id]['plan'][$plan_id]['reserve_count']}}
                  @else
                    0
                  @endif
                </td>
              @endif
            @empty
              <tr class="default-hide jqs-room-plan-{{$room_id}}">
                <td colspan="13" class="plan msg-text-error">設定されているプランがありません</td>
              </tr>                  
            @endforelse
          </tr>
        @endforeach
      @endforeach
    </table>  
  @endif
    {{-- 余白 --}}
    <hr class="bound-line" />
    
    <div class="exp-page-under-menu">
      <p><a href="#" class="move-ptop">▲ページTOPへ</a></p>
    </div>
  
@endsection