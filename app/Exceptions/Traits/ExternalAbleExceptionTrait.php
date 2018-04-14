<?php


namespace App\Exceptions\Traits;


trait ExternalAbleExceptionTrait
{
    protected $externalMessage = "Internal Service Error";
    protected $httpStatusCode = 500;

    public function externalMessage()
    {
        return $this->externalMessage;
    }
    public function httpStatusCode()
    {
        return $this->httpStatusCode;
    }
}