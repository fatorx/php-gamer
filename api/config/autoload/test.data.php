<?php

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * NOTE: This file is ignored from Git by default with the .gitignore included
 * in laminas-mvc-skeleton. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

use Application\Util\Environment;

return [
    'app' => [
        'app_key' => Environment::env('APP_KEY'),
        'app_username' => Environment::env('APP_USERNAME'),
        'app_password' => Environment::env('APP_PASSWORD'),
    ],
];
