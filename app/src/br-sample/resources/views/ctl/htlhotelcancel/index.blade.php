@extends('ctl.common._htl_base')
@section('title', '施設キャンセルポリシー')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelCancelController')

@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
施設キャンセルポリシー
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

</br>
    <table cellspacing="0" cellpadding="4" border="1">
        <tr>
            <td nowrap  bgcolor="#EEEEFF" ><br></td>
            {!! Form::open(['route' => ['ctl.htl_hotel_cancel.switch'], 'method' => 'get']) !!}
            <td align="center">
                @if($policy_status == 0)
                    <input type="submit" value=" 切り替える ">
                @else
                    <font color="red">適用中</font>
                @endif
                <input type="hidden" name="policy_status" value="1" />
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
            </td>
            {!! Form::close() !!}
            {!! Form::open(['route' => ['ctl.htl_hotel_cancel.switch'], 'method' => 'get']) !!}
            <td align="center">
                @if($policy_status == 1)
                    <input type="submit" value=" 切り替える ">
                @else
                    <font color="red">適用中</font>
                @endif
                <input type="hidden" name="policy_status" value="0" />
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
            </td>
            {!! Form::close() !!}
        </tr>
        <tr>
                <td nowrap  bgcolor="#EEEEFF" >適用選択</td>
                <td align="center">施設任意登録キャンセルポリシー</td>
                <td align="center">標準約款キャンセルポリシー</td>
        </tr>
        <tr>
            <td nowrap  bgcolor="#EEEEFF" >条件</td>
            <td valign="top">
                <table border="1" cellspacing="0" cellpadding="2" >
                <tr>
                    <td  bgcolor="#EEEEFF" >日数</td>
                    <td  bgcolor="#EEEEFF" nowrap>料率</td>
                    <td  bgcolor="#EEEEFF" nowrap><br></td>
                    <td  bgcolor="#EEEEFF" nowrap><br></td>
                </tr>
                <tr>
                    <td align="right">
                    無断不泊
                    </td>
                    <td align="right">
                    100 ％
                    </td>
                    <td><br></td>
                    <td><br></td>
                </tr>
                @php
                    $loop = count($cancel_rates['values']);
                @endphp

                @for($i = 0; $i < $loop; $i++)
                <tr>
                    {{-- 無断不泊以外 --}}
                    @if($cancel_rates['values'][$i]['days'] != -1)
                            {!! Form::open(['route' => ['ctl.htl_hotel_cancel.create'], 'method' => 'get']) !!}
                                <td align="right">
                                    宿泊日<input type="text" name="days[{{$i}}]" value="{{old('days.'.$i , $cancel_rates['values'][$i]['days'])}}" maxlength="3" size="3" style="text-align:right;"> 日前<br>
                                </td>
                                <td align="right">
                                <input type="text" name="cancel_rate[{{$i}}]" value="{{old('cancel_rate.'.$i , $cancel_rates['values'][$i]['cancel_rate'])}}" maxlength="3" size="3" style="text-align:right;"> ％<br>
                                </td>
                                <td align="right">
                        @if(is_null($cancel_rates['values'][$i]['days']))
                                        <input type="submit" value=" 登録 ">
                                        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                                        <input type="hidden" name="policy_status" value="{{strip_tags($policy_status)}}" />
                                        <input type="hidden" name="index[{{$i}}]" value="{{$i}}">
                                </td>
                            {!! Form::close() !!}
                            <td><br></td>
                        @else
                            <input type="submit" value=" 更新 ">
                            <input type="hidden" name="old_days" value="{{$cancel_rates['values'][$i]->days}}">
                            </td>
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            <input type="hidden" name="policy_status" value="{{strip_tags($policy_status)}}" />
                            <input type="hidden" name="index[{{$i}}]" value="{{$i}}">
                            {!! Form::close() !!}
                            {!! Form::open(['route' => ['ctl.htl_hotel_cancel.delete'], 'method' => 'get']) !!}
                            <td align="right">
                                <input type="submit" value=" 削除 ">
                                <input type="hidden" name="days" value="{{$cancel_rates['values'][$i]->days}}">
                                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
                            </td>
                            {!! Form::close() !!}
                        @endif
                    @endif
                    </tr>
                @endfor
                </table>
            </td>
            <td valign="top">
                <table border="1" cellspacing="0" cellpadding="2" >
                <tr>
                    <td  bgcolor="#EEEEFF" >日数</td>
                    <td  bgcolor="#EEEEFF" nowrap>料率</td>
                </tr>

                @foreach($default_cancel_rates['values'] as $default_cancel_rates)
                    {{-- 0%は表示する必要がない --}}
                    @if($default_cancel_rates['cancel_rate'] != 0)
                        <tr>
                        <td align="right">
                            @if($default_cancel_rates['days'] == -1)
                                無断不泊
                            @elseif($default_cancel_rates['days'] == 0)
                                当日
                            @elseif($default_cancel_rates['days'] == 1)
                                前日
                            @elseif($default_cancel_rates['days'] == 2)
                                前々日
                            @else
                                宿泊日{{$default_cancel_rates['days']}}日前
                            @endif
                        </td>
                        <td align="right">
                            {{$default_cancel_rates['cancel_rate']}} ％
                        </td>
                        </tr>
                    @endif
                @endforeach
                </table>
            </td>
        </tr>
    </table>
<br>
    {!! Form::open(['route' => ['ctl.htl_hotel_cancel.cancelpolicy'], 'method' => 'get']) !!}
        <table border="1" cellspacing="0" cellpadding="4" >
        <tr>
            <td nowrap  bgcolor="#EEEEFF" >コメント</td>
            <td><textarea rows="8" cols="40" name="cancel_policy" wrap="soft">{{old('cancel_policy',strip_tags($hotel_cancel_policy['cancel_policy']))}}</textarea></td>
            <td><small><small>最大全角200文字まで<br>半角文字も入力可<br><font color="#339933">キャンセルポリシーの詳細情報</font></font></td>
        </tr>
        </table>
        <input type="submit" value=" 更新 ">
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
    {!! Form::close() !!}

    @if($is_cancel_policy == true)
        <div align="right" style="width: 625;">
            {!! Form::open(['route' => ['ctl.htl_hotel_cancel.deletecancelpolicy'], 'method' => 'get']) !!}
                <input value="標準のキャンセルポリシーへ戻す" type="submit">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
            {!! Form::close() !!}
        </div>
    @endif

  <div style="margin-left:40px; margin-top:10px">
    <strong>キャンセルポリシーについて</strong>
    <ul style="margin-top:0px">
      <li><small>キャンセル料率は宿泊日からの日数に対する料率設定をお願いします。</small></li>
      <li><small>キャンセル料率0％の設定は不要です。</small></li>
      <li><small>プランにてキャンセル料率、キャンセルポリシーが設定されている場合はプランが優先されます。</small></li>
      <li><small>宿泊日999日前と設定された場合、ユーザーページには「予約時から」と表記されます。</small></li>
    </ul>
  </div>

</small></div>
<br>

@endsection