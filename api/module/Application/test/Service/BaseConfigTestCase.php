<?php

namespace ApplicationTest\Service;

use DirectoryIterator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Dotenv\Dotenv;
use Laminas\Stdlib\ArrayUtils;
use PHPUnit\Framework\TestCase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use Redis;

class BaseConfigTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var EntityManager $em
     */
    protected EntityManager $em;

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $returnFetch = [];

    /**
     * @var null
     */
    protected $findOneByResult;


    /**
     * @throws ORMException
     */
    public function setUp(): void
    {
        // actions
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../', '.env.test');
        $dotenv->load();

        $configOverrides = include __DIR__ . '/../../../../config/autoload/local.php';
        $configLocal = include __DIR__ . '/../../../../config/autoload/test.data.php';
        $this->config = ArrayUtils::merge(
            $configLocal['app'],
            $configOverrides['app']
        );

        $this->em = $this->getEntityManager();

        parent::setUp();
    }

    /**
     * @throws ORMException
     */
    function getEntityManager() : EntityManager
    {
        $entityManager = null;

        if ($entityManager === null) {
            // This code is for read modules
            $dirModules = __DIR__ .'/../../../../module/';
            $d = new DirectoryIterator($dirModules);
            $paths = [];
            foreach ($d as $item) {
                if($item->isDot())
                    continue;

                $paths[] = $item;
            }

            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            $configLocal = include __DIR__ .'/../../../../config/autoload/local.php';
            $dbParams = $configLocal['doctrine']['connection']['orm_default']['params'];
            $dbParams['driver'] = 'pdo_mysql';

            $entityManager = EntityManager::create($dbParams, $config);
        }

        return $entityManager;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getStorage(): Redis
    {
        $redis = new Redis();
        $redis->connect($this->config['redis_host'], 6379);
        return $redis;
    }
}
