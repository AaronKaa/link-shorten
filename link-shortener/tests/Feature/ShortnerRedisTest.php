<?php

test('Test in redis that the links that are meant to return a 422', function() {
    $url_error_links = [
        '/encode' => 422,
        '/decode' => 422,
        '/encode?url=fsoeijowfijw' => 422,
        '/decode?url=oeifjwiofjw' => 422,
    ];

    foreach ($url_error_links as $url_error_link => $status) {
        $response = $this->getJson($url_error_link);
        $response->assertStatus($status);
    }
});

test('Test in sqlite that the links that are meant to return a 422', function() {
    $url_error_links = [
        '/encode' => 422,
        '/decode' => 422,
        '/encode?url=fsoeijowfijw' => 422,
        '/decode?url=oeifjwiofjw' => 422,
    ];

    // loop though the 422 links
    foreach ($url_error_links as $url_error_link => $status) {
        $response = $this->getJson($url_error_link);
        $response->assertStatus($status);
    }
});

test('Test that the encode link works', function () {

    $encode_link = ["url" => '/encode?url=http://example.com', "status" => 200];

    $response = $this->getJson($encode_link['url']);
    $response->assertStatus($encode_link['status']);
});

test('Run another test on the encode link but store the shortened link to test decode', function () {

    $encode_link = ["url" => '/encode?url=http://example.com', "status" => 200];

    $response = $this->getJson($encode_link['url']);
    $response->assertStatus($encode_link['status']);

    $short_link = (object)json_decode($response->content());

    // build the decode link and check for 200
    $response = $this->getJson('/decode?url=' . $short_link->link);
    $response->assertStatus(200);
});
