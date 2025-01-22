<?php

namespace App\Testing\Mock\Redis;

use Illuminate\Redis\Connections\PredisConnection;

class MockPredisConnection extends PredisConnection
{
    /**
     * pipeline
     *
     * @param callable $callback
     *
     * @return void
     */
    public function pipeline(callable $callback = null)
    {
        $pipeline = $this->client()->pipeline();

        return is_null($callback)
            ? $pipeline
            : tap($pipeline, $callback)->exec();
    }
}
