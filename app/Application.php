<?php

namespace App;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Override getNamespace to avoid reading composer.json
     * Fix untuk shared hosting (InfinityFree) yang memblokir file_get_contents pada composer.json
     */
    public function getNamespace(): string
    {
        return 'App\\';
    }
}
