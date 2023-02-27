{{--JavaScript指定--}}
@section('headScript')
<script type="text/javascript">
<!--
    $(document).ready(function () {
    $('a.move-ptop').scrollPageTop();
    $('.jqs-show-hide-room').showAndHideRoomPlans({target_selecter:'jqs-plan-room'});
    $('.jqs-tooltip-target').showHideToolTop();
    });
-->
</script>
@endsection

{{-- ヘッダーのテンプレート読み込み --}}
  @extends('ctl.common.base3', [
'title' => 'プラン手仕舞の調整',
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
        
        {{-- 室数の調整 --}}
        @if (Route::currentRouteName() === 'ctl.htlsroomoffer.list')
            <div class="elm elm-active">
                【室数の調整】
            </div>
        @else
            {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post']) !!}
            <div class="elm">
                <input type="hidden" name="target_cd" value="{{$hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
                <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                <input type="hidden" name="start_ymd[year]"  value="{{$date_range['current']['year']}}" />
                <input type="hidden" name="start_ymd[month]" value="{{$date_range['current']['month']}}" />
                <input type="hidden" name="start_ymd[day]"   value="{{$date_range['current']['day']}}" />
                <input type="submit" value="室数の調整" />
            </div>
            {!! Form::close() !!}
        @endif
        
        {{-- 日毎プランの調整 --}}
        @if (Route::currentRouteName() === 'ctl.htlsplanoffer.list')
            <div class="elm elm-active">
                【日毎にプラン売止/販売を行う】
            </div>
        @else
            {!! Form::open(['route' => ['ctl.htlsplanoffer'], 'method' => 'post']) !!} 
            <div class="elm">
                <input type="hidden" name="target_cd" value="{{$hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
                <input type="hidden" name="start_ymd[year]"  value="{{$date_range['current']['year']}}" />
                <input type="hidden" name="start_ymd[month]" value="{{$date_range['current']['month']}}" />
                <input type="hidden" name="start_ymd[day]"   value="{{$date_range['current']['day']}}" />
                <input type="submit" value="日毎にプラン売止/販売を行う" />
            </div>
            {!! Form::close() !!}
        @endif
        
        {{-- 料金を調整する --}}
        @if (Route::currentRouteName() === 'ctl.htlschargeoffer.list')
            <div class="elm elm-active">
                【料金を調整する】
            </div>
        @else
            {!! Form::open(['route' => ['ctl.htlschargeoffer'], 'method' => 'post']) !!} 
                <div class="elm">
                    <input type="hidden" name="target_cd" value="{{$hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
                    <input type="hidden" name="start_ymd[year]"  value="{{$date_range['current']['year']}}" />
                    <input type="hidden" name="start_ymd[month]" value="{{$date_range['current']['month']}}" />
                    <input type="hidden" name="start_ymd[day]"   value="{{$date_range['current']['day']}}" />
                    <input type="submit" value="料金を調整する" />
                </div>
            {!! Form::close() !!}
        @endif
        
        {{-- プランの期間を延長する --}}
        @if (Route::currentRouteName() === 'ctl.htlsextendoffer.list')
            <div class="elm elm-active">
                【プランの期間を延長する】
            </div>
        @else
            {!! Form::open(['route' => ['ctl.htlsextendoffer'], 'method' => 'post']) !!} 
                <div class="elm">
                    <input type="hidden" name="target_cd" value="{{$hotel['hotel_cd']}}">{{-- TODO:$v->user 認証関連機能ができ次第修正 --}}
                    <input type="submit" value="プランの期間を延長する" />
                </div>
            {!! Form::close() !!}
        @endif
        
        <div class="clear"></div>
    </div>
  
    {{-- 余白 --}}
    <hr class="bound-line" />
    @if(is_null($plan_details))
        <p class="msg-text-error">登録されているプランがありません</p>
    @else
        {{-- プラン・部屋の一覧 --}}
        {{-- プランIDの一覧を作成 --}}
        <table class="tbl-room-plan-list">
        <tr>
            <th id="menu" colspan="16">
            {{-- 操作期間の変更 --}}
            <div class="calendar-menu">
                <div class="menu">
                    {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'post']) !!}
                    <div class="elm">
                        <span>表示期間の変更：</span>
                        <select name="start_ymd[year]">
                            @for($i=$start_date['year']; $i < $end_date['year']+1; $i++)
                                <option value={{$i}}  @if($i == $date_range['current']['year']) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                        <span>年</span>
                        <select name="start_ymd[month]">
                            @for($i=0; $i <= 12; $i++)
                                <option value={{$i}}  @if($i == $date_range['current']['month']) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                        <span>月</span>
                        <select name="start_ymd[day]">
                            @for($i=0; $i <= 31; $i++)
                                <option value={{$i}}  @if($i == $date_range['current']['day']) selected @endif>{{$i}}</option>
                            @endfor
                        </select>
                        <span>日</span>
                        <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                        <input type="submit" value="切替"  class="btn" />
                    </div>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'post']) !!}
                    <div class="elm">
                        <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                        <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($date_range['week_bfo']['year'])}}" />
                        <input type="hidden" name="start_ymd[month]" value="{{strip_tags($date_range['week_bfo']['month'])}}" />
                        <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($date_range['week_bfo']['day'])}}" />
                        <input type="submit" value="<< 2週間前" class="btn" />
                    </div>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'post']) !!}
                    <div class="elm">
                    <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                    <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($date_range['day_bfo']['year'])}}" />
                    <input type="hidden" name="start_ymd[month]" value="{{strip_tags($date_range['day_bfo']['month'])}}" />
                    <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($date_range['day_bfo']['day'])}}" />
                    <input type="submit" value="<前の日" class="btn" />
                    </div>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'post', 'style' =>'display:inline;']) !!}
                    <div class="elm">
                        <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                        <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($date_range['day_aft']['year'])}}" />
                        <input type="hidden" name="start_ymd[month]" value="{{strip_tags($date_range['day_aft']['month'])}}" />
                        <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($date_range['day_aft']['day'])}}" />
                        <input type="submit" value="次の日>" class="btn" />
                    </div>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'post', 'style' =>'display:inline;']) !!}
                    <div class="elm">
                        <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                        <input type="hidden" name="start_ymd[year]"  value="{{strip_tags($date_range['week_aft']['year'])}}" />
                        <input type="hidden" name="start_ymd[month]" value="{{strip_tags($date_range['week_aft']['month'])}}" />
                        <input type="hidden" name="start_ymd[day]"   value="{{strip_tags($date_range['week_aft']['day'])}}" />
                        <input type="submit" value="2週間後 >>" class="btn" />
                    </div>
                {!! Form::close() !!}
                <div class="clear"></div>
                </div>
            </div>
            </th>
        </tr>

        {{-- 2週間分の日程 --}}
        <tr>
            <th class="emp" rowspan="3">&nbsp;</th>
            <th>日程</th>
            @foreach($week_days as $day)
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

        {{-- 2週間分の各日の一括設定 --}}
        <tr>
            <th>日毎調整</th>
            @foreach ($week_days as $day)
                @if($day['dow_num'] == 6)
                    <td class="md wkd-sat">
                        {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'get']) !!}
                        <div>
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="ui_type" value="date" />
                            <input type="hidden" name="target_ymd" value="{{strip_tags($day['ymd_num'])}}" />
                            <input type="hidden" name="current_ymd" value="{{strip_tags($date_range['current']['ymd'])}}" />
                            @foreach($plan_details as $plan_id => $plan_detail)
                                <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
                            @endforeach
                            <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                        </div>
                        {!! Form::close() !!}
                    </td>
                @elseif(isset($day['is_bfo']))
                    <td class="md wkd-bfo">
                        {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'post']) !!}
                        <div>
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="ui_type" value="date" />
                            <input type="hidden" name="target_ymd" value="{{strip_tags($day['ymd_num'])}}" />
                            <input type="hidden" name="current_ymd" value="{{strip_tags($date_range['current']['ymd'])}}" />
                            @foreach($plan_details as $plan_id => $plan_detail)
                                <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
                            @endforeach
                            <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                        </div>
                        {!! Form::close() !!}
                    </td>
                @elseif(isset($day['is_hol']))
                    <td class="md wkd-hol">
                        {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'post']) !!}
                        <div>
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="ui_type" value="date" />
                            <input type="hidden" name="target_ymd" value="{{strip_tags($day['ymd_num'])}}" />
                            <input type="hidden" name="current_ymd" value="{{strip_tags($date_range['current']['ymd'])}}" />
                            @foreach($plan_details as $plan_id => $plan_detail)
                                <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
                            @endforeach
                            <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                        </div>
                        {!! Form::close() !!}
                    </td>
                @elseif($day['dow_num'] == 0)
                    <td class="md wkd-sun">
                        {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'post']) !!}
                        <div>
                            <input type="hidden" name="target_cd"   value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="ui_type"     value="date" />
                            <input type="hidden" name="target_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                            <input type="hidden" name="current_ymd" value="{{strip_tags($date_range['current']['ymd'])}}" />
                            @foreach($plan_details as $plan_id => $plan_detail)
                                <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
                            @endforeach
                        <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                        </div>
                        {!! Form::close() !!}
                    </td>
                @else
                    <td class="md">
                        {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'post']) !!}
                        <div>
                            <input type="hidden" name="target_cd"   value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="ui_type"     value="date" />
                            <input type="hidden" name="target_ymd"    value="{{strip_tags($day['ymd_num'])}}" />
                            <input type="hidden" name="current_ymd" value="{{strip_tags($date_range['current']['ymd'])}}" />
                            @foreach($plan_details as $plan_id => $plan_detail)
                                <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
                            @endforeach
                        <input type="submit" value="全て" @if($day['ymd_mn_num'] < time()) disabled @endif/>
                        </div>
                        {!! Form::close() !!}
                    </td>
                @endif
            @endforeach 
        </tr>

        {{-- 2週間分の各日の予約室数合計 --}}
        <tr>
            <th>予約室数合計</th>
            @foreach ($week_days as $day)
                @if($day['ymd_mn_num'] < time())
                    <td class="md msg-text-deactive">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @elseif($day['dow_num'] == 6)
                    <td class="md wkd-sat">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @elseif(isset($day['is_bfo']))
                    <td class="md wkd-bfo">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @elseif(isset($day['is_hol']))
                    <td class="md wkd-hol">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @elseif($day['dow_num'] == 0)
                    <td class="md wkd-sun">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @else
                    <td class="md">
                        @if(isset($reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']))
                            {{$reserve_count_plan_room[$day['ymd_num']]['reserve_count_sum']}}
                        @else
                            0
                        @endif
                    </td>
                @endif
            @endforeach
          </tr>
        
        {{-- プラン情報 --}}
        @foreach($plan_details as $plan_id => $plan_detail)
            {{-- 販売期間が未設定 or 期限切れの判定 --}}
            @php
                $is_accept_ymd_validate_fail = false;
            @endphp

            <tr>
                <td class="plan" rowspan="5">
                    {{-- プラン名称 --}}
                    <p>{{$plan_details[$plan_id]->plan_nm}}</p>

                    {{-- PMSコード --}}
                    <p>[{{$plan_details[$plan_id]->pms_cd}}]</p>

                    {{-- 販売期間 --}}
                    <p>
                        @if (empty($plan_details[$plan_id]->accept_s_ymd) || empty($plan_details[$plan_id]->accept_e_ymd))
                            @php
                                $is_accept_ymd_validate_fail = true;
                            @endphp
                            <span class="msg-text-error alert-msg">販売期間未設定</span>
                        @else
                            {{substr($plan_details[$plan_id]->accept_s_ymd,0,4)}}年{{substr($plan_details[$plan_id]->accept_s_ymd,4,2)}}月{{ltrim(substr($plan_details[$plan_id]->accept_s_ymd,6,2),'0')}}日
                            &nbsp;～&nbsp;
                            {{substr($plan_details[$plan_id]->accept_e_ymd,0,4)}}年{{substr($plan_details[$plan_id]->accept_e_ymd,4,2)}}月{{ltrim(substr($plan_details[$plan_id]->accept_e_ymd,6,2),'0')}}日

                            @if(strtotime($plan_details[$plan_id]->accept_e_ymd) < strtotime('now'))
                                @php
                                    $is_accept_ymd_validate_fail = true;
                                @endphp
                                &nbsp;
                                <span class="msg-text-error alert-msg">期間切れ</span>
                            @endif
                        @endif
                    </p>
                    
                    {{-- 販売期間が正しくないときはメッセージを表示 --}}
                    @if($is_accept_ymd_validate_fail)
                        <p class="msg-text-error alert-msg">※プランの編集より販売期間の設定を行ってください</p>
                    @endif
                    
                    {{-- プランスペック --}}
                    @include('ctl.common._plan_spec_icons',['plan'  => $plan_details[$plan_id]])
                    
                    <hr />
                    
                    {!! Form::open(['route' => ['ctl.htlsplan2.edit'], 'method' => 'post']) !!}
                        <div>
                            <input type="button" name="show_hide_room" class="jqs-show-hide-room" id="plan-id-{{$plan_id}}" value="+" />
                            &nbsp;
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="plan_id" value="{{$plan_id}}" />
                            {{-- <input type="hidden" name="return_path" value="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/" /> --}}
                            <input type="hidden" name="current_ymd" value="{{$date_range['current']['ymd']}}" />
                            <input type="submit" value="プランの編集" />
                        </div>
                    {!! Form::close() !!}   
                </td>
            </tr>
            
            {{-- 各日の日付 --}}
            <tr>
                <th class="fp">日程</th>
                @foreach($week_days as $day)
                    @if($day['ymd_mn_num'] < time())
                        <th class="fp md msg-text-deactive">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @elseif($day['dow_num'] == 6)
                        <th class="fp md wkd-sat">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @elseif(isset($day['is_bfo']))
                        <th class="fp md wkd-bfo">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @elseif(isset($day['is_hol']))
                        <th class="fp md wkd-hol">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @elseif($day['dow_num'] == 0)
                        <th class="fp md wkd-sun">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @else
                        <th class="fp md">{{date('n',$day['ymd_num'])}}/{{date('j',$day['ymd_num'])}}</th>
                    @endif
                @endforeach
            </tr>

            {{-- 手仕舞 --}}
            <tr>
                <th>手仕舞</th>
                @foreach($week_days as $day)
                    @if($day['ymd_mn_num'] < time())
                        <td class="md msg-text-deactive">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @elseif($day['dow_num'] == 6)
                        <td class="md wkd-sat">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @elseif(isset($day['is_bfo']))
                        <td class="md wkd-bfo">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @elseif(isset($day['is_hol']))
                        <td class="md wkd-hol">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @elseif($day['dow_num'] == 0)
                        <td class="md wkd-sun">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @else
                        <td class="md">
                        @if($day['ymd_mn_num'] < time())
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                ×
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                手仕舞
                            @else
                                －
                            @endif
                        @else
                            @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_sale'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=0">－</a>
                            @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['accept_status_charge']['is_stop'])
                                <a href="edit/?target_cd={{$target_cd}}&amp;ui_type=plan&amp;plan_id[]={{$plan_id}}&amp;target_ymd={{$day['ymd_num']}}&amp;current_ymd={{$date_range['current']['ymd']}}&amp;accept_status=1">手仕舞</a>
                            @else
                                ×
                            @endif
                        @endif
                        </td>
                    @endif
                @endforeach
            </tr>
            
            {{-- 販売状況 --}}
            <tr>
                <th>販売状況</th>
                @foreach($week_days as $day)
                    @if($day['ymd_mn_num'] < time())
                        <td class="md msg-text-deactive">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @elseif($day['dow_num'] == 6)
                        <td class="md wkd-sat">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @elseif(isset($day['is_bfo']))
                        <td class="md wkd-bfo">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @elseif(isset($day['is_hol']))
                        <td class="md wkd-hol">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @elseif($day['dow_num'] == 0)
                        <td class="md wkd-sun">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @else
                        <td class="md">
                            @if ($day['ymd_mn_num'] < time())
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale_still']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])      &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']))
                                開始前
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_expiration']) &&
                                    !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                販売<br />終了
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale'])  &&
                                    (isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) ||
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without'])))
                                一部売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_sale']))
                                売
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']) &&
                                    isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_resale']))
                                止(再有)
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_stop']))
                                止
                            @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['sale_status']['is_without']))
                                登録無
                            @else
                                －
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>

            {{-- 予約室数 --}}
            <tr>
                <th>予約室数</th>
                @foreach($week_days as $day)
                    @if($day['ymd_mn_num'] < time())
                        <td class="md msg-text-deactive">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @elseif($day['dow_num'] == 6)
                        <td class="md wkd-sat">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @elseif(isset($day['is_bfo']))
                        <td class="md wkd-bfo">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @elseif(isset($day['is_hol']))
                        <td class="md wkd-hol">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @elseif($day['dow_num'] == 0)
                        <td class="md wkd-sun">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @else
                        <td class="md">
                            @if(isset($reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']))
                                {{$reserve_count_plan_room[$day['ymd_num']]['plan'][$plan_id]['reserve_count']}}
                            @else
                                0
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
                           
            {{-- 部屋情報 --}}
            @foreach($match_plan_rooms_all[$plan_id] as $room_id)
                <tr class="default-hide jqs-plan-room-{{$plan_id}}">
                    <td class="room" rowspan="4">
                        {{-- 部屋名称 --}}
                        <p>{{$room_details[$room_id]->room_nm}}</p>
                        
                        {{-- PMSコードと連携在庫コード --}}
                        <p>
                            [{{$room_details[$room_id]->pms_cd}}]&nbsp;@if($room_details[$room_id]->akafu_cd) [{{$room_details[$room_id]->akafu_cd}}] @endif
                        </p>
                        
                        {{-- 部屋スペック --}}
                        @include('ctl.common._room_spec_icons',['room'  => $room_details[$room_id]])

                        <hr />
                    
                        {!! Form::open(['route' => ['ctl.htlscharge2.single'], 'method' => 'post']) !!}
                            <div style="float: left;">
                                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                                <input type="hidden" name="plan_id" value="{{$plan_id}}" />
                                <input type="hidden" name="room_id" value="{{$room_id}}" />
                                <input type="hidden" name="current_ymd" value="{{$date_range['current']['ymd']}}" />
                                <input type="submit" value="料金の設定" @if($is_accept_ymd_validate_fail) disabled="disabled" @endif />
                            </div>
                        {!! Form::close() !!}

                        {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post']) !!}
                            <div style="float: left; margin-left: 12px;">
                                <input type="hidden" name="target_cd" value="{{$target_cd}}" />
                                <input type="hidden" name="ui_type" value="calender" />
                                <input type="hidden" name="room_id" value="{{$room_id}}" />
                                <input type="hidden" name="date_ym" value="{{$date_range['current']['ymd']}}" />
                                <input type="hidden" name="current_ymd" value="{{$date_range['current']['ymd']}}" />
                                <input type="submit" value="部屋数の設定" />
                            </div>
                        {!! Form::close() !!}
                    </td>
                
                    {{-- 提供室数 --}}
                    <th class="fr">提供室数</th>
                    @foreach($week_days as $day)
                        @if($day['ymd_mn_num'] < time())
                            <td class="fr md msg-text-deactive">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif($day['dow_num'] == 6)
                            <td class="fr md wkd-sat">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif(isset($day['is_bfo']))
                            <td class="fr md wkd-bfo">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif(isset($day['is_hol']))
                            <td class="fr md wkd-hol">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif($day['dow_num'] == 0)
                            <td class="fr md wkd-sun">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @else
                            <td class="fr md">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            
                {{-- 残室数 --}}
                <tr class="default-hide jqs-plan-room-{{$plan_id}}">
                    <th>残室数</th>
                    @foreach($week_days as $day)
                        @if($day['ymd_mn_num'] < time())
                            <td class="md msg-text-deactive">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif        
                            </td>
                        @elseif($day['dow_num'] == 6)
                            <td class="md wkd-sat">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif(isset($day['is_bfo']))
                            <td class="md wkd-bfo">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif(isset($day['is_hol']))
                            <td class="md wkd-hol">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @elseif($day['dow_num'] == 0)
                            <td class="md wkd-sun">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @else
                            <td class="md">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']))
                                    {{$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['remaining_rooms']}}
                                @else
                                    無し
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            
                {{-- 部屋の手仕舞状況 --}}
                <tr class="default-hide jqs-plan-room-{{$plan_id}}">
                    <th>手仕舞</th>
                    @foreach($week_days as $day)
                        @if($day['ymd_mn_num'] < time())
                            <td class="md msg-text-deactive">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @elseif($day['dow_num'] == 6)
                            <td class="md wkd-sat">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @elseif(isset($day['is_bfo']))
                            <td class="md wkd-bfo">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @elseif(isset($day['is_hol']))
                            <td class="md wkd-hol">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @elseif($day['dow_num'] == 0)
                            <td class="md wkd-sun">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @else
                            <td class="md">
                                @if(!empty($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count']))
                                    ×
                                @elseif($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['accept_status_room_count'] == 1)
                                    －
                                @else
                                    手仕舞
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            
                <tr class="default-hide jqs-plan-room-{{$plan_id}}">
                    {{-- 部屋・プランでの販売状況 --}}
                    <th>販売状況</th>
                    @foreach($week_days as $day)
                        @if($day['ymd_mn_num'] < time())
                            <td class="md jqs-tooltip-target msg-text-deactive" id="tip-id-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}}">
                                @php
                                    $is_disable_tooltip = false
                                @endphp
            
                                @if($day['ymd_mn_num'] < time())
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
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
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
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
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
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
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
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
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
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
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    販売<br />終了
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_resale']) &&
                                        isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop'])   &&
                                        (!isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])))
                                    止(再有)
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale']))
                                    売
                                @php
                                    $is_disable_tooltip = true
                                @endphp
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without']) || isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without']))
                                    登録無
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still']) &&
                                        !isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration']))
                                    開始前
                                @elseif(isset($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop']))
                                    止
                                @else
                                    －
                                @endif

                                @if(!$is_disable_tooltip)
                                    <div class="jqs-tooltip-{{$day['ymd_num']}}-{{$room_id}}-{{$plan_id}} tooltip">
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room'])<p>部屋が休止中です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_plan']) <p>プランが休止中です</p>@endif                  
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>販売が終了しています</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_room_count'])<p>部屋が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stop_charge'])<p>料金が手仕舞です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_sale_still'] && !$sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_expiration'])<p>まだ販売が開始されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_full'])<p>満室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_zero'])<p>提供室数が0室です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_zero'])<p>料金が0円です</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_charge_without'])<p>料金が登録されていません</p>@endif
                                        @if($sale_state_plan_room[$day['ymd_num']]['plan'][$plan_id]['room'][$room_id]['sale_status']['is_stock_without'])<p>在庫が登録されていません</p>@endif               
                                    </div>
                                @endif  
                            </td>
                        @endif
                    @endforeach
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