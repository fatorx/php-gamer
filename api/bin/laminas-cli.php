<?php

use Application\Http\Header\Custom;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Laminas\Mvc\Application;
use Laminas\Http\PhpEnvironment\Request;

$bootstrapConfig = include __DIR__ . '/bootstrap.php';
$app             = Application::init($bootstrapConfig);
$appConfig       = $app->getConfig()['app'];

$route = $argv[1] ?? '';
$pars  = $argv[2] ?? '';

/** @var Request $request */
$request = $app->getRequest();

$request->setMethod('GET');
if ($pars) {
    $headers = new Headers();
    $headerContentType = new Custom("Content-Type", "application/json");
    $headers->addHeader($headerContentType);
    $request->setHeaders($headers);

    $request->setContent(Json::encode($pars));
    $request->setMethod('POST');
}

$uri = $request->getUri();
$uri->setPath($route);

$app->run();
$content = $app->getResponse()
    ->getContent();

//echo "\n".$content."\n\n";

