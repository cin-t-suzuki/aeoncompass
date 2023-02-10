@if (config('app.env') != 'product')
    <p>
        {{ '社内管理者(staff): ' . (Auth::guard('staff')->check() ? '◯' : '×') }}
        <br>
        {{ '施設管理者(hotel): ' . (Auth::guard('hotel')->check() ? '◯' : '×') }}
    </p>
@endif
