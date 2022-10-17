{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\_form.tpl --}}

{{-- TODO: Form Facades --}}
<form action="/ctl/brpartnersite/search/" method="get">
    <p>
        {{-- TODO: セッションなどで保持してコントローラから渡す。 --}}
        @php
            $form_params = (object)[
                'customer_id' => '1',
                'customer_off' => '1',
            ];
            $customer = (object)[
                'customer_nm' => '精算先名',
            ];
        @endphp
        <table class="br-detail-list">
            @if (!empty($form_params->customer_id))
                <tr>
                    <th>精算先</th>
                    <td>
                        {{ $customer->customer_nm }}（{{ $form_params->customer_id }}）
                        <br />
                        <input type="checkbox" name="customer_off" value="1" {{ $form_params->customer_off ? 'checked="checked"' : '' }} /> 精算先を検索条件から外す
                        <input type="hidden" name="customer_id" value="{{ strip_tags($form_params->customer_id) }}" />
                    </td>
                </tr>
            @endif
            <tr>
                <th>キーワード</th>
                <td>
                    {{-- TODO: $keyword、 null 合体演算子を外す --}}
                    <input type="text" name="keywords" size="50" maxlength="20" value="{{ strip_tags($keywords ?? '') }}" />
                    <br /><a href="" onclick="helpForm(); return false;">キーワードのヘルプ</a>
                </td>
            </tr>
        </table>
    </p>
    <p>
        <input type="submit" value="　検索　" />
    </p>
</form>
{{-- キーワード検索のヘルプ --}}
@include('ctl.brPartnerSite._form_help')
    
    