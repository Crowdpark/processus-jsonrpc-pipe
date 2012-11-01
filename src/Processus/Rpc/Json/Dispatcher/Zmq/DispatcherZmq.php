<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 4:30 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Dispatcher\Zmq;

use Processus\Rpc\Json\Dispatcher\Dispatcher;
use Processus\Rpc\Json\Base\RpcUtil;

class DispatcherZmq extends Dispatcher
{

    /**
     * @var string
     */
    protected $_uri = 'tcp://0.0.0.0:5555';
    /**
     * @var \ZMQSocket
     */
    protected $_socket;

    /**
     * @var string
     */
    protected $_currentRequestText;

    /**
     * @var bool
     */
    protected $_isDebugEnabled;

    /**
     * @override
     * @return Dispatcher
     */
    public function init()
    {
        $this->_isInitialized = true;

        return $this;
    }

    /**
     * @override
     * @return DispatcherZmq
     */
    public function run()
    {
        try {
            $this->_lastException = null;
            $this->_requireIsInitialized();
            $this->_requireZmq();
            $socket = $this->getSocket();

            if (!$this->hasUri()) {

                $e = $this->newException();
                $e->setMessage($e::ERROR_ZMQ_SOCKET_URI_INVALID);
                $e->setMethodInfo($this, __METHOD__, __LINE__);
                $e->setDebugData(
                    array(
                        'uri' => $this->getUri(),
                    )
                );

                throw $e;
            }

            echo PHP_EOL
                . " ==== ZMQ TRY BIND TO URI: "
                . $this->getUri()
                . " === "
                . PHP_EOL;


            $socket->bind($this->getUri());
            echo PHP_EOL . " ==== LISTENING ... ===" . PHP_EOL;

            while (true) {
                //echo 1;
                usleep(10000);
                //echo 2;
                try {
                    $message = '' . $socket->recv(\ZMQ::MODE_NOBLOCK);
                    if (empty($message)) {

                        continue;
                    }
                    $this->_currentRequestText = $message;
                    $this->_handleRequest();
                } catch (\Exception $e) {
                    $this->_lastException = $e;
                    $this->_onHandleRequestError($e);
                }
                $this->unsetGateway();
            }

        } catch (\Exception $e) {
            $this->_lastException = $e;
            $this->_onError($e);
        }

        return $this;
    }

    /**
     * @return DispatcherZmq
     */
    protected function _handleRequest()
    {
        $result = $this;

        if (!$this->hasCurrentRequestText()) {

            return $result;
        }

        $requestText = $this->getCurrentRequestText();

        $startTS = microtime(true);
        $this->unsetGateway();
        $gateway = $this->getGateway();
        $gateway->init();
        $gateway->setIsAutoEmitResponseEnabled(false);
        $gateway->setIsAutoFetchRequestTextEnabled(false);
        $gateway->setIsDebugEnabled($this->getIsDebugEnabled());
        $requestData = RpcUtil::jsonDecode($requestText, true, false);
        $gateway->setRawRequestData($requestData);
        $gateway->run();
        $stopTS       = microtime(true);
        $durationTS   = $stopTS - $startTS;
        $responseText = $gateway->getRawResponseText();

        echo PHP_EOL . " ==== GATEWAY RESPONSE:BEGIN ===" . PHP_EOL;
        echo $responseText;
        echo PHP_EOL . " ==== GATEWAY RESPONSE:END ===" . PHP_EOL;
        echo PHP_EOL . " ==== GATEWAY PROFILER:BEGIN ===" . PHP_EOL;

        var_dump(
            array(
                'duration' => $durationTS
            )
        );
        echo PHP_EOL . " ==== GATEWAY PROFILER:END ===" . PHP_EOL;

        return $result;

    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    protected function _onHandleRequestError(\Exception $exception)
    {
        echo PHP_EOL . " ==== ON HANDLE REQUEST ERROR:BEGIN ===" . PHP_EOL;

        echo $exception->getMessage();

        echo PHP_EOL . " ==== ON HANDLE REQUEST ERROR:END ===" . PHP_EOL;

        return $this;
    }

    /**
     * @param \Exception $exception
     * @return DispatcherZmq|void
     */
    protected function _onError(\Exception $exception)
    {
        echo PHP_EOL . " ==== DISPATCHER ERROR:BEGIN ===" . PHP_EOL;

        echo $exception->getMessage();

        echo PHP_EOL . " ==== DISPATCHER ERROR:END ===" . PHP_EOL;

        return $this;
    }


    /**
     * @return DispatcherZmq
     * @throws DispatcherZmqException
     */
    protected function _requireZmq()
    {
        $result = $this;

        if (!RpcUtil::classExists('\ZMQSocket', true, false)) {
            $e = $this->newException();
            $e->setMessage($e::ERROR_ZMQ_EXTENSION_NOT_FOUND);
            $e->setMethodInfo($this, __METHOD__, __LINE__);

            throw $e;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getCurrentRequestText()
    {
        return (string)$this->_currentRequestText;
    }

    /**
     * @return bool
     */
    public function hasCurrentRequestText()
    {

        $text = $this->getCurrentRequestText();

        return ((is_string($text)) && (!empty($text)));
    }

    /**
     * @param string $uri
     * @return DispatcherZmq
     */
    public function setUri($uri)
    {
        $this->_uri = (string)$uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return (string)$this->_uri;
    }

    /**
     * @return bool
     */
    public function hasUri()
    {
        $uri = $this->getUri();

        return ((is_string($uri)) && (!empty($uri)));
    }

    /**
     * @return bool
     */
    public function getIsDebugEnabled()
    {
        return ($this->_isDebugEnabled === true);
    }

    /**
     * @param bool $isEnabled
     * @return DispatcherZmq
     */
    public function setIsDebugEnabled($isEnabled)
    {
        $this->_isDebugEnabled = ($isEnabled === true);

        return $this;
    }

    // ============ factory: Exception ==================

    /**
     * @param string $message
     * @param int $code
     * @param null $previous
     * @return DispatcherZmqException
     */
    public function newException($message = '', $code = 0, $previous = null)
    {
        $e = new DispatcherZmqException(
            (string)$message, (int)$code, $previous
        );

        return $e;
    }


    // ============ factory: socket ==================

    /**
     * @return \ZMQSocket
     */
    public function newSocket()
    {
        $this->_requireZmq();

        $socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_PULL);

        return $socket;
    }

    /**
     * @return \ZMQSocket
     */
    public function getSocket()
    {
        if (!$this->hasSocket()) {
            $socket        = $this->newSocket();
            $this->_socket = $socket;
        }

        return $this->_socket;
    }

    /**
     * @param \ZMQSocket $socket
     * @return DispatcherZmq
     */
    public function setSocket($socket)
    {
        $this->_requireZmq();
        if (!$socket instanceof \ZMQSocket) {

            throw new \InvalidArgumentException(
                'Parameter Socket must be instanceof ZMQSocket'
            );
        }

        $this->_socket = $socket;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSocket()
    {
        $this->_requireZmq();

        return ($this->_socket instanceof \ZMQSocket);
    }

    /**
     * @return DispatcherZmq
     */
    public function unsetSocket()
    {
        $this->_socket = null;

        return $this;
    }


}
