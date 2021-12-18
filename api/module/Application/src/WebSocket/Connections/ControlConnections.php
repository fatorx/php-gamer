<?php

namespace Application\WebSocket\Connections;

class ControlConnections
{
    private array $connections = [];
    private int $gameId = 0;

    /**
     * @return array
     */
    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * @param $workerId
     * @param mixed $connection
     * @return ControlConnections
     */
    public function addConnection($workerId, mixed $connection): ControlConnections
    {
        $this->connections[$workerId] = $connection;
        return $this;
    }

    /**
     * @param array $connections
     * @return ControlConnections
     */
    public function setConnections(array $connections): ControlConnections
    {
        $this->connections = $connections;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return ControlConnections
     */
    public function setGameId(int $gameId): ControlConnections
    {
        $this->gameId = $gameId;
        return $this;
    }
}
