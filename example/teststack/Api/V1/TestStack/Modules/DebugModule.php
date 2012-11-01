<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Api\V1\TestStack\Modules;
use Processus\Rpc\Json\Base\RpcUtil;

class DebugModule
    extends
    \Processus\Rpc\Json\Modules\Debug\DebugModule
{


    /**
     * @return DebugModule
     */
    public function handleResponseReadyData()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $rpc       = $this->getRpc();
        $response  = $rpc->getResponse();
        $readyData = $response->getReadyData();

        // readyData.debug
        if (!$this->getIsDebugEnabled()) {
            $response->unsetReadyDataKey('debug');
        } else {
            $readyDataDebug = $response->getReadyDataKey('debug');
            $readyDataDebug = RpcUtil::arrayEnsure(
                $readyDataDebug,
                array(
                    'demoDebugModuleMessage' => 'foo ' . __METHOD__,
                    'isDebugEnabled'         => true,
                    'rpc'                    => array(
                        'request'      => $rpc->getRequest()->getData(),
                        'result'       => $rpc->getResponse()->getResult(),
                        'hasException' => $rpc->getResponse()->hasException(),
                    ),
                )
            );
            if ($response->hasException()) {
                $readyDataDebug['errorInfo'] = RpcUtil::exceptionAsArray(
                    $response->getException(),
                    true
                );
            }
            $response->setReadyDataKey('debug', $readyDataDebug);
        }

        return $result;
    }

}
