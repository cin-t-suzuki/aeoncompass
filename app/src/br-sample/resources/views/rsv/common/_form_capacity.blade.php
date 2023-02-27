{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_capacity.tpl --}}

@php
    // MEMO: 移植元ソースで値がセットされていない変数
    $s_disabled = '';
@endphp

<div name="panel-{{ $form_capacity_nm }}" style="display:none;position:absolute;background:#fff;width:500px;border:1px solid #ccc;">
    <div style="margin:12px;@if ($form_capacity_nm == 'guests-query') background: none repeat scroll 0% 0% #F9F6E; @endif ">
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>
                    <div style="text-align:center;@if ($form_capacity_nm == 'guests-query') background: none repeat scroll 0 0 #575347;color: #FFFFFF;margin-right: 0.5em;padding: 0.25em 0.5em; @endif ">
                        小学生
                    </div>
                </th>
                <th colspan="4">
                    <div style="text-align:center;@if ($form_capacity_nm == 'guests-query') background: none repeat scroll 0 0 #575347;color: #FFFFFF;margin-right: 0.5em;padding: 0.25em 0.5em; @endif ">
                        幼児
                    </div>
                </th>
            </tr>
            <tr>
                <td class="text-center" style="width:100px;">
                    <div style="height:32px;">大人並の食事・<br />布団あり</div>
                    @if (is_null($search_condition['form']['children']['child1_capacities']))
                        {{-- MEMO: もとは is_empty --}}
                        受け入れなし
                    @else
                        <select class="text-right" name="child1" size="1" {{ $s_disabled }}>
                            @foreach ($search_condition['form']['children']['child1_capacities'] as $child)
                                <option value="{{ $child['capacity'] > 0 ? $child['capacity'] : '' }}" {{ $child['current_status'] ? 'selected' : '' }}>
                                    {{ $child['capacity'] }}名
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td class="text-center" style="width:100px;">
                    <div style="height:32px;">子供食事・<br />布団あり</div>
                    @if (is_null($search_condition['form']['children']['child2_capacities']))
                        {{-- MEMO: もとは is_empty --}}
                        受け入れなし
                    @else
                        <select class="text-right" name="child2" size="1" {{ $s_disabled }}>
                            @foreach ($search_condition['form']['children']['child2_capacities'] as $child)
                                <option value="{{ $child['capacity'] > 0 ? $child['capacity'] : '' }}" {{ $child['current_status'] ? 'selected' : '' }}>
                                    {{ $child['capacity'] }}名
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td class="text-center" style="width:100px;">
                    <div style="height:32px;">子供食事あり</div>
                    @if (is_null($search_condition['form']['children']['child4_capacities']))
                        {{-- MEMO: もとは is_empty --}}
                        受け入れなし
                    @else
                        <select class="text-right" name="child4" size="1" {{ $s_disabled }}>
                            @foreach ($search_condition['form']['children']['child4_capacities'] as $child)
                                <option value="{{ $child['capacity'] > 0 ? $child['capacity'] : '' }}" {{ $child['current_status'] ? 'selected' : '' }}>
                                    {{ $child['capacity'] }}名
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td class="text-center" style="width:100px;">
                    <div style="height:32px;">布団あり</div>
                    @if (is_null($search_condition['form']['children']['child3_capacities']))
                        {{-- MEMO: もとは is_empty --}}
                        受け入れなし
                    @else
                        <select class="text-right" name="child3" size="1" {{ $s_disabled }}>
                            @foreach ($search_condition['form']['children']['child3_capacities'] as $child)
                                <option value="{{ $child['capacity'] > 0 ? $child['capacity'] : '' }}" {{ $child['current_status'] ? 'selected' : '' }}>
                                    {{ $child['capacity'] }}名
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td class="text-center" style="width:100px;">
                    <div style="height:32px;">子供食事なし・<br />布団なし</div>
                    @if (is_null($search_condition['form']['children']['child5_capacities']))
                        {{-- MEMO: もとは is_empty --}}
                        受け入れなし
                    @else
                        <select class="text-right" name="child5" size="1" {{ $s_disabled }}>
                            @foreach ($search_condition['form']['children']['child5_capacities'] as $child)
                                <option value="{{ $child['capacity'] > 0 ? $child['capacity'] : '' }}" {{ $child['current_status'] ? 'selected' : '' }}>
                                    {{ $child['capacity'] }}名
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
            </tr>
        </table>
        <div class="text-center" style="margin:8px 0 0;">
            <a class="panelsw" name="{{ $form_capacity_nm }}" href="">×閉じる</a>
        </div>
    </div>
</div>
