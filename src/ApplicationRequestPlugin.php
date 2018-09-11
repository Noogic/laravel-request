<?php

namespace Noogic\LaravelRequest;

use Illuminate\Http\Request;

abstract class ApplicationRequestPlugin
{
    protected static $key = null;

    public static function key()
    {
        return static::$key;
    }

    public static function boot()
    {
        return new static();
    }

    abstract public function run(array $data, $user, Request $request);
}
