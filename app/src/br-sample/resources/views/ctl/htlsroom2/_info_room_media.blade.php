      <tr>
        <td nowrap  bgcolor="#EEEEFF" >画像情報</td>
        
        @if(0 < count($room->room_media))
        <td colspan="2">
          <table border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
            <tr>
              <td>
                <table border="0" cellpadding="4" cellspacing="1">
                  <tr>
                  <!-- {* メディア情報 *} -->
                  @foreach($room->room_media as $media)
                    <td bgcolor="#ffffff">
                      <img src="/images/hotel/{$v->helper->form->strip_tags($room_media.hotel_cd)}/trim_054/{$v->helper->form->strip_tags($room_media.file_nm)}" width="54" height="54" alt="{$v->helper->form->strip_tags($room_media.title)}">
                    </td>
                  @endforeach
                  <!-- {* メディア情報 *} -->
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        @else
        <td colspan="2">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <table border="0" cellpadding="4" cellspacing="1">
                  <tr>
                    <td></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        @endif
      </tr>