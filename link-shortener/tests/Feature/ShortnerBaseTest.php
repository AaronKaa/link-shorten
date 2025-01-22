<?php

use Illuminate\Config\Repository as Config;
use App\Services\ShortnerService;
use App\Store\LinkStore;
use Mockery as m;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('config:clear');
});

test('Tests that the shortner service can encode and deccode a link', function () {

    $originalLink = 'http://example.com';

    $shortner = app()->make(ShortnerService::class);

    $shortLink = $shortner->encode($originalLink);
    $decodedLink = $shortner->decode($shortLink);

    expect($originalLink == $decodedLink)->toBeTrue();
});


test('decode() returns the original URL for a given short URL using the bare services', function () {
    $shortUrl = 'http://short.test/AbCdEfG';
    $expectedOriginalUrl = 'http://example.com';

    $mockLinkStore = m::mock(LinkStore::class);
    $mockConfig = m::mock(Config::class);

    $mockLinkStore
        ->shouldReceive('get')
        ->once()
        ->with($shortUrl)
        ->andReturn($expectedOriginalUrl);

    $service = new ShortnerService($mockLinkStore, $mockConfig);
    $actualUrl = $service->decode($shortUrl);

    expect($actualUrl)->toBe($expectedOriginalUrl);
});
