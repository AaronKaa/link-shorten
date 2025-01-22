<?php

namespace App\Store;

use App\Exceptions\UrlNotFoundException;
use App\Models\Link;

abstract class Store
{
    /**
     * connection
     *
     * @var string
     */
    protected string $connection = 'mysql';

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
        $link = Link::on($this->connection)
            ->create([
                'original_url' => $originalUrl,
                'shortened_url' => $shortenedUrl
            ]);

        return $link->shortened_url;
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
        $link = Link::on($this->connection)->firstWhere('shortened_url', $shortenedUrl);

        if (!$link) {
            throw new UrlNotFoundException("Shortened URL not found.");
        }

        return $link->original_url;
    }
}
