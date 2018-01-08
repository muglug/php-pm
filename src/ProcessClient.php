<?php

namespace PHPPM;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\UnixConnector;
use React\Socket\ConnectionInterface;

class ProcessClient
{
    use ProcessCommunicationTrait;

    /**
     * @var LoopInterface
     */
    protected $loop;

    public function __construct()
    {
        $this->loop = Factory::create();
    }

    /**
     * @return void
     */
    protected function request($command, $options, $callback)
    {
        $data = [
            'cmd' => $command,
            'options' => $options
        ];

        $connector = new UnixConnector($this->loop);
        $unixSocket = $this->getControllerSocketPath(false);

        $connector->connect($unixSocket)->done(
            function($connection) use ($data, $callback) {
                $result = '';

                $connection->on('data', function($data) use (&$result) {
                    $result .= $data;
                });

                $connection->on('close', function() use ($callback, &$result) {
                    $callback($result);
                });

                $connection->write(json_encode($data) . PHP_EOL);
            }
        );
    }

    /**
     * @return void
     */
    public function getStatus(callable $callback)
    {
        $this->request('status', [], /**
         * @return void
         */
        function($result) use ($callback) {
            $callback(json_decode($result, true));
        });
        $this->loop->run();
    }

    /**
     * @return void
     */
    public function stopProcessManager(callable $callback)
    {
        $this->request('stop', [], /**
         * @return void
         */
        function($result) use ($callback) {
            $callback(json_decode($result, true));
        });
        $this->loop->run();
    }
}
