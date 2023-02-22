

@extends('ctl.common.base')
@section('title', '提携先別専用料金登録プラン一覧')

@section('page_blade')

{{ Form::open(['route' => 'ctl.brpartnertotal.search', 'method' => 'post']) }}
     <table border="0" cellpadding="4" cellspacing="0">
       <tr>
         <td>
           <select name="partner_group_id">
             <option value="all">提携先別専用料金登録プランすべて表示</option>
            @foreach ($partner_group_list as $group_list_item)
             <option value="{{ $group_list_item->partner_group_id }}" 
             @if( $partner_group_id == $group_list_item->partner_group_id) selected @endif
             >
                {{ $group_list_item->partner_group_nm }}
            </option>
            @endforeach  
           </select>
         </td>
         <td><input type="submit" value=" 表示 " /></td>
       </tr>
     </table>
{!! Form::close() !!}
    @if (empty($total_material_list) == false)
     
       <table border="1" cellpadding="4" cellspacing="0">
         <tr>
           <td rowspan="2" bgcolor="#cccccc">No.</td>
           <td rowspan="2" bgcolor="#cccccc">施設コード</td>
           <td rowspan="2" bgcolor="#cccccc">施設名</td>
           <td rowspan="2" bgcolor="#cccccc" nowrap>都道府県</td>
           <td rowspan="2" bgcolor="#cccccc">住所</td>
           <td rowspan="2" bgcolor="#cccccc">ホテルTEL</td>
           <td rowspan="2" bgcolor="#cccccc">ホテルFAX</td>
           <td colspan="2" bgcolor="#cccccc">部屋</td>
           <td colspan="2" bgcolor="#cccccc">プラン</td>
           <td rowspan="2" bgcolor="#cccccc">ベストリザーブ料金設定有無</td>
           <td rowspan="2" bgcolor="#cccccc">自動延長対象有無</td>
         </tr>
         <tr>
           <td bgcolor="#cccccc">名称</td>
           <td bgcolor="#cccccc">コード</td>
           <td bgcolor="#cccccc">名称</td>
           <td bgcolor="#cccccc">コード</td>
         </tr>
           @foreach ($total_material_list as $item)
           <tr>
             <td bgcolor="#ffffff">{{$loop->iteration}}</td>
             <td bgcolor="#ffffff">{{$item->hotel_cd}}</td>
             <td bgcolor="#ffffff">{{$item->hotel_nm}}</td>
             <td bgcolor="#ffffff">{{$item->pref_nm}}</td>
             <td bgcolor="#ffffff">{{$item->address}}</td>
             <td bgcolor="#ffffff">{{$item->tel}}</td>
             <td bgcolor="#ffffff">{{$item->fax}}</td>
             <td bgcolor="#ffffff">{{$item->room_nm}}</td>
             <td bgcolor="#ffffff">{{$item->room_id}}</td>
             <td bgcolor="#ffffff">{{$item->plan_nm}}</td>
             <td bgcolor="#ffffff">{{$item->plan_id}}</td>
             <td bgcolor="#ffffff">{{$item->room_charge_hotel_cd}}</td>
             <td bgcolor="#ffffff">{{$item->extend_status}}</td>
           </tr>
         @endforeach
       </table>
       
    @elseif(empty($partner_group_id) == false)
    <p>提携先別専用料金登録プランは見つかりませんでした。<br>
    @endif
    <br/>

@endsection