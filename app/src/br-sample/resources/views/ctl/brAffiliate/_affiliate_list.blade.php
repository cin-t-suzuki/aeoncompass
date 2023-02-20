{{-- 各リストの表示 --}}

    @if (now() < $affiliate->accept_e_dtm || $service->is_empty($affiliate->accept_e_dtm))
      @php $bgcolor = 'bgcolor="#ffffff"'; @endphp
    @else
      @php $bgcolor = 'bgcolor="#eeeeee"'; @endphp
    @endif

    <td nowrap {{$bgcolor}}>
      <small>
      {{strip_tags($affiliate->affiliate_cd)}} {{-- アフィリエイトコード --}}
      </small><br>
      {{strip_tags($affiliate->program_nm)}}{{-- プログラム名称  --}}
    </td>
    <td align="center" {$bgcolor}>
      @if ($affiliate->reserve_system != "")
        {{strip_tags($affiliate->reserve_system)}}{{-- アフィリエイト予約システム --}}
      @else
        <br>
      @endif
    </td>
    <td nowrap {{$bgcolor}}>
      @if ($affiliate->overwrite_status != "")
        @if ($service->is_empty($affiliate->limit_cookie))
          セッション単位
        @else
          有効期限：{{$affiliate->limit_cookie}}{{-- COOKIE有効期限 --}}日
        @endif<br />
        @if ($affiliate->overwrite_status == 1){{-- COOKIE上書き可否 --}}
          上書き：○
        @elseif ($affiliate->overwrite_status == 0)
          上書き：×
        @endif
      @endif<br />
    </td>
    <td nowrap {{$bgcolor}}>
      {{-- サービス開始日時 --}}
       @if ($affiliate->accept_s_dtm != "")
        {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$affiliate.accept_s_dtm format='ymd'}～<br>
      @else
        <br>
      @endif
      @if ($affiliate->accept_e_dtm != "")
        {include file=$v->env.module_root|cat:'/views/_common/_date.tpl' timestamp=$affiliate.accept_e_dtm format='ymd'}
      @endif
    </td>
    <td nowrap {{$bgcolor}}>
      @if ($affiliate->reserve_system == "reserve")
        U : <a target="blank" href="http://{$v->config->system->rsv_host_name}/ac/{$affiliate.affiliate_cd}/">http://{$v->config->system->rsv_host_name}/ac/{$affiliate.affiliate_cd}/</a><br>
      @elseif ($affiliate->reserve_system == "biztrip")
        U : 利用不可<br>
      @endif
      @if ($affiliate->reserve_system != "")
        R : <a target="blank" href=""{{$affiliate->redirect}}"">{{$affiliate->redirect}}</a>
      @else
        <br>
      @endif
    </td>