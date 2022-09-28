{{-- エラーメッセージの表示 --}}
@if (!empty($errors) && count($errors) > 0)
<div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
    @foreach ($errors as $error)
        <div>{{ strip_tags($error, '<br>') }}</div>
    @endforeach
</div>
@endif

{{-- ガイドメッセージの表示 --}}
@if (!empty($guides) && count($guides) > 0)
<div style="border-style:solid;border-color:#00f;border-width:1px;padding:6px;background-color:#eef;">
    @foreach ($guides as $guide)
        <div>{{ strip_tags($guide, '<br>') }}</div>
    @endforeach
</div>
@endif
