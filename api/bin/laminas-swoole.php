<?php

use Application\Util\Environment;
use Application\WebSocket\Connections\ControlConnections;
use Application\WebSocket\Events\Start as StartEvent;
use Application\WebSocket\Events\Open as OpenEvent;
use Application\WebSocket\Events\Message as MessageEvent;
use Application\WebSocket\Events\Close as CloseEvent;
use Application\WebSocket\Events\Disconnect as DisconnectEvent;

use Swoole\Http\Request;
use Swoole\WebSocket\Server;
use Utopia\CLI\Console;

Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);

$bootstrapConfig = include __DIR__ . '/bootstrap.php';
$swoolePort      = Environment::env('SWOOLE_PORT');
$environment     = Environment::env('ENVIRONMENT');

$server = new Server('localhost', $swoolePort);

$c = new ControlConnections();

$server->on('open', new OpenEvent($bootstrapConfig, $c));

$server->on('start', new StartEvent($bootstrapConfig, $swoolePort));

$server->on('message', new MessageEvent($bootstrapConfig, $c));

$server->on('Close', new CloseEvent($bootstrapConfig));

$server->on('Disconnect', new DisconnectEvent($bootstrapConfig));

$server->on("workerStart", function ($server, $workerId) use ($c) {

});

if ($environment == 'dev') {
    $hotReloadOptions = new \EasySwoole\HotReload\HotReloadOptions;
    $hotReloadOptions->disableInotify(false);

    $pathModule = "/mnt/api/module" ;
    $hotReloadOptions->setMonitorFolder([$pathModule]);
    $hotReloadOptions->setIgnoreSuffix(['php']);
    $hotReloadOptions->setReloadCallback(function (\Swoole\Server $server) {
        echo "\n\nFile change event triggered";
        $server->reload();
    });

    $hotReload = new \EasySwoole\HotReload\HotReload($hotReloadOptions);
    $hotReload->attachToServer($server);
}

$server->start();
