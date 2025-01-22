<?php

return [
    'url_base' => env('SHORTNER_URL', 'https://very.shrt'),
    'url_length' => env('SHORTNER_URL_LENGTH', 7),
    'store' => env('SHORTNER_STORGE', 'redis'),
];
