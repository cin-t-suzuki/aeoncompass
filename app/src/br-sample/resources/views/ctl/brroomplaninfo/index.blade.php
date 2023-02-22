

@extends('ctl.common.base')
@section('title', '部屋プラン情報一覧')

@section('page_blade')


{{ Form::open(['route' => 'ctl.brroomplaninfo.index', 'method' => 'post']) }}
  <table border="1" cellpadding="3" cellspacing="0">
    <tr>
      <td bgcolor="#EEFFEE">施設コード</td>
      <td nowrap="nowrap">
        <input name="hotel_cd" size="30" maxlength="50" type="text" value="{{ $hotel_cd }}"> ※完全一致
         </td>
      <td nowrap="nowrap">
    <input value="検索" type="submit">
      </td>
    </tr>
  </table>
  <br>


 @if(empty($planinfo['hotel_cd']) == false)
  <p>
      <font color="#cdcdcd">■</font>
      <b>ホテル情報</b>
    </p>
    <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th bgcolor="#f0e68c" align="left">ホテル名称</th>
      <th bgcolor="#f0e68c" align="left">ホテルコード</th>
      <th bgcolor="#f0e68c" align="left">マイグレーション</th>
      <th bgcolor="#f0e68c" align="left">使用画面</th>
    </tr>
    <tr>
      <td bgcolor="#EEFFEE">{{$planinfo['hotel_nm']}}</td>
      <td bgcolor="#EEFFEE">{{$planinfo['hotel_cd']}}</td>
      <td bgcolor="#EEFFEE">{{$planinfo['mygration']}}</td>
      <td bgcolor="#EEFFEE">{{$planinfo['use_screen']}}</td>
    </tr>
   </table>
  <br>
  <p>
    <font color="#cdcdcd">■</font>
    <b>部屋情報</b>
  </p>
    <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th bgcolor="#f0e68c" align="left">部屋名称</th>
      <th bgcolor="#f0e68c" align="left">部屋コード</th>
      <th bgcolor="#f0e68c" align="left">ラベルコード</th>
      <th bgcolor="#f0e68c" align="left">受付／休止</th>
    </tr>


    @if($planinfo['room_list'])
      @foreach ($planinfo['room_list'] as $room)
      <tr>
        <td bgcolor="#EEFFEE">{{$room->room_nm}}</td>
        <td bgcolor="#EEFFEE">{{$room->room_id}}</td>
       
        @if(empty($room->label_cd) == false)
          <td bgcolor="#EEFFEE">{{$room->label_cd}}</td>
        @else
          <td bgcolor="#EEFFEE">-</td>
        @endif

        @if($room->accept_status == 0)
          <td bgcolor="#EEFFEE">停止中</td>
        @else
          <td bgcolor="#EEFFEE">受付中</td>
        @endif
      </tr>
      @endforeach
    @else
      <div>
      <font color="#FF0000;">登録されている部屋情報はありません。</font>
      </div>
    @endif
    </table>
    <br>
    <p>
      <font color="#cdcdcd">■</font>
      <b>プラン情報</b>
    </p>
    <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th bgcolor="#f0e68c" align="left">プラン名称</th>
      <th bgcolor="#f0e68c" align="left">プランコード</th>
      <th bgcolor="#f0e68c" align="left">ラベルコード</th>
      <th bgcolor="#f0e68c" align="left">受付／休止</th>
    </tr>
    @if(empty($planinfo['plan_list'])==true)
      <div>
      <font color="#FF0000;">登録されているプラン情報はありません。</font>
      </div>
    @else
      @foreach ($planinfo['plan_list'] as $plan)
      <tr>
        <td bgcolor="#EEFFEE">{{$plan->plan_nm}}</td>
        <td bgcolor="#EEFFEE">{{$plan->plan_id}}</td>
        
        @if(empty($plan->label_cd) == false)
          <td bgcolor="#EEFFEE">{{$plan->label_cd}}</td>
        @else
          <td bgcolor="#EEFFEE">-</td>
        @endif

        @if($plan->accept_status == 0)
          <td bgcolor="#EEFFEE">停止中</td>
        @else
          <td bgcolor="#EEFFEE">受付中</td>
        @endif
      </tr>
      @endforeach
    @endif

    </table>
@endif
{!! Form::close() !!}

@endsection
