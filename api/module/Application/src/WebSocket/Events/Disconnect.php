<?php

namespace Application\WebSocket\Events;

use Swoole\WebSocket\Server;

class Disconnect
{
    private array $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Server $server
     * @param int $fd
     */
    public function __invoke(Server $server, int $fd)
    {
        echo "\n\nconnection disconnect: {$fd}\n";
    }
}
