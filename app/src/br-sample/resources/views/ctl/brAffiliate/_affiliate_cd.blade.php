<form method="post" action="{$v->env.source_path}{$v->env.module}/braffiliate/details/">
  <td rowspan="{{$row_cnt}}">
    <input type="submit" value="詳細">
    <input type="hidden" name="affiliater_cd" value={{strip_tags($affiliate->affiliater_cd)}} />
  </td>
</form>
<td WIDTH=110 rowspan="{{$row_cnt}}">
  <small>
  {{strip_tags($affiliate->affiliater_cd)}}{{-- アフィリエイターコード --}}
  </small><br />
  {{strip_tags($affiliate->affiliater_nm)}}{{-- アフィリエイター名称  --}}
</td>