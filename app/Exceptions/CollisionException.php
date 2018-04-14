<?php


namespace App\Exceptions;


use App\Exceptions\Interfaces\ExternalAbleException;
use App\Exceptions\Traits\ExternalAbleExceptionTrait;

class CollisionException extends \Exception implements ExternalAbleException
{
    use ExternalAbleExceptionTrait;
    protected $url = null;

    public function __construct($url)
    {
        $this->url = $url;
        parent::__construct("url store collided {$url}", 0);
    }
}