<?php

namespace App\Http\Controllers;

use App\Exceptions\HostedException;
use App\Exceptions\UrlInvalidException;
use App\UrlShortener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function index(Request $request, $hash)
    {
        $connection = Redis::connection();
        $handler = new UrlShortener($connection);
        $url = $handler->get($hash);
        if(empty($url)){
            abort(404);
        }
        return redirect($url, 301);
    }

    public function submit(Request $request)
    {
        $url = $request->json("url");
        if(empty($url)){
            throw new UrlInvalidException($url);
        }
        // handle url without scheme
        $regexScheme = '%^(https?://|ftp://)?(.+)%';
        if(!preg_match($regexScheme, $url, $matches)){
            throw new UrlInvalidException($url);
        }
        list(,$scheme,$rest) = $matches;
        if(empty($scheme)){
            $url = "http://$rest";
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host == config("app.domain")) {
            throw new HostedException($url, config("app.domain"));
        };
        $connection = Redis::connection();
        $handler = new UrlShortener($connection);
        $hash = $handler->handle($url);
        $shortUrl = config("app.scheme") . "://" . config("app.domain") . "/$hash";
        return [
            "url" => $url,
            "shorten_url" => $shortUrl,
        ];
    }
}
