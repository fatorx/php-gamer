<?php
require_once(__DIR__ . '/../bootstrap.php');

$entityManager = getEntityManager();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);