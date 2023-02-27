<!-- {* 部屋のelements *} -->
@foreach($room->plan_elements as $plan_element)
	<tr>
		<td nowrap  bgcolor="#EEEEFF" >{{$plan_element->element_nm}}</td>
		<td>
			<table border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td align="left">
						{{$plan_element->element_value_text}}
					</td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
@endforeach
<!-- {* 部屋のelements *} -->