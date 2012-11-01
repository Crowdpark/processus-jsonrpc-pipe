<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 6:56 PM
 * To change this template use File | Settings | File Templates.
 */
namespace TestStackExample\Task\Zmq;

use \TestStackExample\Task\AbstractTask;
use \TestStackExample\Api\V1\TestStack\Dispatcher\DispatcherZmq;

class TestStackServer
    extends AbstractTask
{

    // php runtask.php Zmq.TestStackServer

    /**
     * @throws \Exception|null
     */
    public function run()
    {
        $isDebugEnabled = true;
        $uri            = 'tcp://127.0.0.1:5556';

        $exception = null;
        $startTS   = microtime(true);
        try {
            $dispatcher = new DispatcherZmq();
            $dispatcher->init();
            $dispatcher->setUri($uri);
            $dispatcher->setIsDebugEnabled($isDebugEnabled === true);
            $dispatcher->run();
        } catch (\Exception $e) {
            $exception = $e;
        }
        $stopTS   = microtime(true);
        $duration = $stopTS - $startTS;

        var_dump(
            array(
                'method'   => __METHOD__,
                'duration' => $duration,
            )
        );


        if ($exception) {

            throw $exception;
        }
    }


}
