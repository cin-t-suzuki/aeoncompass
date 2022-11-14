{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\_common\_change_acceptance.tpl --}}

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <td align="center">
            <table border="0" cellspacing="0" cellpadding="2">
                {{-- TODO: Form Facades --}}
                {{-- TODO: create route  --}}
                <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htlacceptance/update/" method="post">
                    @if ($v->user->hotel->accept_status == 1)
                        <tr>
                            <td align="center">
                                <small>
                                    <font color="#0000ff">予約受付中</font>
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <input type="submit" value="停止中にする">
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td align="center">
                                <input value="受付中にする" type="submit">
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <small>
                                    <font color="#ff0000">予約受付停止中</font>
                                </small>
                            </td>
                        </tr>
                    @endif
                    {{-- hidden生成処理 --}}
                    @foreach (Request::input() as $key => $value)
                        @if ($key != 'error_handler' && $key != 'module' && $key != 'controller' && $key != 'action')
                            @if (is_array($value))
                                {{-- 配列のhidden値生成 --}}
                                @foreach ($value as $key2 => $value2)
                                    <input type="hidden" name="{{ $key }}[{{ $key2 }}]" value="{{ strip_tags($value2) }}" />
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ strip_tags($value) }}" />
                            @endif
                        @endif
                    @endforeach
                    <input type="hidden" name="base_controller" value="{{ strip_tags($v->env->controller) }}" />
                    <input type="hidden" name="base_action" value="{{ strip_tags($v->env->action) }}" />
                    <input type="hidden" name="hotel[accept_status]" value="{{ $v->user->hotel->accept_status == 0 ? '1' : '0' }}" />
                </form>
            </table>
        </td>
    </tr>
</table>
