<?php

declare(strict_types=1);

$http = new Swoole\Http\Server("0.0.0.0", 9501);

$http->on(
    "request",
    function (Swoole\Http\Request $request, Swoole\Http\Response $response) {

        $str = <<<HEREDOC
                Hello, world!
               HEREDOC;

        $response->end(
            $str
        );
    }
);

$http->start();
