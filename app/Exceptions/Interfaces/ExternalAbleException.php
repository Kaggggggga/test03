<?php


namespace App\Exceptions\Interfaces;


interface ExternalAbleException
{
    public function externalMessage();
    public function httpStatusCode();
}