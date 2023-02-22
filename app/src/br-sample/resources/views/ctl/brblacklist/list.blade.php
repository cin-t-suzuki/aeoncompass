

@extends('ctl.common.base')
@section('title', '監視対象者予約状況')

@section('page_blade')


<div align="center">

  {{ Form::open(['route' => 'ctl.brblacklist', 'method' => 'post']) }}
    <table border="1" cellpadding="5" cellspacing="0">
      <tr>
        <td nowrap  bgcolor="#EEFFEE">予約受付日(From)</td>
        <td nowrap  bgcolor="#EEFFEE">予約受付日(To)</td>
        <td nowrap  bgcolor="#EEFFEE"><br /></td>
      </tr>
      <tr>
        <td>
          <!--  検索開始期間（From:年）  -->
          <select size="1" name="date_ymd[search_year_from]">
          
            @foreach ($selectbox_y as $option) 
                <option value={{$option}} @if($option == $form_param['date_ymd']['search_year_from']) selected @endif>
                    {{$option}} 
                </option>
            @endforeach
            
          </select>年
          <!--  検索開始期間（From:月） -->
          <select size="1" name="date_ymd[search_mon_from]">
            @foreach ($selectbox_m as $option)
                <option value={{$option}} @if($option == $form_param['date_ymd']['search_mon_from']) selected @endif>
                    {{$option}} 
                </option>
            @endforeach

          </select>月
          <!--  検索開始期間（From:日）  -->
          <select size="1" name="date_ymd[search_day_from]">
            @foreach ($selectbox_d as $option)
                <option value={{$option}} @if($option == $form_param['date_ymd']['search_day_from']) selected @endif>
                    {{$option}}
                </option>
            @endforeach
          </select>日
        </td>
        <td>
          <!--  検索開始期間（To:年）  -->
          <select size="1" name="date_ymd[search_year_to]">
            @foreach ($selectbox_y as $option)
                <option value={{$option}} @if($option == $form_param['date_ymd']['search_year_to']) selected @endif>
                    {{$option}}
                </option>
            @endforeach
          </select>年
          <!--  検索開始期間（To:月）  -->
          <select size="1" name="date_ymd[search_mon_to]">
            @foreach ($selectbox_m as $option)
              <option value={{$option}} @if($option == $form_param['date_ymd']['search_mon_to']) selected @endif>
                    {{$option}}
                </option>
            @endforeach
          </select>月
          <!--  検索開始期間（To:日）  -->
          <select size="1" name="date_ymd[search_day_to]">
            @foreach ($selectbox_d as $option)
                <option value={{$option}} @if($option == $form_param['date_ymd']['search_day_to']) selected @endif>
                    {{$option}}
                </option>
            @endforeach
          </select>日
        </td>
        <td>
          <input type="submit" value="検索">
        </td>
      </tr>
    </table>
  {!! Form::close() !!}
</div>
<br />


<div align="center">
  @if (empty($result))
    検索結果は見つかりませんでした
  @else
    <table border="1" cellpadding="5s" cellspacing="0">
      <tr>
        <td nowrap  bgcolor="#EEFFEE">予約コード</td>
        <td nowrap  bgcolor="#EEFFEE">施設コード<br />施設名<br />予約詳細</td>
        <td nowrap  bgcolor="#EEFFEE">予約者<br />宿泊代表者</td>
        <td nowrap  bgcolor="#EEFFEE">予約日<br /><font color="#FF9999">取消日</font></td>
        <td nowrap  bgcolor="#EEFFEE">宿泊日</td>
        <td nowrap  bgcolor="#EEFFEE">宿泊人数</td>
        <td nowrap  bgcolor="#EEFFEE">予約状態</td>
        <td nowrap  bgcolor="#EEFFEE">予約者詳細</td>
      </tr>

      @foreach ($result as $user)
        <tr>
          <!--  予約コード  -->
          <td>
            {{$user->reserve_cd}}
          </td>

          <!--  施設名  -->
          <td>
            {{$user->hotel_cd}}<br />
            {{$user->hotel_nm}}<br />

            {{ Form::open(['route' => 'ctl.htlreservemanagement.reserveinfo', 'method' => 'post']) }}
              <input type="hidden" name="target_cd" value="{{$user->hotel_cd}}">
              <input type="hidden" name="reserve_cd" value="{{$user->reserve_cd}}">
              <input type="hidden" name="partner_ref" value="{{$user->partner_ref}}">
              <input type="hidden" name="date_ymd" value="{{$user->date_ymd}}">
              <input type="submit" name="i_btn" VALUE="予約詳細">
            {!! Form::close() !!}
          </td>
          <!--  予約者氏名  -->
          <td>
            @if(isset($user->family_nm) )
                {{$user->family_nm}}&nbsp; {{$user->given_nm}}
            <!-- {else} -->
            @else
              非会員
            @endif
            <br />
            @if(isset($user->guest_nm))
                {{$user->guest_nm}}
            @endif
          </td>
          <!--  予約日/取消日  -->
          <td>
            <!-- 曜日のセット -->
            @php 
                $week = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat',];
                $reservedate=date('w', strtotime($user->reserve_dtm));

                if(isset($user->cancel_dtm)){
                    $canceldate=date('w', strtotime($user->cancel_dtm));
                }   

                $reservedate=date('w', strtotime($user->date_ymd));
            @endphp

            {{date('Y年m月d日', strtotime($user->reserve_dtm))}} ( {{$week[$reservedate]}} )<br />
            
            <font color="#FF9999">
            @if(isset($user->cancel_dtm))
                {{date('Y年m月d日', strtotime($user->cancel_dtm))}} ( {{$week[$canceldate]}} )<br />
            @endif
            </font>
          </td>
          <!--  宿泊日  -->
          <td>
          {{date('Y年m月d日', strtotime($user->date_ymd))}} ( {{$week[$reservedate]}} )<br /><br />
             </td>
          <!--  宿泊人数  -->
          <td>
            {{$user->guests}}人
          </td>
          <!--  予約状態  -->
          <td>
            @if($user->reserve_status == 0)   <font color="#0000FF">予約</font>
            @elseif( $user->reserve_status == 1) <font color="#FF0000">キャンセル</font>
            @elseif( $user->reserve_status == 2)<font color="#FF0000">電話キャンセル</font>
            @elseif( $user->reserve_status == 4) <font color="#FF0000">無断不泊</font>
            @endif
          </td>
         <!--  予約者詳細  -->
          <td>
            {{ Form::open(['route' => 'ctl.htlreserve.memberinfo', 'method' => 'post']) }}  <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($target_user.hotel_cd)}">

              <input type="hidden" name="member_cd" value="{{$user->member_cd}}" />
              <input type="hidden" name="reserve_cd" value="{{$user->reserve_cd}}" />
              <input type="submit" name="i_btn" VALUE="予約者詳細">
            {!! Form::close() !!}
          </td>
        </tr>
      @endforeach
    </table>
  @endif
</div>
<br>
@endsection