<?php

namespace Application\Request;

use Laminas\Json\Json;

class Parameters
{
    /**
     * @var string
     */
    private string $route;

    /**
     * @var array|string
     */
    private array|string $sendData;

    /**
     * @var string
     */
    private string $token;

    /**
     * @var string
     */
    private string $action;

    /**
     * Parameters constructor.
     * @param string $messageData
     */
    public function __construct(string $messageData = '')
    {
        if ($messageData != '') {
            $parameters = Json::decode($messageData, 1);

            $this->route    = !isset($parameters['route']) ? '' : $parameters['route'];
            $this->sendData = (isset($parameters['data']) && !empty($parameters['data'])) ? $parameters['data'] : [];
            $this->token    = !isset($parameters['token']) ? '' : $parameters['token'];
            $this->action   = !isset($parameters['action']) ? '' : $parameters['action'];
        }
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Parameters
     */
    public function setRoute(string $route): Parameters
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendData(): mixed
    {
        return $this->sendData;
    }

    /**
     * @param array|mixed|string $sendData
     * @return Parameters
     */
    public function setSendData(mixed $sendData): Parameters
    {
        $this->sendData = $sendData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken(): mixed
    {
        return $this->token;
    }

    /**
     * @param mixed|string $token
     * @return Parameters
     */
    public function setToken(mixed $token): Parameters
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * @param mixed|string $action
     * @return Parameters
     */
    public function setAction(mixed $action): Parameters
    {
        $this->action = $action;
        return $this;
    }
}
