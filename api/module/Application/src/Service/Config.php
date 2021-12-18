<?php

namespace Application\Service;

use Laminas\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Redis;

class Config
{
    const CONFIG_KEY = 'config';

    /**
     * @param ServiceManager $serviceManager
     * @param IService $service
     *
     * @return IService
     */
    public function setup(ServiceManager $serviceManager, IService $service): IService
    {
        $config = $serviceManager->get(self::CONFIG_KEY);
        $service->setConfig($config['app']);

        $entityManager = $serviceManager->get(EntityManager::class);
        $service->setEm($entityManager);

        $redis = new Redis();
        $redis->connect($config['app']['redis_host'], 6379);
        $service->setStorage($redis);

        return $service;
    }
}
