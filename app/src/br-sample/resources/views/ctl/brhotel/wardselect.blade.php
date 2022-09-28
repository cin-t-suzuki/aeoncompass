
@if(isset($views->mast_wards))

	{{-- 外部レイアウトと引数再設定のみ、区候補がない場合は表示しない --}}
	@include('ctl.brhotel._ward_select_form',
				["mast_wards" => $views->mast_wards
			])
@endif