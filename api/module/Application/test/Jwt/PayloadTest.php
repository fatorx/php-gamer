<?php

declare(strict_types=1);

namespace ApplicationTest\Jwt;

use PHPUnit\Framework\TestCase;
use Application\Jwt\Payload;

class PayloadTest extends TestCase
{
    private $pars = null;

    public function setUp() : void
    {
        parent::setUp();

        $this->pars = new \stdClass();
        $this->pars->sub   = 16;
        $this->pars->name  = "Fatorx";
        $this->pars->admin = false;
        $this->pars->issued_at  = "";
        $this->pars->expiration  = "";
        $this->pars->audience    = "";

    }

    public function testClassHasAttributtes()
    {
        $payload = new Payload($this->pars);
        $name = 'John';
        $payload->setName($name);
        $this->assertEquals($name, $payload->getName());
    }

    public function testSetGetSub()
    {
        $payload = new Payload($this->pars);
        $admin = false;
        $payload->setSub($admin);
        $this->assertEquals($admin, $payload->getSub());
    }

    public function testSetGetAdmin()
    {
        $payload = new Payload($this->pars);
        $admin = false;
        $payload->setAdmin($admin);
        $this->assertEquals($admin, $payload->getAdmin());
    }

    public function testSetGetIssueAt()
    {
        $payload = new Payload($this->pars);
        $issueAt = '2020-03-06';
        $payload->setIssuedAt($issueAt);
        $this->assertEquals($issueAt, $payload->getIssuedAt());
    }

    public function testSetGetExpiration()
    {
        $payload = new Payload($this->pars);
        $expiration = '2020-03-06';
        $payload->setExpiration($expiration);
        $this->assertEquals($expiration, $payload->getExpiration());
    }

    public function testSetGetAudience()
    {
        $payload = new Payload($this->pars);
        $audience = 'app';
        $payload->setAudience($audience);
        $this->assertEquals($audience, $payload->getAudience());
    }
}
