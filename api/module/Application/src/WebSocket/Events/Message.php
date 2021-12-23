<?php

namespace Application\WebSocket\Events;

use Application\Request\ControlRequest;
use Application\Request\Parameters;
use Application\WebSocket\Connections;
use Application\WebSocket\Connections\ControlConnections;
use Exception;
use Laminas\Json\Json;
use Swoole\Connection\Iterator;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Message
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
     * @param Frame $message
     * @return void
     */
    public function __invoke(Server $server, Frame $message)
    {
        /** @var Iterator $connections */
        $connections = $server->connections;
        $origin      = $message->fd;

        try {
            $parameters = new Parameters($message->data);
            $this->request($server, $parameters, $origin, $connections);
        } catch(Exception $e) {
            $json = [
                'error' => $e->getMessage(),
                'code'  => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ];
            $jsonEncode  = Json::encode($json);
            $server->push($origin, $jsonEncode);
        }
    }

    public function request($server, Parameters $parameters, $origin, $connections)
    {
        $controlReq = new ControlRequest($this->config);
        $content    = $controlReq->perform($parameters, $origin)->getContent();
        Connections::sendToConnections($connections, $server, 0, $content);
    }
}
