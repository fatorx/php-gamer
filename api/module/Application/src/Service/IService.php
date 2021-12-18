<?php

namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Redis;

interface IService
{
    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em);

    /**
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     * @param Redis $storage
     * @return mixed
     */
    public function setStorage(Redis $storage);

    /**
     * @return Redis
     */
    public function getStorage(): Redis;
}
