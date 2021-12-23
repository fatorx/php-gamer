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
            'action'  => 'index',
            'message' => 'root',
            'datetime_action' => $this->getDateTime()
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function pingAction(): JsonModel
    {
        $data = [
            'action'  => 'ping',
            'message' => 'pong',
            'datetime_action' => $this->getDateTime()
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function postPingAction(): JsonModel
    {
        $dataParameters = $this->getJsonParameters();

        $data = [
            'action' => 'post-ping',
            'message' => 'post pong : ' . json_encode($dataParameters),
            'data' => $dataParameters,
            'datetime_action' => $this->getDateTime()
        ];

        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function timerAction(): JsonModel
    {
        $dateTime = $this->getDateTime();
        $times = 3;
        sleep($times);

        $data = [
            'action'  => 'timer',
            'message' => 'timer: ' . $times,
            'datetime_action' => $dateTime
        ];

        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function loopAction(): JsonModel
    {
        $dateTime = $this->getDateTime();
        $times = 100;
        $list  = [];
        for($i = 0; $i < $times; ++$i) {
            $list[] = $i;
        }

        $data = [
            'action'  => 'timer',
            'message' => $list,
            'datetime_action' => $dateTime
        ];

        return $this->createResponse($data);
    }

}
