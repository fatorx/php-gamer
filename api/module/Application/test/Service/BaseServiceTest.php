<?php

namespace ApplicationTest\Service;

use UsersTest\Service\BaseTestCase;
use Application\Service\BaseService;

use Doctrine\ORM\EntityManager;
use Users\Entity\User as ClientEntity;

class BaseServiceTest extends BaseTestCase
{
    /**
     * @var BaseService
     */
    protected $baseService = null;

    /**
     * @var array
     */
    protected $data = [];

    public static function setUpBeforeClass(): void
    {
        // actions
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->baseService = new BaseService();
        $this->baseService->setEm($this->em);
    }
}
