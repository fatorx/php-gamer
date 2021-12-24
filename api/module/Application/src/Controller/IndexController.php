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
        $dateTime = $this->getDateTime();
        // code
        $data = [
            'action'  => 'index',
            'message' => 'root',
            'datetime_action' => $dateTime
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function pingAction(): JsonModel
    {
        $dateTime = $this->getDateTime();
        // code
        $data = [
            'action'  => 'ping',
            'message' => 'pong',
            'datetime_action' => $dateTime
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function postPingAction(): JsonModel
    {
        $dateTime = $this->getDateTime();
        // code

        $dataParameters = $this->getJsonParameters();
        $dateExit = $this->getDateTime();

        $data = [
            'action' => 'post-ping',
            'message' => 'post pong : ' . json_encode($dataParameters),
            'data' => $dataParameters,
            'datetime_action' => $dateTime
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
            'message' => 'Server timer: ' . $times,
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
        $times = 1000;
        $list  = [];
        for($i = 0; $i < $times; ++$i) {
            $list[] = $i;
        }
        $dateTimeExit = $this->getDateTime();
        $data = [
            'action'  => 'timer',
            'message' => $list,
            'datetime_action' => $dateTime,
            'datetime_exit'   => $dateTimeExit,
        ];

        return $this->createResponse($data);
    }
}
