 {{-- $pgc1_steps_current:現在のステップ数  --}}
<div class="pgc1-steps advance">
  {{--書き替えあっているか？ <table class="pgc1-steps-{$v->helper->store->gets('step')|@count}" border="0" cellpadding="0" cellspacing="0"> --}}
  <table class="pgc1-steps-{{count([$step])}}" border="0" cellpadding="0" cellspacing="0">
    <tr>
{{--書き替えあっているか？ @foreach (from=$v->helper->store->gets('step') item=text name=text) --}}
{{-- TODO：CSSは未調整（本来入っているはずのwidth指定が適用されていない、←自体がレスポンス未対応） --}}
@foreach ($step as $text)
@if ($pgc1_steps_current-1 == $loop->iteration-1 && 1 < $loop->iteration)
      <td><div class="step-nc"></div></td>
@elseif ($pgc1_steps_current+1 == $loop->iteration && 1 < $loop->iteration)
      <td><div class="step-cn"></div></td>
@elseif (1 < $loop->iteration)
      <td><div class="step-nn"></div></td>
@endif
@if ($pgc1_steps_current == $loop->iteration)
      <td><div class="step{{$loop->iteration}}-c">{{$text}}</div></td>
@else
      <td><div class="step{{$loop->iteration}}">{{$text}}</div></td>
@endif
@endforeach
    </tr>
  </table>
</div>
