<?php

namespace App;

use App\Exceptions\CollisionException;
use App\Exceptions\UrlInvalidException;
use Illuminate\Redis\Connections\Connection;
use Tuupola\Base62;

class UrlShortener
{

    protected $connection = null;

    const MOD = 1000;

    const PREFIX_MAIN = "main-";

    protected $maxTries = 100;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function validate($url)
    {
        //https://mathiasbynens.be/demo/url-regex/
        //stephenhay
        $regex = '@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS';
        return preg_match($regex, $url);
    }

    public function handle($url)
    {
        if(!$this->validate($url)){
            throw new UrlInvalidException($url);
        }
        $done = false;
        $tries = 0;
        $hash = null;
        while (!$done) {
            if ($tries > $this->maxTries) {
                throw new CollisionException($url);
            }
            $hash = $this->hash("$tries-$url");
            $mainKey = $this->mainKey($hash);
            $added = $this->connection->hsetnx($mainKey, $hash, $url);
            if ($added) {
                $done = true;
            } else {
                $test = $this->connection->hget($mainKey, $hash);
                if ($test == $url) {
                    $done = true;
                }
            }
            $tries++;
        }
        return $hash;
    }

    public function get($hash)
    {
        $mainKey = $this->mainKey($hash);
        $url = $this->connection->hget($mainKey, $hash);
        return $url;
    }

    protected function mainKey($hash)
    {
        $sum = crc32($hash);
        $index = $sum % static::MOD;
        return static::PREFIX_MAIN . $index;
    }

    protected function hash($raw)
    {
        //pow(36, 10) > pow(62, 8)
        $hash = substr(md5($raw), 0, 10);
        $base62 = new Base62();
        $hash = $base62->encode($hash);
        $hash = substr($hash,0, 8) ;
        return $hash;
    }
}