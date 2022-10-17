{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\_form.tpl --}}
<form action="{$v->env.path_base_module}/brpartnersite/search/" method="post">
    <p>
        <table class="br-detail-list">
            {if !is_empty($v->assign->form_params.customer_id)}
                <tr>
                    <th>精算先</th>
                    <td>
                        {$v->assign->customer.customer_nm}（{$v->assign->form_params.customer_id}）
                        <br />
                        <input type="checkbox" name="customer_off" value="1" {if ($v->assign->form_params.customer_off)}checked="checked"{/if} /> 精算先を検索条件から外す
                        <input type="hidden" name="customer_id" value="{$v->helper->form->strip_tags($v->assign->form_params.customer_id)}" />
                    </td>
                </tr>
            {/if}
            <tr>
                <th>キーワード</th>
                <td>
                    <input type="text" name="keywords" size="50" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->form_params.keywords)}" />
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
    
    