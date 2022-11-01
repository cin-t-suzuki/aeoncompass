
@section('title', '施設管理情報更新')
@include('ctl.common.base')

{{-- メッセージ --}}
@include('ctl.common.message', $messages)

{{-- 施設情報詳細 --}}
@include(
    'ctl.brhotel._hotel_info'
    , [
        "hotel" => $views->hotel,
        "mast_pref" => $views->mast_pref,
        "mast_city" => $views->mast_city,
        "mast_ward" => $views->mast_ward
    ]
)

<br>

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/updatemanagement/">

    {include file=$v->env.module_root|cat:'/views/brhotel/_input_management_form.tpl'}

    <INPUT TYPE="submit" VALUE="施設管理情報更新">
    ※は必須です。

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{include file=$v->env.module_root|cat:'/views/brhotel/_log_hotel_person_form.tpl'}

<br>
