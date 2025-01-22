<?php

namespace App\Services;

use App\Store\LinkStore;

class ShortnerService
{
    /**
     * linkStore
     *
     * @var LinkStore
     */
    private LinkStore $linkStore;

    /**
     * shortUrlBase
     *
     * @var string
     */
    private string $shortUrlBase;

    /**
     * __construct
     *
     * @param LinkStore $linkStore
     */
    public function __construct(LinkStore $linkStore)
    {
        $this->linkStore = $linkStore;
        $this->setShortUrlBase();
    }

    /**
     * encode
     *
     * @param string $originalUrl
     *
     * @return void
     */
    public function encode(string $originalUrl)
    {
        $short_link = $this->makeShortUrl();
        $this->linkStore->save($originalUrl, $short_link);

        return $short_link;
    }

    /**
     * decode
     *
     * @param string $shortUrl
     *
     * @return void
     */
    public function decode(string $shortUrl)
    {
        $original_url = $this->linkStore->get($shortUrl);

        return $original_url;
    }

    /**
     * setShortUrlBase
     *
     * @return void
     */
    protected function setShortUrlBase()
    {
        $shortUrlBase = config('shorten.url_base');

        $this->shortUrlBase = $shortUrlBase;

        if (!$this->hasTrailingSlash((string)$shortUrlBase)) {
            $this->shortUrlBase .= '/';
        }
    }

    /**
     * makeShortUrl
     *
     * @return string
     */
    protected function makeShortUrl(): string
    {
        $shortUrlLength = config('shorten.url_length');

        return $this->shortUrlBase . $this->generateShortCode((string)$shortUrlLength);
    }

    /**
     * generateShortCode
     *
     * @param int $n the code length
     *
     * @return string
     */
    protected function generateShortCode(int $n): string
    {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $alphabetLength = strlen($alphabet);
        $result = '';

        for ($i = 0; $i < $n; $i++) {
            $result .= $alphabet[random_int(0, $alphabetLength - 1)];
        }

        return $result;
    }

    /**
     * hasTrailingSlash
     *
     * @param string $string
     *
     * @return bool
     */
    protected function hasTrailingSlash(string $string): bool
    {
        return (bool)substr($string, -1) === '/';
    }
}
