<?php

namespace App\Exceptions;

use Exception;

/**
 * 重複アクセスエラー時に投げる例外
 *
 * HACK: 命名は、移植元ソースに合わせている
 */
class AccessesException extends Exception
{
    //
}
