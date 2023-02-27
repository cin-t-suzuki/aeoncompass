{{-- MEMO: 移植元 public\app\rsv\view2\top\_link_text.tpl --}}

@if ($top_attention['display_status'] == 2)
    @foreach ($top_attention['display_attention'] as $keys => $item)
        <li class="f">
            <a href="{{ $item->url }}" title="{{ $item->word }}" alt="">
                {{ $item->word }}
            </a>
        </li>
    @endforeach
@else
    <table class="f" class="f" border="0" align="center" height="">
        @foreach ($top_attention['display_attention'] as $keys => $item)
            @if ($item->order_no % 2 != 0)
                <tr>
                    <td nowrap>
                        <a class="attention_link" data-jwest_word="{{ $item->jwest_word }}" data-jwest_url="{{ $item->jwest_url }}" href="{{ $item->url }}" title="{{ $item->word }}" alt="">
                            {{ $item->word }}
                        </a>
                    </td>
                @else
                    <td nowrap>
                        <a class="attention_link" data-jwest_word="{{ $item->jwest_word }}" data-jwest_url="{{ $item->jwest_url }}" href="{{ $item->url }}" title="{{ $item->word }}" alt="">
                            {{ $item->word }}
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        if ($.cookies.get('CP') == '1169008784') {
            $.each($(".attention_link"), function(i, val) {
                $(val).text($(val).data("jwest_word"));
                $(val).attr('href', $(val).data("jwest_url"));
            });
        }
    });
</script>
