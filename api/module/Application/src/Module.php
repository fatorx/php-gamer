<?php

namespace Application;

use Laminas\ServiceManager\ServiceManager;
use Laminas\EventManager\EventInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Http\Request;
use Doctrine\ORM\EntityManager;

use Application\Service\RequestResponseService;
class Module
{
    const VERSION = '0.1';
    const CONFIG_KEY = 'config';

    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $data = (new \Datetime())->format('Y-m-d H:i:s');

        #zero-trust

        /** @var Request $request */
        $request = $e->getApplication()->getRequest();
        $server = $request->getServer();

        // Allow from any origin
        if (!empty($server->get('HTTP_ORIGIN'))) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }

        // Access-Control headers are received during OPTIONS requests
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }

        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_FINISH, function ($e) use ($data) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $reqResService = $serviceManager->get(RequestResponseService::class);
            $reqResService->register($e->getApplication(), $data);
        });

        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_FINISH, function ($e) {
            $headers = $e->getApplication()->getResponse()->getHeaders();
            $headers->addHeaderLine("X-Frame-Options: DENY");
            $headers->addHeaderLine("X-XSS-Protection: 1");
            $headers->addHeaderLine("X-Content-Type-Options: nosniff");
            $headers->addHeaderLine("Referrer-Policy: no-referrer-when-downgrade");
            $headers->addHeaderLine('Expect-CT: max-age=0, report-uri="https://middlecard.com"');

            $headers->addHeaderLine("Strict-Transport-Security:max-age=63072000");
            $headers->addHeaderLine("Feature-Policy: vibrate 'self'; sync-xhr 'self'");
        }, 100000);


    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                RequestResponseService::class => function(ServiceManager $serviceManager) {
                    $reqResService = new RequestResponseService();
                    $entityManager = $serviceManager->get(EntityManager::class);
                    $reqResService->setEm($entityManager);

                    $config = $serviceManager->get(self::CONFIG_KEY);
                    $reqResService->setConfig($config['ApiRequest']);

                    return $reqResService;
                }
            ]
        ];
    }
}
