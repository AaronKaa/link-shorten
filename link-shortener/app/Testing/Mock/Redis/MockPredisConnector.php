<?php

namespace App\Testing\Mock\Redis;

use Illuminate\Redis\Connectors\PredisConnector;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Redis\Connections\PredisClusterConnection;
use Illuminate\Support\Arr;
use M6Web\Component\RedisMock\RedisMockFactory;

class MockPredisConnector extends PredisConnector
{
    /**
     * Create a new clustered Predis connection.
     *
     * @param array $config
     * @param array $options
     *
     * @return \Illuminate\Redis\Connections\PredisConnection
     */
    public function connect(array $config, array $options): PredisConnection
    {
        $formattedOptions = array_merge(
            ['timeout' => 10.0],
            $options,
            Arr::pull($config, 'options', [])
        );

        $factory = new RedisMockFactory();
        $redisMockClass = $factory->getAdapter('Predis\Client', true);

        return new MockPredisConnection(new $redisMockClass($config, $formattedOptions));
    }

    /**
     * Create a new clustered Predis connection.
     *
     * @param array $config
     * @param array $clusterOptions
     * @param array $options
     *
     * @return PredisClusterConnection
     */
    public function connectToCluster(array $config, array $clusterOptions, array $options): PredisClusterConnection
    {
        $clusterSpecificOptions = Arr::pull($config, 'options', []);

        $factory = new RedisMockFactory();
        $redisMockClass = $factory->getAdapter('Predis\Client', true);

        return new MockPredisConnection(
            new $redisMockClass(
                array_values($config),
                array_merge(
                    $options,
                    $clusterOptions,
                    $clusterSpecificOptions
                )
            )
        );
    }
}
