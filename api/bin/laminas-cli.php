<?php
//echo "\n here \n"; exit();

use Application\Http\Header\Custom;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Laminas\Mvc\Application;
use Laminas\Http\PhpEnvironment\Request;

$bootstrapConfig = include __DIR__ . '/bootstrap.php';
$app             = Application::init($bootstrapConfig);
$appConfig       = $app->getConfig()['app'];

$route = $argv[1] ?? '/';
$pars  = $argv[2] ?? '';

/** @var Request $request */
$request = $app->getRequest();

$request->setMethod('GET');
if ($pars) {
    $headers = new Headers();
    $headerContentType = new Custom("Content-Type", "application/json");
    $headers->addHeader($headerContentType);
    $request->setHeaders($headers);

    $parsEncode = Json::encode($pars);
    $request->setContent($parsEncode);
    $request->setMethod('POST');

    echo "\n";
    echo "Pars: " . $parsEncode."\n";
}

$uri = $request->getUri();
$uri->setPath($route);

$app->run();
$content = $app->getResponse()
    ->getContent();

echo "\n\n";

