<?php

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$port   = 8000;
$server = new Server("0.0.0.0", $port);

$server->on("Start", function (Server $server) use ($port) {
    echo "Swoole WebSocket Server is started at http://127.0.0.1:{$port}\n";
});

$server->on('Open', function (Server $server, Swoole\Http\Request $request) {
    echo "connection open: {$request->fd}\n";

    $server->tick(1000, function () use ($server, $request) {
        $server->push($request->fd, json_encode(["hello", time()]));
    });
});

$server->on('Message', function (Server $server, Frame $frame) {
    echo "received message: {$frame->data}\n";
    $server->push($frame->fd, json_encode(["hello", time()]));
});

$server->on('Close', function (Server $server, int $fd) {
    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function (Server $server, int $fd) {
    echo "connection disconnect: {$fd}\n";
});

$server->start();
