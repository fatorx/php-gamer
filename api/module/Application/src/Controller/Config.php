<?php

namespace Application\Controller;

use Laminas\ServiceManager\ServiceManager;

class Config
{
    /**
     * @param ServiceManager $serviceManager
     * @param IController $controller
     * @return IController
     */
    public function setup(ServiceManager $serviceManager, IController $controller): IController
    {
        $config = $serviceManager->get(Strings::CONFIG_KEY);
        $controller->setConfig($config['app']);

        return $controller;
    }
}
