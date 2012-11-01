<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/19/12
 * Time: 1:57 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Server;

use Processus\Rpc\Json\Modules\Base\BaseModule;
use Processus\Rpc\Json\Base\RpcUtil;

class ServerModule extends BaseModule
{

    /**
     * @var Adapter\Zend\Server
     */
    private $_zendServer;


    /**
     * @return ServerModule
     */
    public function handleRequest()
    {
        $useZend = false; // zend is slow!

        $result = $this;

        // zend is slow!
        if ($useZend) {
            $this->_handleZend();

            return $result;
        }

        $rpc = $this->getRpc();

        $rpcRequest  = $rpc->getRequest();
        $rpcResponse = $rpc->getResponse();

        try {
            $class  = $rpc->getRouterServiceQualifiedName();
            $method = $rpc->getRouterServiceMethodName();
            $params = $rpc->getRouterServiceMethodParams();
            if (!is_array($params)) {
                $params = array();
            }

            $classExists = RpcUtil::classExists($class, true, false);
            if (!$classExists) {
                $e = $this->newModuleException();
                $e->setMessage(
                    $e::ERROR_SERVERMODULE_SERVICECLASS_NOT_EXIST
                );
                $e->setMethodInfo($this, __METHOD__, __LINE__);
                $e->setDebugData(
                    array(
                        'className'  => $class,
                        'methodName' => $method,
                    )
                );

                throw $e;
            }

            $reflectionClass = new \ReflectionClass(
                $class
            );
            if (
                (!$reflectionClass->isInstantiable())
                || ($reflectionClass->isAbstract())
            ) {
                $e = $this->newModuleException();
                $e->setMessage(
                    $e::ERROR_SERVERMODULE_SERVICECLASS_NOT_INVOCABLE
                );
                $e->setMethodInfo($this, __METHOD__, __LINE__);
                $e->setDebugData(
                    array(
                        'className'  => $class,
                        'methodName' => $method,
                    )
                );

                throw $e;

            }

            if (!$reflectionClass->hasMethod((string)$method)) {
                $e = $this->newModuleException();
                $e->setMessage(
                    $e::ERROR_SERVERMODULE_SERVICEMETHOD_NOT_EXISTS
                );
                $e->setMethodInfo($this, __METHOD__, __LINE__);
                $e->setDebugData(
                    array(
                        'className'  => $class,
                        'methodName' => $method,
                    )
                );

                throw $e;
            }

            $reflectionMethod = $reflectionClass->getMethod($method);

            if (
                (!$reflectionMethod->isPublic())
                || ($reflectionMethod->isAbstract())
                || ($reflectionMethod->isStatic())
            ) {
                $e = $this->newModuleException();
                $e->setMessage(
                    $e::ERROR_SERVERMODULE_SERVICEMETHOD_NOT_INVOCABLE
                );
                $e->setMethodInfo($this, __METHOD__, __LINE__);
                $e->setDebugData(
                    array(
                        'className'  => $class,
                        'methodName' => $method,
                    )
                );

                throw $e;
            }

            $className       = $reflectionClass->getName();
            $serviceInstance = $this->_newServiceInstance($className);
            $this->_invokeServiceMethod(
                $reflectionMethod,
                $serviceInstance,
                $params
            );

        } catch (\Exception $e) {
            $rpcResponse->setException($e);
        }

        return $result;
    }

    /**
     * @param $className
     * @return mixed
     */
    protected function _newServiceInstance($className)
    {
        $instance = new $className();

        return $instance;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param $serviceInstance
     * @param $methodParams
     * @return ServerModule
     */
    protected function _invokeServiceMethod(
        \ReflectionMethod $reflectionMethod,
        $serviceInstance,
        $methodParams
    ) {

        $rpc         = $this->getRpc();
        $rpcResponse = $rpc->getResponse();
        if (!is_array($methodParams)) {
            $methodParams = array();
        }

        try {
            $rpcResult = $reflectionMethod->invokeArgs(
                $serviceInstance,
                $methodParams
            );
            $rpcResponse->setResult($rpcResult);
        } catch (\Exception $e) {
            $rpcResponse->setException($e);
        }

        return $this;
    }


    /**
     * @return ServerModule
     */
    private function _handleZend()
    {

        $rpc = $this->getRpc();

        $rpcRequest  = $rpc->getRequest();
        $rpcResponse = $rpc->getResponse();

        if (!($this->_zendServer instanceof Adapter\Zend\Server)) {
            $this->_zendServer = new Adapter\Zend\Server();
        }
        $zendServer = $this->_zendServer;
        $zendServer->unsetRequest();
        $zendServer->unsetResponse();
        $zendServer->setAutoEmitResponse(false);

        try {
            $rpcId      = $rpcRequest->getId();
            $rpcVersion = $rpcRequest->getVersion();
            $rpcJsonrpc = $rpcRequest->getJsonrpc();
            $rpcMethod  = $rpcRequest->getMethod();
            if (!is_string($rpcMethod)) {
                $rpcMethod = '';
            }
            $rpcParams = $rpcRequest->getParams();
            if (!is_array($rpcParams)) {
                $rpcParams = array();
            }

            $phpClass  = $rpc->getRouterServiceQualifiedName();
            $phpMethod = $rpc->getRouterServiceMethodName();

            $zendServer->setClass($phpClass);

            $zendOptions = array(
                'id'      => $rpcId,
                'version' => $rpcVersion,
                'jsonrpc' => $rpcJsonrpc,
                'method'  => $phpMethod,
                'params'  => $rpcParams,
            );

            $zendServer->getRequest()
                ->setOptions($zendOptions);
            $zendServer->handle();
            $rpcResponse->setResult(
                $zendServer->getResponse()->getResult()
            );
        } catch (\Exception $e) {
            $rpcResponse->setException($e);
        }

        return $this;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return ServerModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {

        $exception = new ServerModuleException($message, $code, $previous);

        return $exception;
    }


}
