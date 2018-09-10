<?php

namespace Noogic\LaravelRequest;

class ApplicationRequestPluginContainer
{
    protected $plugins = [];

    public function register(ApplicationRequestPlugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function plugins()
    {
        return $this->plugins;
    }
}
