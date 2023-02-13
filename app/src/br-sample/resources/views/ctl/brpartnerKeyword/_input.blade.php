{{-- 引数：                                             --}}
{{--  form_params             リクエストパラメータ      --}}
{{--  display_status_selecter 表示状態のラジオボタン項目--}}
{{----}}
{{-- {strip} 削除していいか？--}}
  <p class="item-name">キーワード（ここに入れた文字が画面に表示されます。）</p>
  <input type="text" name="word" value="{{$form_params['word']??null}}" size="50" />
  {{-- ↑??nullでいいか、下も --}}
  <hr class="item-margin" />
  <p class="item-name">検索値（キーワードと異なる内容で検索したい場合に入力します。）</p>
  <input type="text" name="value" value="{{$form_params['value']??null}}" size="50" />
  <hr class="item-margin" />
<!--   <p class="item-name">表示No.</p>
  <input type="text" name="order_no" value="{$form_params.order_no}" size="10" /> -->
  <p class="item-name">表示状態</p>
  {{-- {html_radios
    name="display_status"
    values=$display_status_selecter.values
    output=$display_status_selecter.names
    selected=$form_params.display_status
  } --}}
  {{-- ↑ラジオ部分書き換え合っているか？$display_status_selecter.namesだけ使う方式にしている --}}
  @foreach ($display_status_selecter['names'] as $key => $value)
  <label>
    <input type="radio" name="display_status" value="{{$key}}" @if ($key == $form_params['display_status']) checked @endif />
    {{$value}}
  </label>
  @endforeach


  <hr class="item-margin" />
{{-- {/strip} 削除していいか？--}}