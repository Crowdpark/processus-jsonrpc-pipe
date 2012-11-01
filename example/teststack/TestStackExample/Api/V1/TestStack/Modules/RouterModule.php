<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 2:38 PM
 * To change this template use File | Settings | File Templates.
 */
namespace TestStackExample\Api\V1\TestStack\Modules;

class RouterModule
    extends
    \Processus\Rpc\Json\Modules\Router\RouterModule
{

    /**
     * @var array
     */
    protected $_servicesList = array(
        // a service
        array(
            'serviceName' => 'TestStack.Test',
            'className'

                => 'TestStackExample\\Api\\V1\\TestStack\\Service\\Test',
            'isValidateMethodParamsEnabled' => true,
            'classMethodFilter'             => array(
                'allow' => array(
                    '*',
                ),
                'deny'  => array(
                    '*getApplicationContext',
                ),
            ),

        ),
        // another service

    );

    /**
     * @return RouterModule
     */
    public function findRequestParams()
    {
        $rpc = $this->getRpc();

        $rpcParams = $rpc->getRequest()->getParams();
        if (!is_array($rpcParams)) {
            $rpcParams = array();
        }

        $serviceMethodParams = $rpcParams;

        $rpc->setRouterServiceMethodParams($serviceMethodParams);

        return $this;
    }


}
