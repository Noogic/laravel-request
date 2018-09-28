<?php

namespace Noogic\LaravelRequest\Exceptions;

use Throwable;

class PluginNotFoundException extends \Exception
{
    protected $code = 500;
    protected $message = "Invalid plugin request: ";

    public function __construct(string $plugin = "", Throwable $previous = null)
    {
        parent::__construct($this->message . $plugin, $this->code, $previous);
    }

    public function render()
    {
        abort($this->code, $this->message);
    }
}
