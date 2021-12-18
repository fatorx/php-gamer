<?php

namespace Application\Controller;

use Laminas\Http\Request;
use Laminas\Json\Json;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\View\Model\JsonModel;
use Firebase\JWT\JWT;
use Laminas\EventManager\EventManagerInterface;
use Application\Jwt\Payload;

class ApiController extends AbstractRestfulController implements IController
{

    /**
     * @var Integer $httpStatusCode Define Api Response code.
     */
    public int $httpStatusCode = 200;

    /**
     * @var array $apiResponse Define response for api
     */
    public array $apiResponse;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var \stdClass Object or Array
     */
    public $tokenPayload;

    /**
     * @var null
     */
    private $redis = null;

    /**
     * set Event Manager to check Authorization
     * @param EventManagerInterface $events
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', [$this, 'checkAuthorization'], 10);
    }

    /**
     * This Function call from eventmanager to check authntication and token validation
     * @param type $event
     *
     */
    public function checkAuthorization($event)
    {
        $request = $event->getRequest();
        $method  = $request->getMethod();

        $response = $event->getResponse();
        $isAuthorizationRequired = $event->getRouteMatch()->getParam('isAuthorizationRequired');
        $methods = $event->getRouteMatch()->getParam('methodsAuthorization');
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $event->setParam('config', $config);

        if (!empty($methods) && !in_array($method, $methods)) {
            return $this->stopRequest('HTTP Method ' . $method . ' not allowed for this action.');
        }

        if (isset($config['ApiRequest'])) {
            $configApi = $config['ApiRequest'];
            $responseStatusKey = $configApi['responseFormat']['statusKey'];
            if (!$isAuthorizationRequired) {
                return;
            }
            //return $this->stopRequest('This action requires authentication.'); // @todo check access

            $jwtToken = $this->findJwtToken($request);

            if ($jwtToken) {
                $this->token = $jwtToken;
                $this->decodeJwtToken();
                if (is_object($this->tokenPayload)) {
                    return; // $this->stopRequest('This token is not valid.');
                }
                $response->setStatusCode(400);
                $jsonModelArr = [$responseStatusKey => $configApi['responseFormat']['statusNokText'],
                $configApi['responseFormat']['resultKey'] => [$configApi['responseFormat']['errorKey'] =>
                        $this->tokenPayload]];
            } else {
                $response->setStatusCode(401);
                $jsonModelArr = [$responseStatusKey => $configApi['responseFormat']['statusNokText'],
                $configApi['responseFormat']['resultKey'] => [$configApi['responseFormat']['errorKey'] =>
                $configApi['responseFormat']['authenticationRequireText']]];
            }
        } else {
            $response->setStatusCode(400);
            $jsonModelArr = ['status' => 'NOK', 'result' => ['error' => 'Require copy this file config\autoload\restapi.global.php']];
        }

        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $view = new JsonModel($jsonModelArr);
        $response->setContent($view->serialize());
        return $response;
    }

    /**
     * Check Request object have Authorization token or not
     * @param  $request
     * @return string String
     */
    public function findJwtToken($request): ?string
    {
        $jwtToken = $request->getHeaders("Authorization") ? $request->getHeaders("Authorization")->getFieldValue() : '';
        if ($jwtToken) {
            $jwtToken = trim(trim($jwtToken, "Bearer"), " ");
            return $jwtToken;
        }
        if ($request->isGet()) {
            $jwtToken = $request->getQuery('token');
        }
        if ($request->isPost()) {
            $jwtToken = $request->getPost('token');
        }
        return $jwtToken;
    }

    /**
     * contain user information for createing JWT Token
     * @param $payload
     * @return false|string
     */
    protected function generateJwtToken($payload)
    {
        if (!is_array($payload) && !is_object($payload)) {
            $this->token = false;
            return false;
        }
        $this->tokenPayload = $payload;
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        $this->token = JWT::encode($this->tokenPayload, $cypherKey, $tokenAlgorithm);
        return $this->token;
    }

    /**
     * contain encoded token for user.
     */
    protected function decodeJwtToken()
    {
        if (!$this->token) {
            $this->tokenPayload = false;
        }
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        try {
            $decodeToken = JWT::decode($this->token, $cypherKey, [$tokenAlgorithm]);
            $this->tokenPayload = $decodeToken;
        } catch (\Exception $e) {
            $this->tokenPayload = $e->getMessage();
        }
    }

    /**
     * @return ?object
     */
    protected function getTokenPayload() : ?object
    {
        return $this->tokenPayload;
    }

    /**
     * @return Payload
     */
    protected function getPayload() : Payload
    {
        $payload = new Payload($this->tokenPayload);
        return $payload;
    }

    /**
     * Create Response for api Assign require data for response and check is valid response or give error
     *
     * @param array $apiResponse
     * @return JsonModel
     */
    public function createResponse(array $apiResponse = [])
    {
        $numArgs = count($apiResponse);

        $config = $this->getEvent()->getParam('config', false);
        $event = $this->getEvent();

        /** @var Response */
        $response = $event->getResponse();

        if ($numArgs > 0) {
            $response->setStatusCode($this->httpStatusCode);
        } else {
            $this->httpStatusCode = 500;
            $response->setStatusCode($this->httpStatusCode);
            $errorKey = $config['ApiRequest']['responseFormat']['errorKey'];
            $defaultErrorText = $config['ApiRequest']['responseFormat']['defaultErrorText'];
            $apiResponse[$errorKey] = $defaultErrorText;
        }

        $statusKey = $config['ApiRequest']['responseFormat']['statusKey'];

        $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusNokText'];
        if ($this->httpStatusCode == 200) {
            $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusOkText'];
        }

        $sendResponse[$config['ApiRequest']['responseFormat']['resultKey']] = $apiResponse;
        $sendResponse['request_time'] = (new \Datetime())->format('Y-m-d H:i:s.u');
        return new JsonModel($sendResponse);
    }

    public function stopRequest($message)
    {
        $data = [
            'message'      => $message,
            'request_time' => (new \Datetime())->format('Y-m-d H:i:s.u')
        ];

        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $view = new JsonModel($data);
        $response->setContent($view->serialize());

        return $response;
    }

    public function setConfig(array $config)
    {
        // TODO: Implement setConfig() method.
    }

    /**
     * @param string $param Parameter name to retrieve, or null to get all.
     * @param mixed $default Default value to use when the parameter is missing.
     * @return mixed
     */
    public function getJsonParameters($param = null, $default = null)
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $content = $request->getContent();
        $data = Json::decode($content, 1);
        if ($param == null) {
            return $data;
        }
        return isset($data[$param]) ? $data[$param] : $default;
    }
}
