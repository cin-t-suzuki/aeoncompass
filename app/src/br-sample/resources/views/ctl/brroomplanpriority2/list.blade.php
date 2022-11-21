@section('title', '重点表示プランの登録')
@include('ctl.common.base')

{{-- 追記 --}}
@inject('service', 'App\Http\Controllers\ctl\BrtopController')

  <br>
  {!! Form::open(['route' => ['ctl.brroomplanpriority2.list'], 'method' => 'post']) !!}
    <table border="1" cellpadding="4" cellspacing="0">
      <tr>
        <td nowrap bgcolor="#EEFFEE">都道府県</td>
        <td>
          <select size="1" name="priority[pref_id]">
            @foreach ($views->mast_pref['values'] as $value)
            {{-- null追記,要書き換え --}}
            <option value="{{strip_tags($value['pref_id'])}}" @if ($value['pref_id'] == $views->select_pref['pref_id']) selected="selected"@endif @if (($value['pref_id'] == 13) && $service->is_empty($views->select_pref['pref_id']))selected="selected"@endif>{{strip_tags($value['pref_nm'])}}</option>
              {{-- <option value="{$v->helper->form->strip_tags($value.pref_id)}" {if $value.pref_id == $v->assign->select_pref.pref_id} selected="selected"{/if} {if ($value.pref_id == 13) && is_empty($v->assign->select_pref.pref_id)}selected="selected"{/if}>{$v->helper->form->strip_tags($value.pref_nm)}</option> --}}
            @endforeach
          </select>
        </td>
      </tr>
      <tr>
        <td nowrap bgcolor="#EEFFEE">宿泊対象期間</td>
        <td>
          <input type="radio" name="priority[span]" value="0" @if (($views->priority_cd['span']??null) == 0) checked @endif id="span_0" /><label for="span_0">検索日から 0 - 6 日後</label><br>
          <input type="radio" name="priority[span]" value="7" @if (($views->priority_cd['span']??null) == 7) checked @endif id="span_7" /><label for="span_7">検索日から 7 - 35 日後</label>
        </td>
      </tr>
      <tr>
        <td nowrap bgcolor="#EEFFEE">　</td>
        <td><input type="submit" value=" 検索する ">
          <input type="hidden" name="priority[pran_list]" value="true" />
        </td>
      </tr>
    </table>
  {!! Form::close() !!}
  {{-- 一覧・入力画面表示 --}}
  {{-- @if (!is_empty($views->priority_cd)) --}}
  @if (!empty($views->priority_cd))
    @include ('ctl.brroomplanpriority2._priority_list')
  @endif

  @section('title', 'footer')
  @include('ctl.common.footer')