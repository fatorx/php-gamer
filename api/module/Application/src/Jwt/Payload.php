<?php

namespace Application\Jwt;

class Payload
{
    private string $sub;

    private string $name;

    private bool $admin;

    private string $issuedAt;

    private string $expiration;

    private string $audience;

    private $connection;

    private $game;

    /**
     * @param object $data
     */
    public function __construct(\stdClass $data)
    {
        $this->sub        = isset($data->sub) ? $data->sub : '';
        $this->name       = isset($data->name) ? $data->name : '';
        $this->admin      = isset($data->admin) ? $data->admin : false;
        $this->issuedAt   = isset($data->issued_at) ? $data->issued_at : '';
        $this->expiration = isset($data->expiration) ? $data->expiration : '';
        $this->audience   = isset($data->audience) ? $data->audience : '';
        $this->connection = isset($data->connection) ? $data->connection : 0;
        $this->game       = isset($data->game) ? $data->game : 0;
    }

    /**
     * Get the value of sub
     */
    public function getSub(): string
    {
        return $this->sub;
    }

    /**
     * Set the value of sub
     *
     * @return  self
     */
    public function setSub($sub)
    {
        $this->sub = $sub;
        return $this;
    }



    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get the value of issuedAt
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set the value of issuedAt
     *
     * @return  self
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get the value of expiration
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * Set the value of expiration
     *
     * @return  self
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get the value of audience
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * Set the value of audience
     *
     * @return  self
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return Payload
     */
    public function setConnection(string $connection): Payload
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return int
     */
    public function getGame(): int
    {
        return $this->game;
    }

    /**
     * @param int $game
     * @return Payload
     */
    public function setGame(int $game): Payload
    {
        $this->game = $game;
        return $this;
    }
}
