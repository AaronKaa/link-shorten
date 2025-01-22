<?php

namespace App\Store;

use App\Exceptions\UrlNotFoundException;
use Illuminate\Support\Facades\Redis;

class RedisStore extends Store implements LinkStore
{
    /**
     * save
     *
     * @param string $originalUrl
     * @param string $shortenedUrl
     *
     * @return string
     */
    public function save(string $originalUrl, string $shortenedUrl): string
    {
        Redis::set($shortenedUrl, $originalUrl);

        return $shortenedUrl;
    }

    /**
     * get
     *
     * @param string $shortenedUrl
     *
     * @return string
     */
    public function get(string $shortenedUrl): string
    {
        $originalUrl = Redis::get($shortenedUrl);

        if (!$originalUrl) {
            throw new UrlNotFoundException("Shortened URL not found.");
        }

        return $originalUrl;
    }
}
