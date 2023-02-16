{{-- 引数：$pager     ※ページャーオブジェクト             --}}
{{-- 引数：$params    ※リンクパラメータ                   --}}
{{-- 引数：$page_name ※ページ番号パラメータの名称         --}}
{{----------------------------------------------------------------------------------}}
{{-- ページ番号パラメータの名称設定 --}}
@if ($service->is_empty($page_name ?? null))
  @php $page_nm = 'page' @endphp
@else
  @php $page_nm = $page_name @endphp
@endif
{{-- /ページ番号パラメータの名称設定 --}}
{{-- Getパラメータ作成 --}}
@php $get_params = '' @endphp
{{-- {foreach from=$params item=value key=key}
  {assign var=get_params value=$get_params|cat:'&amp;'|cat:$key|cat:'='|cat:$value}
{/foreach} --}}
@foreach ($params as $key => $value)
  @php $get_params = $get_params . '&' . $key . '=' . $value @endphp
@endforeach
{{-- /Getパラメータ作成 --}}
{{----------------------------------------------------------------------------------}}
{{-- ページャー --}}
@if ($pager->lastPage() > 1)
  <ul class="pager">
    <li>
      {{-- 最初のページへのリンク --}}
      @if (!$service->is_empty($pager->previousPageUrl()))
        <a href="{{$pager->url(1)}}{{$get_params}}">&lt;&lt;</a>
      @else
        &lt;&lt;
      @endif
      {{-- /最初のページへのリンク --}}
    </li>
    <li>
      {{-- 前のページへのリンク --}}
      @if (!$service->is_empty($pager->previousPageUrl()))
        <a href="{{$pager->previousPageUrl()}}{{$get_params}}">PREV</a>
      @else
        PREV
      @endif
      {{-- /前のページへのリンク --}}
    </li>
    {{-- ページ番号へのリンク --}}
    {{--書き換え以下でいいか？ @foreach ($pager->pagesInRange as $page) --}}
    @php 
     $start_page = $pager->currentPage() -4;
      if ($start_page < 1) {$start_page = 1;};
     $last_page = $pager->currentPage() +5;
      if ($last_page > $pager->lastPage()) {$last_page = $pager->lastPage();};
    @endphp
    @for ($page =$start_page; $page <= $last_page; $page++)
      <li @if ($page == $pager->currentPage())id="current"@endif>
        @if ($page == $pager->currentPage())
          {{$page}}
        @else
          <a href="?{{$page_nm}}={{$page}}{{$get_params}}">{{$page}}</a>
        @endif
      </li>
    @endfor
    {{-- /ページ番号へのリンク --}}
    <li>
      {{-- 次のページへのリンク --}}
      @if (!$service->is_empty($pager->nextPageUrl()))
        <a href="{{$pager->nextPageUrl()}}{{$get_params}}">NEXT</a>
      @else
        NEXT
      @endif
      {{-- /次のページへのリンク --}}
    </li>
    <li>
      {{-- 最後のページへのリンク --}}
      @if (!$service->is_empty($pager->nextPageUrl()))
        <a href="{{$pager->url($pager->lastPage())}}{{$get_params}}">&gt;&gt;</a>
      @else
        &gt;&gt;
      @endif
      {{-- /最後のページへのリンク --}}
    </li>
  </ul>
@endif
{{-- /ページャー --}}