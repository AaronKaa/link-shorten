<?php

namespace App\Store;

interface LinkStore
{
    /**
     * save
     *
     * @param string $originalUrl
     * @param string $shortenedUrl
     *
     * @return string
     */
    public function save(string $originalUrl, string $shortenedUrl): string;

    /**
     * get
     *
     * @param string $shortenedUrl
     *
     * @return string
     */
    public function get(string $shortenedUrl): string;
}
