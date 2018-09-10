<?php

namespace Noogic\LaravelRequest;

use Illuminate\Http\Request;

abstract class ApplicationRequestPlugin
{
    public function __construct(ApplicationRequestPluginContainer $plugins)
    {
        $plugins->register($this);
    }

    abstract function run(array $data, $user, Request $request);
}
