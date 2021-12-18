<?php

namespace Application\WebSocket;


class Connections
{
    public static function sendToConnections($connections, $server, $origin, $jsonEncode)
    {
        foreach ($connections as $connection) {
            if ($connection === $origin) {
                continue;
            }
            $server->push($connection, $jsonEncode);
        }
    }

}
