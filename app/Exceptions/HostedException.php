<?php


namespace App\Exceptions;


use App\Exceptions\Interfaces\ExternalAbleException;
use App\Exceptions\Traits\ExternalAbleExceptionTrait;

class HostedException extends \Exception implements ExternalAbleException
{
    use ExternalAbleExceptionTrait;
    protected $url = null;
    public function __construct($url, $domain)
    {
        $this->url = $url;
        $message = "url($url) is already a $domain url";
        parent::__construct("", 0);
        $this->externalMessage = $message;
        $this->httpStatusCode = 422;
    }
}