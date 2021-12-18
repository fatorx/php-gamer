<?php

namespace Application\Util;

class Environment
{
    /**
     * @param $name
     * @return mixed|string
     */
    public static function env($name)
    {
        return isset($_ENV[$name]) ? $_ENV[$name] : 'not-set';
    }
}
