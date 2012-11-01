<?php

namespace Processus\Rpc\Json\Base;

use Processus\Rpc\Json\Modules\Gateway\GatewayModule;

class Rpc
{

    /**
     * @var $request RpcRequestVo
     */
    protected $_request;
    /**
     * @var $response RpcResponseVo
     */
    protected $_response;

    /**
     * @var string
     */
    protected $_routerServiceClassQualifiedName = '';

    /**
     * @var string
     */
    protected $_routerServiceMethodName = '';

    /**
     * @var array
     */
    protected $_routerServiceMethodParams = array();

    /**
     * @var ServiceInfoVo
     */
    protected $_routerServiceInfo;

    /**
     * @var GatewayModule|null
     */
    protected $_gateway;

    /**
     * @return GatewayModule|null
     */
    public function getGateway()
    {
        return $this->_gateway;
    }

    /**
     * @param GatewayModule $gateway
     * @return Rpc
     */
    public function setGateway(GatewayModule $gateway)
    {
        $this->_gateway = $gateway;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasGateway()
    {
        return ($this->_gateway instanceof GatewayModule);
    }

    /**
     * @return Rpc
     */
    public function unsetGateway()
    {
        $this->_gateway = null;

        return $this;
    }


    /**
     * @return RpcRequestVo
     */
    public function newRequest()
    {
        $request = new RpcRequestVo();

        return $request;
    }

    /**
     * @return RpcResponseVo
     */
    public function newResponse()
    {
        $response = new RpcResponseVo();

        return $response;
    }

    /**
     * @return RpcRequestVo
     */
    public function getRequest()
    {
        if (!($this->_request instanceof RpcRequestVo)) {
            $this->_request = $this->newRequest();
        }

        return $this->_request;
    }

    /**
     * @return RpcResponseVo
     */
    public function getResponse()
    {
        if (!($this->_response instanceof RpcResponseVo)) {
            $this->_response = $this->newResponse();
        }

        return $this->_response;
    }

    /**
     * @param RpcRequestVo $request
     * @return Rpc
     */
    public function setRequest(RpcRequestVo $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * @param RpcResponseVo $response
     * @return Rpc
     */
    public function setResponse(RpcResponseVo $response)
    {
        $this->_response = $response;

        return $this;
    }

    /**
     * @return Rpc
     */
    public function init()
    {
        return $this;
    }

    /**
     * @param string $name
     */
    public function setRouterServiceQualifiedName($className)
    {
        $this->_routerServiceClassQualifiedName = (string)$className;
    }

    /**
     * @return string
     */
    public function getRouterServiceQualifiedName()
    {

        return (string)$this->_routerServiceClassQualifiedName;
    }

    /**
     * @param string $methodName
     * @return Rpc
     */
    public function setRouterServiceMethodName($methodName)
    {
        $this->_routerServiceMethodName = $methodName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouterServiceMethodName()
    {
        return (string)$this->_routerServiceMethodName;
    }

    /**
     * @return array|mixed
     */
    public function getRouterServiceMethodParams()
    {
        return $this->_routerServiceMethodParams;
    }

    /**
     * @param $params
     * @return Rpc
     */
    public function setRouterServiceMethodParams($params)
    {
        $this->_routerServiceMethodParams = $params;

        return $this;
    }

    /**
     * @param ServiceInfoVo $serviceInfo
     * @return Rpc
     */
    public function setRouterServiceInfo(ServiceInfoVo $serviceInfo)
    {
        $this->_routerServiceInfo = $serviceInfo;

        return $this;
    }

    /**
     * @return ServiceInfoVo
     */
    public function getRouterServiceInfo()
    {

        return $this->_routerServiceInfo;
    }

    /**
     * @return bool
     */
    public function hasRouterServiceInfo()
    {

        return ($this->_routerServiceInfo instanceof ServiceInfoVo);
    }

    /**
     * @return Rpc
     */
    public function unsetRouterServiceInfo()
    {
        $this->_routerServiceInfo = null;

        return $this;
    }

}


