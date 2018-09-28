<?php

namespace Noogic\LaravelRequest\Exceptions;

class UndefinedKeyException extends \Exception
{
    protected $code = 500;
    protected $message = 'Request plugin key is not defined';

    public function render()
    {
        abort($this->code, $this->message);
    }
}
