<?php

namespace PHPPM\Bridges;

use Psr\Http\Message\ServerRequestInterface;
use RingCentral\Psr7;

class StaticBridge implements BridgeInterface
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function bootstrap($appBootstrap, $appenv, $debug)
    {
        // empty
    }

    /**
     * {@inheritdoc}
     *
     * @return \RingCentral\Psr7\Response
     */
    public function handle(ServerRequestInterface $request)
    {
        return new Psr7\Response(404, ['Content-type' => 'text/plain'], 'Not found');
    }
}
