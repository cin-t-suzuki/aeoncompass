{{-- 引数：$child_charge ・・・ 子供料金情報の入ったコンテナ --}}
  {{-- $charge_type  ・・・ 料金タイプ（0:1室あたり, 1:1人あたり） --}}
  <div class="gen-container">
    <h2 class="contents-header">子供料金の設定</h2>

    {{-- 余白 --}}
    <hr class="bound-line" />

    <table class="tbl-child-charge">
      <tr>
        <th colspan="2"><br /></th>
        <th>受け入れ</th>
        <th>大人料金計算時に数える</th>
        @if ($charge_type == 1)
          <th>値段・率</th>
          <th>単位</th>
        @endif
      </tr>

      {{-- 子供1 --}}
      <tr>
        <td>子供1</td>
        <td class="descript">大人に順ずる食事と寝具（小学生）</td>
        <td>
          @if ($child_charge['child1_accept'] == 1) あり
          @else なし
          @endif
        </td>
        <td>
          @if ($child_charge['child1_charge_include'] == 1) 数える
          @else 数えない
          @endif
        </td>

        @if ($charge_type == 1)
          <td>
            {{-- MEMO: ↓ もとは is_empty() --}}
            @if (!is_null($child_charge['child1_charge']))
             {{number_format($child_charge['child1_charge'])}}
            @else <br />
            @endif
          </td>
          <td>
            @if ($child_charge['child1_accept'] == 1)
              @if ($child_charge['child1_charge_unit'] == 1) 円
              @elseif ($child_charge['child1_charge_unit'] == 2) 円引き
              @else ％
              @endif
            @endif
          </td>
        @endif
      </tr>

      {{-- 子供2 --}}
      <tr>
        <td>子供2</td>
        <td class="descript">子供用の食事と寝具（幼児）</td>
        <td>
          @if ($child_charge['child2_accept'] == 1) あり
          @else なし
          @endif
        </td>
        <td>
          @if ($child_charge['child2_charge_include'] == 1) 数える
          @else 数えない
          @endif
        </td>

        @if ($charge_type == 1)
          <td>
            {{-- MEMO: ↓ もとは is_empty() --}}
            @if (!is_null($child_charge['child2_charge']))
            {{ number_format($child_charge['child2_charge']) }}
            @else <br />
            @endif
          </td>
          <td>
            @if ($child_charge['child2_accept'] == 1)
              @if (    $child_charge['child2_charge_unit'] == 1) 円
              @elseif ($child_charge['child2_charge_unit'] == 2) 円引き
              @else ％
              @endif
            @endif
          </td>
        @endif
      </tr>


      {{-- 子供3 --}}
      <tr>
        <td>子供3</td>
        <td class="descript">子供用の食事（幼児）</td>
        <td>
          @if ($child_charge['child4_accept'] == 1) あり
          @else なし
          @endif
        </td>
        <td>
          数えられません
        </td>

        @if ($charge_type == 1)
          <td>
            {{-- MEMO: ↓ もとは is_empty() --}}
            @if (!is_null($child_charge['child4_charge']))
            {{ number_format($child_charge['child4_charge']) }}
            @else <br />
            @endif
          </td>
          <td>
            @if ($child_charge['child4_accept'] == 1)
              @if (    $child_charge['child4_charge_unit'] == 1) 円
              @elseif ($child_charge['child4_charge_unit'] == 2) 円引き
              @else ％
              @endif
            @endif
          </td>
        @endif
      </tr>

      {{-- 子供4 --}}
      <tr>
        <td>子供4</td>
        <td class="descript">子供用の寝具（幼児）</td>
        <td>
          @if ($child_charge['child3_accept'] == 1) あり
          @else なし
          @endif
        </td>
        <td>
          @if ($child_charge['child3_charge_include'] == 1) 数える
          @else 数えない
          @endif
        </td>

        @if ($charge_type == 1)
          <td>
            {{-- MEMO: ↓ もとは is_empty() --}}
            @if (!is_null($child_charge['child3_charge']))
            {{ number_format($child_charge['child3_charge']) }}
            @else <br />
            @endif
          </td>
          <td>
            @if ($child_charge['child3_accept'] == 1)
              @if ($child_charge['child3_charge_unit'] == 1) 円
              @elseif ($child_charge['child3_charge_unit'] == 2) 円引き
              @else ％
              @endif
            @endif
          </td>
        @endif
      </tr>


      {{-- 子供5 --}}
      <tr>
        <td>子供5</td>
        <td class="descript">食事寝具なし（幼児）</td>
        <td>
          @if ($child_charge['child5_accept'] == 1) あり
          @else なし
          @endif
        </td>
        <td>
          数えられません
        </td>

        @if ($charge_type == 1)
          <td>
            {{-- MEMO: ↓ もとは is_empty() --}}
            @if (!is_null($child_charge['child5_charge']))
            {{ number_format($child_charge['child5_charge']) }}
            @else <br />
            @endif
          </td>
          <td>
            @if ($child_charge['child5_accept'] == 1)
              @if (    $child_charge['child5_charge_unit'] == 1) 円
              @elseif ($child_charge['child5_charge_unit'] == 2) 円引き
              @else ％
              @endif
            @endif
          </td>
        @endif
      </tr>
    </table>
  </div>
