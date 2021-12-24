<?php

namespace Application\WebSocket\Events;

use Application\WebSocket\Connections\ControlConnections;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Utopia\CLI\Console;

class Open
{
    private array $config = [];
    private ControlConnections $c;

    /**
     * @param array $config
     * @param ControlConnections $c
     */
    public function __construct(array $config, ControlConnections $c)
    {
        $this->config = $config;
        $this->c = $c;
    }

    /**
     * @param Server $server
     * @param int $origin
     */
    public function __invoke(Server $server, Request $request)
    {
        $this->c->addConnection($server->getWorkerId(), $request->fd);

        Console::info("Connection open (user: {$request->fd}, worker: {$server->getWorkerId()})");
        Console::info('Total connections: '.count($this->c->getConnections()));

        $data = [
            'result' => [
                'action'  => 'hello',
                'message' => 'Hello WS',
            ],
            'request_time' => (new \Datetime())->format('Y-m-d H:i:s.u')
        ];
        $server->push($request->fd, json_encode($data));
    }

}
