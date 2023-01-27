{{-- 改ページ処理 --}}
  @if ($total_page > 0)
    @if (1 < $page)
      <a href="/ctl/brvoice/search/?target_cd={{strip_tags($target_cd)}}&exp_after_dtm={{strip_tags($exp_after_dtm)}}&exp_before_dtm={{strip_tags($exp_before_dtm)}}&rep_after_dtm={{strip_tags($rep_after_dtm)}}&rep_before_dtm={{strip_tags($rep_before_dtm)}}&exp_check={{strip_tags($search['exp_check'] ?? null)}}&rep_check={{strip_tags($search['rep_check'] ?? null)}}&hotel_cd={{strip_tags($search['hotel_cd'])}}&keywords={{strip_tags($search['keywords'])}}&page={{strip_tags($page)-1}}" >
        前へ</a>&nbsp;&nbsp;
    @endif
    @if ($page < 5)
      {{--$total_page+1はなぜ+1？？無駄なページが出るので$total_pageのみで実装 {section name=page_list start=1 max=10 loop=$total_page+1 step=1} --}}
      @for ($page_list = 1; ($page_list <= 10) && ($page_list <= $total_page); $page_list++)
        @if ($page == $page_list)
          <font color="#ff0000">{{$page_list}}</font>&nbsp;&nbsp;
        @else
          <a href="/ctl/brvoice/search/?target_cd={{strip_tags($target_cd)}}&exp_after_dtm={{strip_tags($exp_after_dtm)}}&exp_before_dtm={{strip_tags($exp_before_dtm)}}&rep_after_dtm={{strip_tags($rep_after_dtm)}}&rep_before_dtm={{strip_tags($rep_before_dtm)}}&exp_check={{strip_tags($search['exp_check'] ?? null)}}&rep_check={{strip_tags($search['rep_check'] ?? null)}}&hotel_cd={{strip_tags($search['hotel_cd'])}}&keywords={{strip_tags($search['keywords'])}}&page={{$page_list}}" >
           {{$page_list}}</a>&nbsp;&nbsp;
        @endif
      @endfor
    @else
      @php
       $start = $page-4;
       $max = $page+5;
      @endphp
      {{--書き換えあっているか？ {section name=page_list start=$start max=$max loop=$max+1 step=1} --}}
      @for ($page_list = $start; ($page_list <= $max) && ($page_list <= $max+1); $page_list++)
        @if ($page_list <= $total_page)
          @if ($page == $page_list)
            <font color="#ff0000">{{$page_list}}</font>&nbsp;&nbsp;
          @else
          <a href="/ctl/brvoice/search/?target_cd={{strip_tags($target_cd)}}&exp_after_dtm={{strip_tags($exp_after_dtm)}}&exp_before_dtm={{strip_tags($exp_before_dtm)}}&rep_after_dtm={{strip_tags($rep_after_dtm)}}&rep_before_dtm={{strip_tags($rep_before_dtm)}}&exp_check={{strip_tags($search['exp_check'] ?? null)}}&rep_check={{strip_tags($search['rep_check'] ?? null)}}&hotel_cd={{strip_tags($search['hotel_cd'])}}&keywords={{strip_tags($search['keywords'])}}&page={{$page_list}}" >
           {{$page_list}}</a>&nbsp;&nbsp;
          @endif
        @endif
      @endfor
    @endif&nbsp;&nbsp;
    @if ($total_page > $page)
      <a href="/ctl/brvoice/search/?target_cd={{strip_tags($target_cd)}}&exp_after_dtm={{strip_tags($exp_after_dtm)}}&exp_before_dtm={{strip_tags($exp_before_dtm)}}&rep_after_dtm={{strip_tags($rep_after_dtm)}}&rep_before_dtm={{strip_tags($rep_before_dtm)}}&exp_check={{strip_tags($search['exp_check'] ?? null)}}&rep_check={{strip_tags($search['rep_check'] ?? null)}}&hotel_cd={{strip_tags($search['hotel_cd'])}}&keywords={{strip_tags($search['keywords'])}}&page={{strip_tags($page)+1}}" >
        次へ
      </a>
    @endif
    <br>
    総泊数{{$total_count}}泊　全{{$total_page}}ページ
  @endif
{{-- 改ページ処理 --}}