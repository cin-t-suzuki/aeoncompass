{{-- MEMO: 移植元 public\app\rsv\view2\mail\subscribe.tpl --}}

{include file='../_common/_header.tpl' title="電子メールアドレスのテスト送信"}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">
      <div style="text-align:center;">
      <div style="width:700px; margin:0 auto;text-align:left;">

{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

    <div class="gi">
      {$v->helper->form->strip_tags($v->assign->email)} にメールを送信いたしました。<br />
      １・２分程たちましても、メールが届かない場合は電子メールアドレスが間違っています。<br />
      電子メールアドレスを確認の上、再度入力しなおしてください。
    </div>
    <br />
    <br />
    <table border="0" cellspacing="0" border="0" bgcolor="#FF9900" width="100%">
      <tr>
        <td>
          <table border="0" cellspacing="1" border="2" width="100%">
            <tr>
              <td align="center" bgcolor="#FFFFFF">
                前の画面に戻りたい場合は、下記 「もどる」ボタンを押してください。
                <form action="{$v->env.ssl_path}{$v->env.module}/{$v->assign->return_pass}/" method="POST">
                  <input type="hidden" name="Member_Subscribe[account_id]"          value="{$v->helper->form->strip_tags($v->assign->account_id)}"         />
                  <input type="hidden" name="Member_Subscribe[password]"            value="{$v->helper->form->strip_tags($v->assign->password)}"           />
                  {if !is_empty($v->assign->j_westid)}
                    <input type="hidden" name="Member_Subscribe[j_westid]"          value="{$v->helper->form->strip_tags($v->assign->j_westid)}"           />
                  {/if}
                  <input type="hidden" name="Member_Subscribe[partner_cd]"          value="{$v->helper->form->strip_tags($v->assign->partner_cd)}"         />
                  <input type="hidden" name="Member_Subscribe[family_nm]"           value="{$v->helper->form->strip_tags($v->assign->family_nm)}"          />
                  <input type="hidden" name="Member_Subscribe[given_nm]"            value="{$v->helper->form->strip_tags($v->assign->given_nm)}"           />
                  <input type="hidden" name="Member_Subscribe[family_kn]"           value="{$v->helper->form->strip_tags($v->assign->family_kn)}"          />
                  <input type="hidden" name="Member_Subscribe[given_kn]"            value="{$v->helper->form->strip_tags($v->assign->given_kn)}"           />
                  <input type="hidden" name="Member_Subscribe[email]"               value="{$v->helper->form->strip_tags($v->assign->email)}"              />
                  <input type="hidden" name="Member_Subscribe[email_confirmation]"  value="{$v->helper->form->strip_tags($v->assign->email_confirmation)}" />
                  <input type="hidden" name="Member_Subscribe[gender]"              value="{$v->helper->form->strip_tags($v->assign->gender)}"             />
                  <input type="hidden" name="Member_Subscribe[year]"                value="{$v->helper->form->strip_tags($v->assign->year)}"               />
                  <input type="hidden" name="Member_Subscribe[month]"               value="{$v->helper->form->strip_tags($v->assign->month)}"              />
                  <input type="hidden" name="Member_Subscribe[day]"                 value="{$v->helper->form->strip_tags($v->assign->day)}"                />
                  <input type="hidden" name="Member_Subscribe[mail_magazine]"       value="{$v->helper->form->strip_tags($v->assign->mail_magazine)}"      />
                  <input type="hidden" name="Member_Subscribe[contact_type]"        value="{$v->helper->form->strip_tags($v->assign->contact_type)}"       />
                  <input type="hidden" name="Member_Subscribe[tel]"                 value="{$v->helper->form->strip_tags($v->assign->tel)}"                />
                  <input type="hidden" name="Member_Subscribe[optional_tel]"        value="{$v->helper->form->strip_tags($v->assign->optional_tel)}"       />
                  <input type="hidden" name="Member_Subscribe[postal_cd]"           value="{$v->helper->form->strip_tags($v->assign->postal_cd)}"          />
                  <input type="hidden" name="Member_Subscribe[pref_id]"             value="{$v->helper->form->strip_tags($v->assign->pref_id)}"            />
                  <input type="hidden" name="Member_Subscribe[address1]"            value="{$v->helper->form->strip_tags($v->assign->address1)}"           />
                  <input type="hidden" name="Member_Subscribe[address2]"            value="{$v->helper->form->strip_tags($v->assign->address2)}"           />
                  <input type="hidden" name="Member_Subscribe[member_group]"        value="{$v->helper->form->strip_tags($v->assign->member_group)}"       />
                  <input type="hidden" name="Member_Subscribe[birth_ymd]"           value="{$v->helper->form->strip_tags($v->assign->birth_ymd)}"          />
                  {section name=email start=1 loop=4}
                    {assign var=email value="email`$smarty.section.email.index`"}
                    {assign var=email_type value="email_type`$smarty.section.email.index`"}
                    {assign var=member_mail_cd value="member_mail_cd`$smarty.section.email.index`"}
                    <input type="hidden" name="Member_Subscribe[{$email}]"          value="{$v->helper->form->strip_tags($v->assign->$email)}"             />
                    <input type="hidden" name="Member_Subscribe[{$email_type}]"     value="{$v->helper->form->strip_tags($v->assign->$email_type)}"        />
                    <input type="hidden" name="Member_Subscribe[{$member_mail_cd}]" value="{$v->helper->form->strip_tags($v->assign->$member_mail_cd)}"    />
                  {/section}
                  <input type="hidden" name="send_magazine_stay"    value="{$v->helper->form->strip_tags($v->assign->send_magazine_stay)}" />
                  <input type="hidden" name="send_magazine_bestcou" value="{$v->helper->form->strip_tags($v->assign->send_magazine_bestcou)}" />
                  <input type="hidden" name="point_camp_cd"         value="{$v->helper->form->strip_tags($v->assign->point_camp_cd)}" />
                  <input type="submit" value="もどる">
                </form>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    </div>
    </div>

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}