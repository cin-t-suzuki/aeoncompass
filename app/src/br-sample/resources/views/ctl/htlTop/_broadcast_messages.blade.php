<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td  bgcolor="#EEEEFF"  align="center">
      <strong>
      <big>
      <font color="#3333ff">
      <a name="infomation">
        お知らせ
      </a>
      </font>
      </big>
      </strong>
    </td>
  </tr>
  {{-- ??[]追記でいいか --}}
  @foreach ($views->broadcast_messages['values'] ?? [] as $key => $value)
  {{-- null追記でいいか --}}
  @if (!($views->is_disp_rate_info ?? null))
    {{-- ??[]追記でいいか --}}
  @if (!in_array($value->id,$views->disable_disp_broadcast_id ??[]))
  @if ($value->accept_s_dtm < now() && now() < $value->accept_e_dtm)
  <tr>
    <td>
      {{-- {$v->helper->form->strip_tags($value.title, '<font>', false)}
        <pre>{$v->helper->form->strip_tags($value.description, '<br><div><font><img><li><small><span><strong><ul><a>', false)}</pre> --}}
      {{-- false削除していいか＆タグ出力のため{!! !!}で記述変更でOK？ --}}
        {!! strip_tags($value->title, '<font>') !!}
        <pre>{!! strip_tags($value->description, '<br><div><font><img><li><small><span><strong><ul><a>') !!}</pre>
    </td>
  </tr>
  @endif
  @endif
  @else
  @if (!in_array($value['id'],$views->disable_disp_broadcast_id))
  @if ($value->accept_s_dtm < now() && now() < $value->accept_e_dtm)
  <tr>
    <td>
        {{strip_tags($value['title'], '<font>', false)}}
        <pre>{{strip_tags($value['description'], '<br><div><font><img><li><small><span><strong><ul><a>', false)}}</pre>
    </td>
  </tr>
  @endif
  @endif
  @endif
  @endforeach
  </table>