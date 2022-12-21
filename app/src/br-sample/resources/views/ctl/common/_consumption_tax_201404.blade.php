{{-- {strip} --}}
  {{--=======================================================================--}}
  {{-- 2014年4月からの消費税8%に関する告知                                   --}}
  {{--                                                                       --}}
  {{-- 引数：type ・・・ 表示内容（                                          --}}
  {{--                              pdf:赤枠PDFリンク                        --}}
  {{--                              message：告知文言のみ                    --}}
  {{--                              message-nm：前後の余白なし               --}}
  {{--                              message-c：中央寄せ                      --}}
  {{--                            ）                                         --}}
  {{--                                                                       --}}
  {{--=======================================================================--}}
  
  {{-- 2024年の5月以降は表示しないようにする(時間は仮) --}}
  @if (strtotime(now()) < strtotime('2024-05-01'))
  
    {{-- PDFファイルへのリンク付告知文 --}}
    @if ($type === 'pdf')
      <div style="background-color: #e6b9b8; border: 2px solid #8c3236; padding: 4px; margin: 20px 0; width: 690px;">
        <a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/201404_tax.pdf" target="_blank">2014年4月の消費税引上げに関する対応に関しまして</a>
      </div>
    @endif
    
    {{-- 告知文のみ --}}
    @if ($type === 'message')
      <p style="color: #ff0000; text-align: left;">※宿泊日2014年4月1日以降は、消費税8%相当を含めた「税サ込」の宿泊料金にて登録をお願いします。</p>
    @endif
    
    {{-- 告知文のみ（前後の余白なし） --}}
    @if ($type === 'message-nm')
      <p style="color: #ff0000; text-align: left; margin: 0;">※宿泊日2014年4月1日以降は、消費税8%相当を含めた「税サ込」の宿泊料金にて登録をお願いします。</p>
    @endif
    
    {{-- 告知文のみ（中央寄せ） --}}
    @if ($type === 'message-c')
      <div style="text-align:left; padding: 5px 0px 5px 0px; width:860px; color: #ff0000;">
        ※宿泊日2014年4月1日以降は、消費税8%相当を含めた「税サ込」の宿泊料金にて登録をお願いします。
      </div>
    @endif
    
  @endif
  
{{-- {/strip} --}}