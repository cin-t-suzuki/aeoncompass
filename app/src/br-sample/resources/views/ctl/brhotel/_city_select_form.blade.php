@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

{{--使用 $hotel, $mast_citie --}}

<script language="javascript"  type="text/javascript">
	<!--
		$(document).ready(function () {

			$('select[name="Hotel[city_id]"]').change(function () { // 市変更時に区を再構成
				var uri_ward = "{{ route('ctl.brhotel.searchward')}}"
				uri_ward += '?city_id='+ encodeURI($('select[name="Hotel[city_id]"]').val());
				$.get(uri_ward, function(html){
					$('#res_ward').html(html);
				});
			});

		});
	//-->
	</script>


{{-- 市表示 --}}
{{-- isset($hotel['city_id']の場合、変数でないとして警告が出る)--}}
@if (count($mast_cities['values']) != 0)
	<select size="1" name="Hotel[city_id]">
		<option value="">未選択</option>
		@foreach ($mast_cities['values'] as $mast_city)

			<option value="{{strip_tags($mast_city['city_id'])}}"

				@if (isset($hotel) && array_key_exists('city_id',$hotel) && !$service->is_empty($hotel['city_id']) && $mast_city['city_id'] == $hotel['city_id'])
						selected
					@endif
			>
				{{strip_tags($mast_city['city_nm'])}}
			</option>
		@endforeach
	</select>
@endif
