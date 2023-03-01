{!! Form::open(['route' => ['ctl.brAffiliate.details'], 'method' => 'get']) !!}
  <td rowspan="{{$row_cnt}}">
    <input type="submit" value="詳細">
    <input type="hidden" name="affiliater_cd" value={{strip_tags($affiliate->affiliater_cd)}} />
  </td>
{!! Form::close() !!}
<td WIDTH=110 rowspan="{{$row_cnt}}">
  <small>
  {{strip_tags($affiliate->affiliater_cd)}}{{-- アフィリエイターコード --}}
  </small><br />
  {{strip_tags($affiliate->affiliater_nm)}}{{-- アフィリエイター名称  --}}
</td>