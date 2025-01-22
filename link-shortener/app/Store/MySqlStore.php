<?php

namespace App\Store;

class MySqlStore extends Store implements LinkStore
{
    /**
     * connection
     *
     * @var string
     */
    protected string $connection = 'mysql';
}
