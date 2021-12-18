<?php

namespace Application\WebSocket\Events;

use Redis;
use Swoole\WebSocket\Server;

class Start
{
    private array $config = [];
    private int $port = 0;

    /**
     * @param array $config
     * @param int $port
     */
    public function __construct(array $config, int $port)
    {
        $this->config = $config;
        $this->port   = $port;
    }

    /**
     * @param Server $server
     */
    public function __invoke(Server $server)
    {
        echo "Laminas application on Swoole http server is started at http://localhost:$this->port\n";


    }
}
