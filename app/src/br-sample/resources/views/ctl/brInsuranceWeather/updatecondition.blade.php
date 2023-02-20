@inject('service', 'App\Http\Controllers\ctl\brInsuranceWeatherController')

{{--削除でいい？ {strip} --}}
  {{-- 提携先管理ヘッダー --}}
  @extends('ctl.common.base2')
  @section('title', 'お天気保険成立者設定更新結果')

  {{--  css  --}}
  @section('headScript')
    @include('ctl.brInsuranceWeather._css')
  @endsection

  @section('content')

  {{-- メッセージbladeの読込 --}}
  @include('ctl.common.message')

    <hr class="contents-margin" />

    <table class="br-detail-list">
    <tr>
      <th>お天気保証番号</th>
      <th>保険金</th>
      <th>宿泊代金</th>
      <th>予約参照コード</th>
      <th>宿泊日</th>
      <th>加入状態</th>
      <th>成立状態</th>
      <th>処理状態</th>
      <th>予約状態</th>
      <th>結果内容</th>
    </tr>
    @foreach ($result as $result)
    <tr>
        <td>20{{sprintf('%08d',$result['jbr_no'])}}</td>
        {{-- ↑元ソースには即しているが、実運用的にこれでいいのか？ --}}
        <td class="charge">{{number_format($result['valid_charge']??null)}}</td>
        {{-- ↑??null追記 --}}
        <td class="charge">{{number_format($result['present_charge'])}}</td>
        {{-- ↑宿泊代金＝present_chargeでいいのか？？（データ登録の方ができたら要確認？？） --}}
        <td>{{$result['reserve_cd']}}</td>
        <td>@include ('ctl.common._date',['timestamp' => $result['date_ymd'] , 'format' => 'y-m-d'])</td>
        <td>@if     ($result['status'] == 0)適用外
            @elseif ($result['status'] == 1)加入
            @endif
        </td>
        <td>@if     ($result['condition'] == 0)未判定
            @elseif ($result['condition'] == 1)成立
            @elseif ($result['condition'] == 2)不成立
            @endif
        </td>
        <td>@if     ($result['status'] == 0)           判定不用
            @elseif ($service->is_empty($result['action_condition']))結果待ち
            @elseif ($result['action_condition'] ==  0)判定不用
            @elseif ($result['action_condition'] == 10)成立反映待ち
            @elseif ($result['action_condition'] == 11)成立メール送信待ち
            @elseif ($result['action_condition'] == 12)口座登録待ち
            @elseif ($result['action_condition'] == 13)全銀データ出力待ち
            @elseif ($result['action_condition'] == 14)全銀データ出力済み
            @elseif ($result['action_condition'] == 20)不成立反映待ち
            @elseif ($result['action_condition'] == 21)不成立反映済み
            @endif
        </td>
        <td>@if ($result['reserve_status'] == 0) 予約状態
            @else 予約キャンセル状態
            @endif
        </td>
        <td>
            @if ($result['error'] == 'NotJbrNo') <span style="color:red;">お天気保証番号に誤りがあります。</span>
            @elseif ($result['error'] == 'Canceled') <span style="color:red;">キャンセルされており対象外です。</span>
            @elseif ($result['error'] == 'NotCharge')<span style="color:red;">保険金に誤りがあります。</span>
            @elseif ($result['error'] == 'NotValid') <span style="color:red;">適用外（キャンセルなど）です。</span>
            @elseif ($result['condition'] == 0 and $result['action_condition'] == 10)更新完了
            @endif
        </td>
    </tr>
    @endforeach
    </table>

    <hr class="contents-margin" />
    {!! Form::open(['route' => ['ctl.brInsuranceWeather.index'], 'method' => 'get']) !!}
    <input type="hidden" name="jbr_no" value="{{$jbr_no}}" />
    <input type="submit" value="お天気保険成立者設定画面に戻る" />
    {!! Form::close() !!}
    <hr class="contents-margin" />

    @endsection

{{--削除でいい？ {/strip} --}}