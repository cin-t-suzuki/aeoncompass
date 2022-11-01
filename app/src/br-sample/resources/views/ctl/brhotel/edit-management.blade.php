{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\editmanagement.tpl --}}

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

    @include('ctl.brhotel._input_management_form')

    <INPUT TYPE="submit" VALUE="施設管理情報更新">
    ※は必須です。

</FORM>

@include('ctl.brhotel._hotel_top_form', ["target_cd" => $views->target_cd])

<br>

@include('ctl.brhotel._log_hotel_person_form')

<br>
