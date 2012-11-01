<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 4:33 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Dispatcher;

use Processus\Rpc\Json\Modules\Gateway\GatewayModule;

class Dispatcher
{
    /**
     * @var bool
     */
    protected $_isInitialized;
    /**
     * @var GatewayModule
     */
    protected $_gateway;

    /**
     * @var \Exception|null
     */
    protected $_lastException;

    /**
     * @return Dispatcher
     */
    public function init()
    {
        $gateway = $this->getGateway();
        $gateway->setIsAutoEmitResponseEnabled(true);
        $gateway->setIsAutoFetchRequestTextEnabled(true);
        $gateway->setIsDebugEnabled(false);

        return $this;
    }

    /**
     * @return Dispatcher
     */
    public function run()
    {
        $this->_lastException = null;
        $this->_requireIsInitialized();

        try {
            $this->getGateway()
                ->run();
        } catch (\Exception $e) {
            $this->_lastException = $e;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsInitialized()
    {
        return ($this->_isInitialized === true);
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    protected function _onError(\Exception $exception)
    {
        // log/ dump / rethrow ?

        throw $exception;
    }

    /**
     * @return Dispatcher
     * @throws DispatcherException
     */
    protected function _requireIsInitialized()
    {
        $result = $this;
        if ($this->getIsInitialized()) {

            return $result;
        }

        $e = $this->newException();
        $e->setMessage($e::ERROR_DISPATCHER_NOT_INITIALIZED);
        $e->setMethodInfo($this, __METHOD__, __LINE__);

        throw $e;
    }

    /**
     * @return \Exception|null
     */
    public function getLastException()
    {
        return $this->_lastException;
    }

    /**
     * @return bool
     */
    public function hasLastException()
    {
        return ($this->_lastException instanceof \Exception);
    }


    // ============ factory: Exception ==================

    /**
     * @param string $message
     * @param int $code
     * @param null $previous
     * @return DispatcherException
     */
    public function newException($message = '', $code = 0, $previous = null)
    {
        $e = new DispatcherException((string)$message, (int)$code, $previous);

        return $e;
    }


    // ============ factory: gateway ==================

    /**
     * @return GatewayModule
     */
    public function newGateway()
    {
        $gateway = new GatewayModule();
        $gateway->init();

        return $gateway;
    }

    /**
     * @return GatewayModule
     */
    public function getGateway()
    {
        if (!($this->_gateway instanceof GatewayModule)) {
            $gateway = $this->newGateway();

            $this->_gateway = $gateway;
        }

        return $this->_gateway;
    }

    /**
     * @param GatewayModule $gateway
     * @return Dispatcher
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
     * @return Dispatcher
     */
    public function unsetGateway()
    {
        $this->_gateway = null;

        return $this;
    }

}
