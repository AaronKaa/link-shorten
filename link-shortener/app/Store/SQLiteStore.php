<?php

namespace App\Store;

class SQLiteStore extends Store implements LinkStore
{
    /**
     * connection
     *
     * @var string
     */
    protected string $connection = 'sqlite';
}
