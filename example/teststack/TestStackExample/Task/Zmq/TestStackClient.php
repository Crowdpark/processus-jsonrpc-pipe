<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 6:51 PM
 * To change this template use File | Settings | File Templates.
 */

namespace TestStackExample\Task\Zmq;

use TestStackExample\Task\AbstractTask;

class TestStackClient
    extends AbstractTask
{

    // php runtask.php Zmq.TestStackClient

    /**
     *
     */
    public function run()
    {
        $batchItemCount = 10;
        $uri            = 'tcp://127.0.0.1:5556';
        $rpcData        = array(
            "id"     => 1,
            "method" => 'TestStack.Test.ping',
            "params" => array(),
        );

        /* Create new queue object */
        $queue = new \ZMQSocket(
            new \ZMQContext(), \ZMQ::SOCKET_PUSH, "MySock1"
        );
        $queue->connect($uri);

        $rpcBatch = array();
        for ($i = 0; $i < $batchItemCount; $i++) {
            $rpcData       = (array)$rpcData;
            $rpcData['id'] = $i;
            $rpcBatch[]    = $rpcData;
        }
        $mqData = $rpcBatch;

        /* Assign socket 1 to the queue, send and receive */
        $queue->send(json_encode($mqData), \ZMQ::MODE_NOBLOCK);
    }


}
