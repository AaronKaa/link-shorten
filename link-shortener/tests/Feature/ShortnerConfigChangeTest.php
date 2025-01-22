<?php
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Artisan::call('config:clear');
});

test('That the link length option works as expected', function () {

    $encode_link = '/encode?url=http://example.com';

    for ($length = 1; $length <= 10; $length++) {

        app('config')->set('shorten.url_length',$length);

        $response = $this->getJson($encode_link);
        $return_link = json_decode($response->content());
        $slug_length = strlen(basename($return_link->link));

        expect($length == $slug_length)->toBeTrue();
    }
});

test('That we can change the returned link using the config (1)', function () {
    $encode_link = '/encode?url=http://shorten-me.com';
    $url_base = 'http://tin.y';

    app('config')->set('shorten.url_base', $url_base);

    $response = $this->getJson($encode_link);
    $return_link = json_decode($response->content());

    $return_scheme = parse_url($return_link->link, PHP_URL_SCHEME);
    $return_hostname = parse_url($return_link->link, PHP_URL_HOST);

    $test_scheme = parse_url($url_base, PHP_URL_SCHEME);
    $test_hostname = parse_url($url_base, PHP_URL_HOST);

    expect($test_scheme === $return_scheme)->toBeTrue();
    expect($test_hostname == $return_hostname)->toBeTrue();
});

test('That we can change the returned link using the config (2)', function () {
    $encode_link = '/encode?url=http://shorten-me.com';
    $url_base = 'http://shor.ten';

    app('config')->set('shorten.url_base', $url_base);

    $response = $this->getJson($encode_link);
    $return_link = json_decode($response->content());

    $return_scheme = parse_url($return_link->link, PHP_URL_SCHEME);
    $return_hostname = parse_url($return_link->link, PHP_URL_HOST);

    $test_scheme = parse_url($url_base, PHP_URL_SCHEME);
    $test_hostname = parse_url($url_base, PHP_URL_HOST);

    expect($test_scheme === $return_scheme)->toBeTrue();
    expect($test_hostname == $return_hostname)->toBeTrue();
});

test('That we can change the returned link using the config (3)', function () {

    $encode_link = '/encode?url=http://shorten-me.com';
    $url_base = 'https://min.y';

    app('config')->set('shorten.url_base', $url_base);

    $response = $this->getJson($encode_link);
    $return_link = json_decode($response->content());

    $return_scheme = parse_url($return_link->link, PHP_URL_SCHEME);
    $return_hostname = parse_url($return_link->link, PHP_URL_HOST);

    $test_scheme = parse_url($url_base, PHP_URL_SCHEME);
    $test_hostname = parse_url($url_base, PHP_URL_HOST);

    expect($test_scheme === $return_scheme)->toBeTrue();
    expect($test_hostname == $return_hostname)->toBeTrue();
});
