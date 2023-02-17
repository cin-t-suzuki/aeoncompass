{{--削除でいい？ {strip} --}}

  {{-- 提携先管理ヘッダー --}}
  @extends('ctl.common.base2')
  @section('title', 'お天気保険成立者設定')

  {{--  css  --}}
  @section('headScript')
    @include('ctl.brInsuranceWeather._css')
  @endsection

  @section('content')

    <hr class="contents-margin" />

    <div style="text-align:left;">
  {!! Form::open(['route' => ['ctl.brInsuranceWeather.updatecondition'], 'method' => 'post']) !!}
  成立分の 「お天気保証番号」 と 「保険金」 を スペース区切りで入力してください。
    <table class="br-detail-list">
      <tr>
        <th>お天気保証番号 保険金</th>
      </tr>
      <tr>
        <td><textarea cols="40" rows="10" name="jbr_no" wrap="off">{{$jbr_no}}</textarea></td>
      </tr>
      <tr>
        <td>
入力例<br />
<br />
2000002702 7000<br />
2000002672 9300<br />
<br />
</td>
      </tr>
      <tr>
        <th style="text-align:center;"><input type="submit" value=" 更新 " /></th>
      </tr>
    </table>
  {!! Form::close() !!}

    <hr class="contents-margin" />

  @endsection
{{--削除でいい？ {/strip} --}}