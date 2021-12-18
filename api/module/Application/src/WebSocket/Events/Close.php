<?php

namespace Application\WebSocket\Events;

use Application\WebSocket\Connections;
use Exception;
use Laminas\Json\Json;
use Swoole\WebSocket\Server;

class Close
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
     * @param int $origin
     */
    public function __invoke(Server $server, int $origin)
    {

    }

}
