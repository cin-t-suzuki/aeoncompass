{{-- 改ページ処理  --}}
  @if ($total_page > 0)
    @if (1 < $views->page)
      @if ($total_page > 10)
        <a href="?{{$service->toQueryCorrect('page,size', false)}}">最初</a>&nbsp;&nbsp;
      @endif
      <a href="?page={{$views->page-1}}&{{$service->toQueryCorrect('page,size', false)}}" >前へ</a>&nbsp;&nbsp;
    @endif

    @if ($views->page < 5)
      {{-- {section name=page_list start=1 max=10 loop=$total_page+1 step=1} --}}
      @for ($page_list = 1; ($page_list < $total_page+1) && ($page_list <= 10); $page_list++)
      {{-- $page_list→でいいか（ページ全体的に同様） --}}
        @if ($views->page == $page_list)
          <font color="#ff0000">{{$page_list}}</font>&nbsp;&nbsp;
        @else
          <a href="?page={{$page_list}}&{{$service->toQueryCorrect('page,size', false)}}">{{$page_list}}</a>&nbsp;&nbsp;
        @endif
      @endfor
    @else
      {{-- {assign var="start" value=$views->page-4}
      {assign var="max" value=$views->page+5} --}}
      @php
      $start = $views->page-4;
      $max = $views->page+5;
      @endphp
      @if ($total_page < $max)
        {{-- {math equation=x-(y-z) x=$start y=$max z=$total_page assign=start} --}}
        @php $start = $start-($max-$total_page) @endphp
        @if ($start < 1)@php $start = 1 @endphp @endif
      @endif
      {{-- {section name=page_list start=$start max=$max loop=$max+1 step=1} --}}
      @for ($page_list = $start; ($page_list < $max+1) && ($page_list <= $max); $page_list++)
        @if ($page_list <= $total_page)
          @if ($views->page == $page_list)
            <font color="#ff0000">{{$page_list}}</font>&nbsp;&nbsp;
          @else
            <a href="?page={{$page_list}}&{{$service->toQueryCorrect('page,size', false)}}">{{$page_list}}</a>&nbsp;&nbsp;
          @endif
        @endif
      @endfor
    @endif

    @if ($total_page > $views->page)
      <a href="?page={{$views->page+1}}&{{$service->toQueryCorrect('page,size', false)}}">次へ</a>&nbsp;&nbsp;
      @if ($total_page > 10)
        <a href="?page={{$total_page}}&{{$service->toQueryCorrect('page,size', false)}}">最後</a>
      @endif
    @endif
    <br>
    総数{{number_format($total_count)}}件　全{{number_format($total_page)}}ページ
  @endif
{{-- 改ページ処理 --}}