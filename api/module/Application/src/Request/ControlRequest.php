<?php

namespace Application\Request;

use Laminas\Mvc\Application;
use Laminas\Http\Headers;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\Header\Authorization;
use Laminas\Json\Json;
use Laminas\Stdlib\RequestInterface;

use Application\Http\Header\Custom;

class ControlRequest
{
    public Application $app;
    public array $appConfig;
    public string $action;
    public string $method;
    public RequestInterface $clearRequest;

    public string $log = '';

    public function __construct($bootstrapConfig)
    {
        $this->app = Application::init($bootstrapConfig);
        $this->appConfig = $this->app->getConfig()['app'];
    }

    public function logItems($route, $origin, $sendData)
    {
        if ($route != '/middle-cards/turn') {
            //return false;
        }

        $dateTime = (new \DateTime())->format('Y-m-d H:i:s');
        echo "\n\n";
        echo 'Connection : ' . $origin . "\n";
        echo $dateTime . "\n";
        echo $route."\n";

        if ($sendData != []) {
            echo "Send Data " . json_encode($sendData)."\n";
        }

        if ($route == '/token') {
            return false;
        }

        $pars = [
            'route' => $route,
            'data'  => json_encode($sendData)
        ];
    }

    /**
     * @param Parameters $parameters
     * @param $origin
     * @return $this
     */
    public function perform(Parameters $parameters, $origin): static
    {
        $route        = $parameters->getRoute();
        $sendData     = $parameters->getSendData();
        $token        = $parameters->getToken();
        $this->action = $parameters->getAction();

        $this->logItems($route, $origin, $sendData);

        if ($route !== '/token') {
            $this->configRequest($route, $token, $sendData);
        } else {
            $this->configRequestToken($origin, $sendData);
        }

        return $this;
    }

    /**
     * @param $origin
     * @param array $sendData
     * @return bool
     */
    public function configRequestToken($origin, array $sendData = []): bool
    {
        /** @var Request $request */
        $request   = $this->app->getRequest();
        $headers = new Headers();

        $headerAppKey = new Custom("App-Key", $this->appConfig['app_key']);
        $headerContentType = new Custom("Content-Type", "application/json");
        $headers->addHeader($headerAppKey);
        $headers->addHeader($headerContentType);
        $request->setHeaders($headers);

        $pars = [
            "username"   => $this->appConfig['app_username'],
            "password"   => $this->appConfig['app_password'],
            "connection" => $origin,
        ];

        if (isset($sendData['username'])) {
            $pars = [
                "username"   => $sendData['username'],
                "password"   => $sendData['password'],
                "connection" => $origin,
                "game"       => $sendData['game'] ?? 0
            ];
        }

        $request->setContent(Json::encode($pars));
        $request->setMethod('POST');

        $uri = $request->getUri();
        $uri->setPath('/token');
        $this->method = $request->getMethod();

        return true;
    }

    /**
     * @param $route
     * @param $token
     * @param $sendData
     */
    public function configRequest($route, $token, $sendData)
    {
        /** @var Request $request */
        $request   = $this->app->getRequest();
        $headers = new Headers();

        $auth = new Authorization('Bearer ' . $token);
        $headers->addHeader($auth);
        $request->setHeaders($headers);
        $request->setMethod('GET');

        // @todo get route e config action, for example: POST, GET and send pars
        if ($sendData != []) {
            $pars = Json::encode($sendData); // @todo check parameters via json, maybe pass connection
            $request->setContent($pars);
            $request->setMethod('POST');
        }

        $uri = $request->getUri();
        $uri->setPath($route);
        $this->method = $request->getMethod();
    }

    /**
     * @param bool $decode
     * @return string|array
     */
    public function getContent(bool $decode = false): string|array
    {
        $this->app->run();
        $content = $this->app->getResponse()
                             ->getContent();
        if ($decode) {
            return Json::decode($content, 1);
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
