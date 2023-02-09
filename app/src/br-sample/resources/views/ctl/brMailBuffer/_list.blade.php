<table border="1" cellpadding="3" cellspacing="0">
  <tr bgcolor="#EEFFEE">
    <td><br></td>
    <td>タイトル</td>
    <td>送信日</td>
  </tr>
  @foreach ($send_mail_queues['values'] as $send_mail_queue)
  <tr>
    <td>
    {!! Form::open(['route' => ['ctl.brMailBuffer.show'], 'method' => 'get']) !!}
      <input name="mail_cd" value="{{$send_mail_queue->mail_cd}}" type="hidden">
      <input name="send_dtm" value="{{$send_mail_queue->send_dtm}}" type="hidden">
      <input value="詳細" type="submit">
    {!! Form::close() !!}
    </td>
    <td>{{strip_tags($send_mail_queue->subject)}}<br /></td>
    <td>{{date('Y-m-d H:i:s', strtotime($send_mail_queue->send_dtm))}}<br /></td>
  </tr>
  @endforeach
</table>
