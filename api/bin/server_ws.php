<?php
/**
 * see https://www.swoole.co.uk/docs/modules/swoole-websocket-server
 */
use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$port    = 8000;
$server  = new Server("0.0.0.0", $port);
$timerId = 0;

$server->on("Start", function (Server $server) use ($port) {
    echo "Swoole WebSocket Server is started at http://127.0.0.1:{$port}\n";
});

$server->on('Open', function (Server $server, Swoole\Http\Request $request) {
    echo "connection open: {$request->fd}\n";

    $server->tick(5000, function () use ($server, $request) {
        $pars = [
            'hello' =>  time(),
            'action' => 'hello'
        ];
        $server->push($request->fd, json_encode($pars));
    });
});

$server->on('Message', function (Server $server, Frame $frame) {
    echo "received message: {$frame->data}\n";

});

$server->on('Close', function (Server $server, int $fd) {
    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function (Server $server, int $fd) {
    echo "connection disconnect: {$fd}\n";
    //$server->clearTimer($timerId);
});

$server->start();
