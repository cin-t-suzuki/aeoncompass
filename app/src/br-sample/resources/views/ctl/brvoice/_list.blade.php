<table width="95%" border="1" cellpadding="5" cellspacing="0">
  <tbody>
    {{-- 渡された変数名とasの変数名がかぶるためエラーになるので変更 --}}
    @foreach ($voice_data['values'] as $voice)
    @php
      $total_page = $voice->total_page ?? null; //null追記でいいか
      $total_count = $voice->total_count ?? null;
    @endphp
    <tr @if ($voice->voice_status == 1)bgcolor="#cccccc" @elseif ($voice->voice_status == 2)bgcolor="#999999"@endif>
      <td valign="top" width="50%">
        <font color="#006633">
          <strong>{{strip_tags($voice->hotel_nm)}}</strong>
          {{-- 実装後に以下リンク修正 --}}
          （<a href="http://{$v->config->system->rsv_host_name}/hotel/{$voice.hotel_cd}/">{{$voice->hotel_cd}}</a>）
          <a href="{$v->env.source_path}{$v->env.module}/htlreservemanagement/reserveinfo/reserve_cd/{$voice.reserve_cd|escape:'url'}/target_cd/{$voice.hotel_cd}/">[予約内容確認]</a>　　
          <a href="{$v->env.source_path}{$v->env.module}/brreserve/search?reserve_dtm[check]=on&reserve_dtm[after_year]=2000&reserve_dtm[after_month]=01&reserve_dtm[after_day]=01&reserve_dtm[before_year]={$smarty.now+24*60*60|date_format:'%Y'}&reserve_dtm[before_month]={$smarty.now+24*60*60|date_format:'%m'}&reserve_dtm[before_day]={$smarty.now+24*60*60|date_format:'%d'}&member_cd={$voice.member_cd|escape:'url'}#insurance_weather">[この会員の他の予約を確認]</a><br>
        </font>
        @if (!$service->is_empty($voice->title))タイトル[{{strip_tags($voice->title)}}]<br>@endif<small>投稿日[{{strip_tags($voice->experience_dtm)}}]</small>
        <br><hr size="1" width="50%" align="left">
        {{strip_tags($voice->explain)}}
      </td>
      <td valign="top" width="50%">
        {!! Form::open(['route' => ['ctl.brvoice.switch'], 'method' => 'post']) !!}
          <table width="100%">
            <tr>
              <td>
                @if (!$service->is_empty($voice->reply_dtm))
                  返答者：<font color="#0000ff">@if ($voice->reply_type == 1)運用 @else 施設@endif</font>　<small>返答日：[{{strip_tags($voice->reply_dtm)}}]</small>
                  <hr size="1" width="100%" align="left">
                @endif
              </td>
              <td align="right">
                @if ($voice->voice_status == 0)
                  <input name="i_btn" value="削除" type="submit">
                @else
                  <input name="i_btn" value="削除取消" type="submit">
                @endif
                {{-- 検索条件と同じもち回し --}}
                {{-- serachはnull追記でいいか？下も同様 --}}
                <input type="hidden" name="target_cd" value="{{strip_tags($voice->hotel_cd)}}">
                <input type="hidden" name="voice_cd" value="{{strip_tags($voice->voice_cd)}}">
                <input type="hidden" name="exp_after_dtm" value="{{strip_tags($exp_after_dtm)}}">
                <input type="hidden" name="exp_before_dtm" value="{{strip_tags($exp_before_dtm)}}">
                <input type="hidden" name="rep_after_dtm" value="{{strip_tags($rep_after_dtm)}}">
                <input type="hidden" name="rep_before_dtm" value="{{strip_tags($rep_before_dtm)}}">
                <input type="hidden" name="exp_check" value="{{strip_tags($search['exp_check'] ?? null)}}">
                <input type="hidden" name="rep_check" value="{{strip_tags($search['rep_check'] ?? null)}}">
                <input type="hidden" name="hotel_cd" value="{{strip_tags($search['hotel_cd'] ?? null)}}">
                <input type="hidden" name="keywords" value="{{strip_tags($search['keywords'] ?? null)}}">
                <input type="hidden" name="page" value="{{strip_tags($page)}}">
              </td>
            </tr>
          </table>
        {!! Form::close() !!}
        @if (!$service->is_empty($voice->reply_dtm))
          {!! Form::open(['route' => ['ctl.brvoice.update'], 'method' => 'post']) !!}
        @else
          {!! Form::open(['route' => ['ctl.brvoice.create'], 'method' => 'post']) !!}
        @endif
          {{-- 検索条件と同じもち回し ※正しpageに関しては宿泊体験に関して返答をした場合、返答日の降順になるので１ページ目固定 --}}
          <input type="hidden" name="target_cd" value="{{strip_tags($voice->hotel_cd)}}">
          <input type="hidden" name="voice_cd" value="{{strip_tags($voice->voice_cd)}}">
          <input type="hidden" name="exp_after_dtm" value="{{strip_tags($exp_after_dtm)}}">
          <input type="hidden" name="exp_before_dtm" value="{{strip_tags($exp_before_dtm)}}">
          <input type="hidden" name="rep_after_dtm" value="{{strip_tags($rep_after_dtm)}}">
          <input type="hidden" name="rep_before_dtm" value="{{strip_tags($rep_before_dtm)}}">
          <input type="hidden" name="exp_check" value="{{strip_tags($search['exp_check'] ?? null)}}">
          <input type="hidden" name="rep_check" value="{{strip_tags($search['rep_check'] ?? null)}}">
          <input type="hidden" name="hotel_cd" value="{{strip_tags($search['hotel_cd'] ?? null)}}">
          <input type="hidden" name="keywords" value="{{strip_tags($search['keywords'] ?? null)}}">
          <input type="hidden" name="page" value="1">
          <textarea name="answer" rows="5" cols="60">{{strip_tags($voice->answer)}}</textarea>
          返答者<br />
          <LABEL for="k{$smarty.foreach.voice_data.index}">
            運用 <INPUT TYPE="radio" NAME="reply_type" VALUE="1"  id="k{$smarty.foreach.voice_data.index}" @if ($voice->reply_type == 1 || $service->is_empty($voice->reply_type)) CHECKED @endif>
          </LABEL>
          <LABEL for="h{$smarty.foreach.voice_data.index}">
            施設 <INPUT TYPE="radio" NAME="reply_type" VALUE="0"  id="h{$smarty.foreach.voice_data.index}" @if ($voice->reply_type == 0 && !$service->is_empty($voice->reply_type)) CHECKED @endif>
          </label>
          <br>
          <table width="100%">
            <tr>
              <td align="right">
                <input name="i_btn" value="返答する" type="submit">
              </td>
            </tr>
          </table>
        {!! Form::close() !!}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<br>
<style type="text/css">
/* <!-- 残しでいい？削除？ */
/* <!-- */
.a {background-color:#ffffff;border:0px;color:#0000ff;text-decoration: underline; cursor: pointer;font-size:100%}
/* --> */
</style>
<br>
{{-- ページャー --}}
  @include ('ctl.brvoice._paging')
