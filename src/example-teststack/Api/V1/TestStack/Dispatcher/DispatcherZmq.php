<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 6:26 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Api\V1\TestStack\Dispatcher;

use Api\V1\TestStack\Modules\GatewayModule;

class DispatcherZmq
    extends \Processus\JsonRpc\Dispatcher\Zmq\DispatcherZmq
{


    /**
     * @return \Api\V1\TestStack\Modules\GatewayModule
     */
    public function newGateway()
    {
        $gateway = new GatewayModule();
        $gateway->init();

        return $gateway;
    }


}
