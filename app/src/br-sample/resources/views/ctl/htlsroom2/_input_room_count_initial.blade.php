      <tr>
        <td  bgcolor="#EEEEFF" >販売希望部屋数</td>

        <td colspan="2">
          <table border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
            <tr>
              <td>
                <table border="0" cellpadding="4" cellspacing="1" width="100%">
                  <tr>
                    <td bgcolor="#FFFFFF">月曜</td>
                    <td bgcolor="#FFFFFF">火曜</td>

                    <td bgcolor="#FFFFFF">水曜</td>
                    <td bgcolor="#FFFFFF">木曜</td>
                    <td bgcolor="#FFFFFF">金曜</td>
                    <td bgcolor="#FFFFFF"><font color="#0000FF">土曜</font></td>
                    <td bgcolor="#FFFFFF"><font color="#FF0000">日曜</font></td>
                    <td bgcolor="#EEEEEE"><font color="#FF0000">祝日</font></td>

                    <td bgcolor="#EEEEEE"><font color="#000000">休前日</font></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_mon]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_mon)}"></td>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_tue]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_tue)}"></td>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_wed]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_wed)}"></td>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_thu]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_thu)}"></td>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_fri]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_fri)}"></td>

                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_sat]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_sat)}"></td>
                    <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_sun]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_sun)}"></td>
                    <td bgcolor="#EEEEEE"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_hol]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_hol)}"></td>
                    <td bgcolor="#EEEEEE"><input maxlength="3" size="5" name="Room_Count_Initial[rooms_bfo]" value="{$v->helper->form->strip_tags($v->assign->room_count_initial.rooms_bfo)}"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>