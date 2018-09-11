<?php

namespace Noogic\LaravelRequest;

use Noogic\LaravelRequest\Exceptions\PluginNotFoundException;

class ApplicationRequestPluginContainer
{
    protected $plugins = [];

    public function register($plugin, string $key)
    {
        $this->plugins[$key] = $plugin;
    }

    public function get($key)
    {
        if (! isset($this->plugins[$key])) {
            throw new PluginNotFoundException($key);
        }

        return $this->plugins[$key];
    }

    public function index() {
        return $this->plugins;
    }
}
