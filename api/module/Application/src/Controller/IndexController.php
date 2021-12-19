<?php

namespace Application\Controller;

use Laminas\View\Model\JsonModel;


class IndexController extends ApiController
{
    /**
     * @return JsonModel
     */
    public function indexAction(): JsonModel
    {
        $data = [
            'token' => '',
            'message' => 'Logged in successfully.'
        ];
        return $this->createResponse($data);
    }
}
