<?php

declare(strict_types=1);

namespace ApplicationTest\Controller;

use Dotenv\Dotenv;
use Exception;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Laminas\Json\Json;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BaseControllerTest extends AbstractHttpControllerTestCase
{
    const PATH_CACHE = __DIR__ . '/../../../../data/cache';

    /**
     * @var array
     */
    public array $configApp = [];

    /**
     * @var array
     */
    public array $result = [];

    public function setUp() : void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../', '.env.test');
        $dotenv->load();

        $configOverrides = include __DIR__ . '/../../../../config/autoload/test.data.php';

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function getConfigApp()
    {
        return $this->getApplicationConfig()['app'];
    }

    /**
     * @param array $config
     */
    public function setRequestHeadersParametersToken(array $config = [])
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $headers = new Headers();
        $headers->addHeaderLine('App-Key', $config['app_key']);
        $headers->addHeaderLine('Content-Type', 'application/json');
        $request->setHeaders($headers);

        $postData = [
            'username' => $config['app_username'],
            'password' => $config['app_password'],
        ];
        $request->setMethod('POST')->setContent( Json::encode($postData)) ;
    }

    /**
     * @throws Exception
     */
    public function getRequestHeadersJwt($postToken = ''): Request
    {
        $configApp = $this->getConfigApp();
        $token = $this->generateToken($configApp);

        /** @var Request $request */
        $request = $this->getRequest();

        $headers = new Headers();
        $headers->addHeaderLine('App-Key', $configApp['app_key']);
        $headers->addHeaderLine('Authorization', 'Bearer ' . $token . $postToken);
        $request->setHeaders($headers);

        return $request;
    }

    /**
     * @throws Exception
     */
    public function generateToken(array $config = [], $regenerate = false)
    {
        $fileName = self::PATH_CACHE.'/token-app.txt';
        if (!is_file($fileName) || $regenerate) {
            $this->setRequestHeadersParametersToken($config);
            $this->dispatch('/token');

            $content = $this->getResponse()->getContent();
            $this->result = Json::decode($content, true);

            $isSetToken = isset($this->result['result']['token']);
            $this->assertTrue($isSetToken);
            if ($isSetToken) {
                file_put_contents($fileName, $this->result['result']['token']);
                return $this->result['result']['token'];
            }
        }

        return file_get_contents($fileName);
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    public function getResponseContent()
    {
        $content = $this->getResponse()->getContent();
        return Json::decode($content, true);
    }
}
