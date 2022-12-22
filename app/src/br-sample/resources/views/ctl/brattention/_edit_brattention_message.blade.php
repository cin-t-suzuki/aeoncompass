<table class="br-detail-list">
  <tr>
      <th style="height: 50px;">タイトル</th>
      <td colspan="2">
        <p><input type="text" name="title" value="{{$views->form_params['title']}}" style="height: 25px; width:500px;"><br>
        タイトルはTOPページには表示されません。</p>         
      </td>
  </tr>
  <tr>
    <th>掲載開始日</th>
    <td colspan="2">
        <table border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td style="border:0;">
                      <select name="start_date_year" style="height:30px;">
                        @foreach ($views->accept_header_ymd_selecter['year'] as $start_date_year)
                          <option value="{{$start_date_year}}" @if ($views->form_params['start_date_year'] == $start_date_year) selected="selected"@endif>{{$start_date_year}}</option>
                        @endforeach
                      </select>
                      年&nbsp;
                      <select name="start_date_month" style="height:30px;">
                        @foreach ($views->accept_header_ymd_selecter['month'] as $start_date_month)
                          <option value="{{$start_date_month}}" @if ($views->form_params['start_date_month'] == $start_date_month) selected="selected"@endif>{{$start_date_month}}</option>
                        @endforeach
                      </select>
                      月&nbsp;
                      <select name="start_date_day" style="height:30px;">
                        @foreach ($views->accept_header_ymd_selecter['day'] as $start_date_day)
                          <option value="{{$start_date_day}}" @if ($views->form_params['start_date_day'] == $start_date_day) selected = "selected"@endif>{{$start_date_day}}</option>
                        @endforeach
                      </select>
                      日 ～<br>
                      掲載開始日に設定した日付が来るとTOPページの注目が変更されます。
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
      </td>
    </tr>
  <tr>
      <th>表示方法選択</th>
      <td>
        <script language="javascript"  type="text/javascript">
        <!--
          //TODO ↑の<!--は削除？コメントアウト？
          //削除でいい？ {literal}
          $(document).ready(function () {
          //  初期化
            if ( $('.display:checked').val() == 4) {
              $('.set_Class_plus_2').show();
              $('.set_Class_plus_3').show();              
            } else {
              $('.set_Class_plus_2').hide();
              $('.set_Class_plus_3').hide();
            }

          $('.display').change(function () {
            if ( $('.display:checked').val() == 4) {
              $('.set_Class_plus_2').show();
              $('.set_Class_plus_3').show();
            } else {
              $('.set_Class_plus_2').hide();
              $('.set_Class_plus_3').hide();
            }
          });
          $('#btn_copy').click(function(e){
            $.each($(".word"), function(i, val) {
              console.log(i + ': ' + $(val).val());
              $('#word_'+i).val($(val).val());
            });
            $.each($(".url"), function(i, val) {
              console.log(i + ': ' + $(val).val());
              $('#url_'+i).val($(val).val());
            });
          });
         }); 
        //削除でいい？  {/literal}
        --> 
        </script>
          <input type="radio" class="display" name="display_status" value="2" @if ($views->form_params['display_status'] == "2") checked="checked"@endif>2項目<br>
          <input type="radio" class="display" name="display_status" value="4" @if ($views->form_params['display_status'] == "4") checked="checked"@endif>4項目<br>
          ※4項目を選択した際は文字数にご注意ください。(40文字推奨)
      </td>
  </tr>
  @foreach ($views->form_detail_params['start_set_array'] as $keys => $items)
  <div>
  <tr class="set_Class_plus_{$keys}">
    <th style="height: 50px;">ベストリザーブ<br>表示順位{{$items['order_no']}}</th>
    <td colspan="2">
      <p>
      ★ベストリザーブのTOPページに表示されます。<br>
      <input type="hidden" name="attention_detail_id[]" value="{{$items['attention_detail_id']}}">
      <input type="hidden" name="order_no[]" value="{{$items['order_no']}}">      
      説明<br><input type="text" name="word[]" class="word" value="{{$items['word']}}" style="height: 25px; width:500px;"><br>
      URL<br><input type="text" name="url[]" class="url" value="{{$items['url']}}" style="height: 25px; width:500px;">
      </p>
    </td>   
  </tr>
  </div>
  @endforeach
  <tr>
    <td colspan="2"><div style="text-align: center;"><input type="button" name=""  value="Jwest表示へコピー" id="btn_copy"><br>
    ↑ボタンを押すベストリザーブの内容がJ-WESTに上書きされます。</input></div></td></input></div></td>
  </tr>
  @foreach ($views->form_detail_params['start_set_array'] as $keys => $items)
  <div>
  <tr class="set_Class_plus_{{$keys}}">
    <th style="height: 50px; background-color:#d9e5f7;">J-WEST<br>表示順位{{$items['order_no']}}</th>
    <td colspan="2">
      <p>
      ★J-WEST会員の場合はこちらがTOPページに表示されます。<br>
      <input type="hidden" name="attention_detail_id[]" value="{{$items['attention_detail_id']}}">
      説明<br><input type="text" name="jwest_word[]" id="word_{{$keys}}" value="{{$items['jwest_word']}}" style="height: 25px; width:500px;"><br>
      URL<br><input type="text" name="jwest_url[]" id="url_{{$keys}}" value="{{$items['jwest_url']}}" style="height: 25px; width:500px;">
      </p>
    </td>
  </tr>
  </div>
  @endforeach
  <tr>
  <th>備考</th>
      <td><textarea name="note"cols="60" rows="6">{{$views->form_params['note']}}</textarea></td>
  </tr>
</table>
