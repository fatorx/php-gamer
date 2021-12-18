<?php

use Laminas\Json\Json;
use Laminas\Mvc\Application;
use Laminas\Http\PhpEnvironment\Request;

$bootstrapConfig = include __DIR__ . '/bootstrap.php';
$app             = Application::init($bootstrapConfig);
$appConfig       = $app->getConfig()['app'];

$redis = new Redis();
$redis->connect($appConfig['redis_host'], 6379);

$route = $argv[1] ?? '';
$pars  = $argv[2] ?? '';

/** @var Request $request */
$request = $app->getRequest();

$headers = [];
$headers = new Headers();

$request->setHeaders($headers);

if ($pars) {
    $json = json_decode($pars, true);
    var_dump($json);
    exit();
}

$uri = $request->getUri();
$uri->setPath($route);

$app->run();
$content = $app->getResponse()
    ->getContent();
$json = Json::decode($content, 1);

if (isset($json['result']['token'])) {
    $redis->set('token', $json['result']['token']);
}

echo "\n";
