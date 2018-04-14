<?php


namespace App\Exceptions;


use App\Exceptions\Interfaces\ExternalAbleException;
use App\Exceptions\Traits\ExternalAbleExceptionTrait;

class UrlInvalidException extends \Exception implements ExternalAbleException
{
    use ExternalAbleExceptionTrait;
    protected $url = null;
    public function __construct($url)
    {
        $this->url = $url;
        $message = "invalid url($url)";
        parent::__construct($message, 0);
        $this->externalMessage = $message;
        $this->httpStatusCode = 422;
    }
}