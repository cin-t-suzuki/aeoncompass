{{-- (4か所)zap_is_empty→is_emptyでいいか？0の扱いが違うらしいが、元ソースでは使用しないになっている --}}
{!! Form::open(['route' => ['ctl.brMailBuffer.search'], 'method' => 'get']) !!}
  <table border="1" cellpadding="3" cellspacing="0">

    <tr>
      <td bgcolor="#EEFFEE">タイトル</td>
      <td>
        <select name="Search[subject]" size="1">
          <option value="" @if ($service->is_empty($search['subject'] ?? null))@endif>全て</option>
          @foreach ($send_mail_queue_subjects['values'] as $send_mail_queue_subject)
            <option value="{{strip_tags($send_mail_queue_subject->subject)}}" @if (!$service->is_empty($search['subject'] ?? null) && (($search['subject'] ?? null) == strip_tags($send_mail_queue_subject->subject))) selected @endif>{{$send_mail_queue_subject->subject}}</option>
          @endforeach
        </select>
      </td>
    </tr>

    <tr>
      <td bgcolor="#EEFFEE">送信日</td>
      <td nowrap="nowrap">
        {{-- 現在時間をセット --}}
        @php $date = time(); @endphp
        <select name="Search[send_dtm_after]" size="1">
        @for ($send_dtm_after = 0; $send_dtm_after < 7; $send_dtm_after++)
          <option value="{{date('Y-m-d', $date)}}"
          @if ((($search['send_dtm_after'] ?? null) == date('Y-m-d', $date))
          || ($service->is_empty($search['send_dtm_after'] ?? null) && (date('Y-m-d', $date) == date('Y-m-d'))))
            selected
          @endif>
            {{date('Y年m月d日', $date)}}
          </option>
          @php $date = strtotime("-1 day", $date);@endphp
        @endfor
        </select>
      ～
        {{-- 現在時間をセット --}}
        @php $date = time(); @endphp
        <select name="Search[send_dtm_before]" size="1">
        @for ($send_dtm_before = 0; $send_dtm_before < 7; $send_dtm_before++)
          <option value="{{date('Y-m-d', $date)}}"
          @if ((($search['send_dtm_before'] ?? null) == date('Y-m-d', $date))
          || ($service->is_empty($search['send_dtm_before'] ?? null) && (date('Y-m-d', $date) == date('Y-m-d'))))
            selected
          @endif>
          {{date('Y年m月d日', $date)}}
          </option>
          @php $date = strtotime("-1 day", $date);@endphp
          @endfor
        </select>（ 過去 ７日分 のみ検索可能）
      </td>
    </tr>

    <tr>
      <td bgcolor="#EEFFEE">Ｅ－Ｍａｉｌ</td>
      <td nowrap="nowrap">
        <input name="Search[to_mail]" size="30" type="text" value="{{strip_tags($search['to_mail'] ?? null)}}"> ※完全一致
      </td>
    </tr>

  </table>
<input value="検索" type="submit">
{!! Form::close() !!}