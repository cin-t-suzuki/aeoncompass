{{-- MEMO: 移植元 single.tpl --}}
  {{--  ヘッダー --}}

  {{--  JavaScriptアクションの定義 --}}
  @section('headScript')
    <script type="text/javascript" src="{{ asset('/js/brj.ctl.js') }}"></script>
      <script type="text/javascript">
        <!--
          $(document).ready(function () {
            $('a.move-ptop').scrollPageTop();
          });
        -->
      </script>
    @endsection
  {{-- ヘッダーのテンプレート読み込み --}}
  @extends('ctl.common.base3', [
    'title' => '料金の編集期間設定',
    'screen_type' => 'htl',
    'js_staff_navi' => 'on',
    'js_htl_navi' => 'on'
    ])
@section('content')
  {{-- メッセージ --}}

  {{-- 余白 --}}
  <hr class="bound-line" />

  {{-- エラーメッセージ or 通知メッセージ --}}
  @include('ctl.common.message2')

  {{-- 余白 --}}
  <hr class="bound-line" />
  
  {{-- プラン情報 --}}
  @include('ctl.htlsCharge2.info_plan',[
    'plan' => $plan_detail,
    'premium_status' => $v->user->hotel->premium_status
    ])
  
  {{-- 余白 --}}
  <hr class="bound-line-l" />

  {{-- 部屋情報 --}}

  {{--移植元ソース
  {include
    file='./_info_room.tpl'
    rooms_idx    = $v->assign->opration_status_rooms.target_rooms
    rooms_detail = $v->assign->plan_has_rooms_detail
  }
  --}}

  {{-- $target_roomsの取得すべき内容を確認 --}}
  {{-- 移植後ソース --}}
  @include('ctl.htlsCharge2.info_room',[
    'rooms_idx' => $opration_status_rooms['target_rooms'],
    'rooms_detail' => $plan_has_rooms_detail
    ])
    

  
  {{-- 余白 --}}
  <hr class="bound-line-l" />
  

  {{-- 子供料金設定 --}}
  {{-- 元ソース 
  {include
    file='./_info_child_charge.tpl'
    child_charge = $v->assign->request_params
    charge_type  = $v->assign->plan_detail.charge_type
  }
  --}}

  {{-- 移植後ソース予定 --}}
  @include('ctl.htlsCharge2._info_child_charge',[
    'child_charge' => $request_params,
    'charge_type' => $plan_detail->charge_type,
    ])

  
  {{-- 余白 --}}
  <hr class="bound-line-l" />
  

  {{-- 入力期間 --}}
  <form action="{$v->env.source_path}{$v->env.module}/htlscharge2/edit/" method="post">
    <div class="gen-container">
      <h2 class="contents-header">料金の設定を行う期間</h2>
      
      {{-- TODO:消費税告知は不要?コメントアウト --}}
      {{-- 2014年4月の消費税告知 --}}
      {{-- {include file=$v->env.module_root|cat:'/view2/_common/_consumption_tax_201404.tpl' type='message'} --}}
      
      {{-- 余白 --}}
      <hr class="bound-line-l" />
      
      <div id="select-ymd">
        {{-- 移植後はこのようになる予定 --}}
        {{-- 開始日 --}}
        {!!Form::select('from_year',$plan_accept_ymd_selecter['options']['year'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['year'])!!}
        
        {!!Form::select('from_month',$plan_accept_ymd_selecter['options']['month'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['month'])!!}

        {!!Form::select('from_day',$plan_accept_ymd_selecter['options']['day'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['day'])!!}

        &nbsp;～&nbsp;
        {{-- 移植後はこのようになる予定 --}}

        {{-- 終了日 --}}
        {!!Form::select('to_year',$plan_accept_ymd_selecter['options']['year'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['year'])!!}
        
        {!!Form::select('to_month',$plan_accept_ymd_selecter['options']['month'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['month'])!!}
        
        {!!Form::select('to_day',$plan_accept_ymd_selecter['options']['day'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['day'])!!}

      </div>
      
      {{-- 余白 --}}
      <hr class="bound-line" />
      
      <div>
        <input type="hidden" name="target_cd"      value="{$v->assign->request_params.target_cd}" />
        <input type="hidden" name="plan_id"        value="{$v->assign->request_params.plan_id}" />
        <input type="hidden" name="room_id"        value="{$v->assign->request_params.room_id}" />
        <input type="hidden" name="target_rooms[]" value="{$v->assign->request_params.room_id}" />
        <input type="hidden" name="pre_action"     value="single" />
        <input type="hidden" name="return_path"    value="{$v->assign->request_params.return_path}"/>
        <input type="hidden" name="current_ymd"    value="{$v->assign->request_params.current_ymd}"/>
        <input type="submit" value="料金の入力へ" class="submit-m" />
      </div>
      
    </div>
    
  </form>
  
  {{-- 余白 --}}
  <hr class="bound-line-l" />
  

  {{-- 料金カレンダー --}}
  {{-- 移植元ソース
  {include
    file='./_info_calendar.tpl'
    calendar_base  = $v->assign->calendar
    capacity_range = $v->assign->target_capacities
    charge_values  = $v->assign->request_params
    charge_type    = $v->assign->plan_detail.charge_type
    low_price_info = $v->assign->low_price_info
  }
  --}}

  {{-- 移植後予定ソース --}}
  @include('ctl.htlsCharge2._info_calendar',[
    'calendar_base' => $calendar,
    'capacity_range' => $target_capacities,
    'charge_values' => $request_params,
    'charge_type' => $plan_detail->charge_type,
    'low_price_info' =>$low_price_info ,
    ])

  {{-- 料金コピーへの遷移 --}}
{{--
  <form action="{$v->env.source_path}{$v->env.module}/htlscopycharge/" method="post">
    <div class="gen-container">
      <input type="hidden" name="target_cd"        value="{$v->assign->request_params.target_cd}" />
        <input type="hidden" name="copy_dest_plan" value="{$v->assign->request_params.plan_id}" />
        <input type="submit" value="別の既存プランから料金をコピー" class="submit-m" />
    </div>
  </form>
  
  <hr class="bound-line-l" />
--}}
  
  <div class="page-under-menu">
    <p><a href="#" class="move-ptop">▲ページTOPへ</a></p>
    <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="post">
      <div>
        <input type="hidden" name="target_cd"  value="{$v->assign->request_params.target_cd}" />
        <input type="submit" value="プランメンテナンスへ" />
      </div>
    </form>
  </div>
  
  {{-- 余白 --}}
  <hr class="bound-line-l" />
  

  {{-- フッター --}}
  {{-- {include file=$v->env.module_root|cat:'/view2/_common/_footer2.tpl'} --}}
@endsection

