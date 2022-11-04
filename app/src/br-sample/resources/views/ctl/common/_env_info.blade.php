{{-- 本番環境以外のときに実行環境を表示する --}}
@if ($v->config->environment->status !== 'product')
  <div class="env{{ $v->config->environment->status }}">
      @if ($v->config->environment->status === 'test')
        開発環境
      @elseif ($v->config->environment->status === 'development')
        検証環境
      @else
        環境不明
      @endif
  </div>
@endif
