<?php

namespace App\Hashing;

use Illuminate\Hashing\HashManager;

class CustomHashManager extends HashManager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'blowfish_cipher';
    }
}
