<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/31/12
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */
namespace TestStackExample\Api\V1\TestStack\Dispatcher;

use TestStackExample\Api\V1\TestStack\Modules\GatewayModule;

class DispatcherHttp
    extends \Processus\Rpc\Json\Dispatcher\Dispatcher
{

    /**
     * @return GatewayModule
     */
    public function newGateway()
    {
        $gateway = new GatewayModule();
        $gateway->init();

        return $gateway;
    }

}
