<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Debug;

use Processus\Rpc\Json\Modules\Base\BaseModule;
use Processus\Rpc\Json\Base\RpcUtil;

class DebugModule extends BaseModule
{

    /**
     * @return bool
     */
    public function getIsDebugEnabled()
    {
        $rpc = $this->getRpc();

        return ($rpc->getGateway()->getIsDebugEnabled() === true);
    }

    /**
     * @return DebugModule
     */
    public function handleRequest()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        if (!$this->getIsDebugEnabled()) {

            return $result;
        }

        return $result;
    }

    /**
     * @return DebugModule
     */
    public function handleResponse()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        if (!$this->getIsDebugEnabled()) {

            return $result;
        }

        return $result;
    }

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
            // you may want to add debug info to rpc.response
            /* EXAMPLE
            $readyDataDebug = $response->getReadyDataKey('debug');
            $readyDataDebug = RpcUtil::arrayEnsure(
                $readyDataDebug,
                array(
                    'demoDebugModuleMessage' => 'foo' . __METHOD__,
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
            */
        }

        return $result;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return DebugModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new DebugModuleException($message, $code, $previous);

        return $exception;
    }


}
