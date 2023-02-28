<?php

namespace App\Exceptions;

use App\Exceptions\AccessesException;
use App\Exceptions\NoInformationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof NoInformationException) {
            // パラメータ不足エラーを表示します。
            return response()->view('errors.no-information', [
                'errorMessages' => [
                    'ページを表示するのに必要な情報が不足しています。',
                    'トップページよりやり直してください。',
                ],
            ]);
        }
        if ($e instanceof AccessesException) {
            // 重複アクセスエラーを出力します。
            return response()->view('errors.accesses', [
                'errorMessages' => [
                    '先に同様の手続きが行われ手続きが完了している可能性がございます。',
                    '一覧ページなどでご確認の上、再度手続きください。',
                ],
            ]);
        }

        return parent::render($request, $e);
    }
}
