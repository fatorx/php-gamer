<?php

namespace Application\Service;

use Application\Logs\Log;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\EntityManager;
use Laminas\Filter\FilterChain;
use Laminas\Json\Json;
use Redis;

/**
 * Class BaseService
 * @package Application\Service
 */
class BaseService implements IService
{

    use Log;

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @var Redis
     */
    private Redis $storage;

    /**
     * @var EntityManager
     */
    protected EntityManager $em;

    /**
     * Flag for check status operation in services.
     *
     * @var bool
     */
    protected bool $status = true;

    /**
     * @var int
     */
    protected int $userId = 0;

    /**
     * @var string
     */
    protected string $connection = '';

    /**
     * @var string
     */
    protected string $message = '';

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param Redis $storage
     * @return void
     */
    public function setStorage(Redis $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return Redis
     */
    public function getStorage(): Redis
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param $name
     * @param false $clazz
     * @return mixed
     */
    protected function getStoreItem($name, bool $clazz = false): mixed
    {
        $redis  = $this->getStorage();
        if ($redis->exists($name)) {
            $content = $redis->get($name);
            if ($clazz) {
                return unserialize($content);
            }
            return Json::decode($content, 1);
        }
        return false;
    }

    /**
     * @param $name
     * @param $value
     * @param false $clazz
     * @return bool
     */
    protected function setStoreItem($name, $value, bool $clazz = false): bool
    {
        $redis  = $this->getStorage();
        $value = ($clazz) ?
            serialize($value)
            :
            Json::encode($value)
        ;

        $storeMemo = $redis->set($name, $value);
        $storeDB   = $this->storeDatabase($name, $value, $clazz);

        return ($storeMemo && $storeDB);
    }

    /**
     * @param $name
     * @param $value
     * @param bool $clazz
     * @return bool
     */
    public function storeDatabase($name, $value, bool $clazz = false): bool
    {
        return false;
    }

    /**
     * @return Connection
     */
    public function getConnectionDatabase(): Connection
    {
        return $this->em->getConnection();
    }

    /**
     * @param $sql
     * @param string|null $get
     * @param array $pars
     * @return array|bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function executeSql($sql, string|null $get = 'unique', array $pars = []): bool|array
    {
        $stmt = $this->em->getConnection()->prepare($sql);

        try {
            $rs = $stmt->executeQuery($pars);
            if ($get == 'all') {
                return $rs->fetchAllAssociative();
            }

            if ($get == null) {
                return true;
            }

            return $rs->fetchAssociative();
        } catch (\Exception $e) {
            $this->logError(__CLASS__, __METHOD__, $e->getMessage());
        }

        return false;
    }

    /**
     * @param $table
     * @param $data
     * @return bool
     */
    public function insert($table, $data): bool
    {
        $status = true;
        try {
            $this->em->getConnection()->insert($table, $data);
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->logError(__CLASS__, __METHOD__, $e->getMessage());
            $status = false;
        }
        return $status;
    }

    /**
     * @return bool
     */
    public function getStatus() : bool
    {
        return $this->status;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getDateTime(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * @param string
     * @return string
     */
    public function filterName(string $name): string
    {
        $name = strtoupper($name);

        $filter = new FilterChain();

        $filter->attachByName('StringTrim');
        $filter->attachByName('StripNewlines');
        $filter->attachByName('StripTags');

        return $filter->filter($name);
    }
}
