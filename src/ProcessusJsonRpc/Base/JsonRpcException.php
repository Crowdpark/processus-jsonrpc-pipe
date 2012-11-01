<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/18/12
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Base;

class JsonRpcException extends \Exception
{
    const ERROR_DEPRECATED_METHOD_CALL = 'ERROR_DEPRECATED_METHOD_CALL';

    /**
     * exposed to client
     * @var array;
     */
    protected $_data = array();

    /**
     * usually not to be exposed to the client (just in debug mode)
     * @var string
     */
    protected $_method = '';
    /**
     * usually not to be exposed to the client (just in debug mode)
     * @var int
     */
    protected $_methodLine = 0;

    /**
     * usually not to be exposed to the client (just in debug mode)
     * @var string
     */
    protected $_methodClass = '';

    /**
     * usually not to be exposed to the client (just in debug mode)
     * @var \Exception|null
     */
    protected $_fault;

    /**
     * usually not to be exposed to the client (just in debug mode)
     * @var array
     */
    protected $_debugData = array();


    /**
     * @param \Exception $exception
     * @return JsonRpcException
     */
    public function setFault(\Exception $exception)
    {
        $this->_fault = $exception;

        return $this;
    }

    /**
     * @return \Exception|null
     */
    public function getFault()
    {

        return $this->_fault;
    }

    /**
     * @return bool
     */
    public function hasFault()
    {

        return ($this->getFault() instanceof \Exception);
    }

    /**
     * @return JsonRpcException
     */
    public function unsetFault()
    {

        $this->_fault = null;

        return $this;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = (string)$message;

        return $this;
    }

    /**
     * @param array $data
     * @return JsonRpcException
     */
    public function setData(array $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $this->_data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!is_array($this->_data)) {
            $this->_data = array();
        }

        return $this->_data;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        $data = $this->getData();

        return (
            is_array($data)
                && (count(array_keys($data)) > 0)
        );
    }


    /**
     * @param array $debugData
     * @return JsonRpcException
     */
    public function setDebugData(array $debugData)
    {
        if (!is_array($debugData)) {
            $debugData = array();
        }
        $this->_debugData = $debugData;

        return $this;
    }

    /**
     * @return array
     */
    public function getDebugData()
    {
        if (!is_array($this->_debugData)) {
            $this->_debugData = array();
        }

        return $this->_debugData;
    }

    /**
     * @return bool
     */
    public function hasDebugData()
    {
        $debugData = $this->getdebugData();

        return (
            is_array($debugData)
                && (count(array_keys($debugData)) > 0)
        );
    }


    /**
     * @param string $method
     * @return JsonRpcException
     */
    public function setMethod($method)
    {
        $this->_method = (string)$method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {

        return (string)$this->_method;
    }

    /**
     * @return bool
     */
    public function hasMethod()
    {
        $method = $this->getMethod();

        return (
            (is_string($method))
                && (!empty($method))
        );
    }


    /**
     * @param int $methodLine
     * @return JsonRpcException
     */
    public function setMethodLine($methodLine)
    {
        $this->_methodLine = (int)$methodLine;

        return $this;
    }

    /**
     * @return int
     */
    public function getMethodLine()
    {

        return (int)$this->_methodLine;
    }

    /**
     * @return bool
     */
    public function hasMethodLine()
    {
        $methodLine = $this->getMethodLine();

        return (
            (is_int($methodLine))
                && ($methodLine > 0)
        );
    }

    /**
     * @param string|object $class
     * @return JsonRpcException
     */
    public function setMethodClass($class)
    {
        if (is_object($class)) {
            try {
                $class = get_class($class);
            } catch (\Exception $e) {
                // NOP
            }
        }

        if (!is_string($class)) {
            $class = '';
        }

        $this->_methodClass = (string)$class;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethodClass()
    {

        return (string)$this->_methodClass;
    }

    /**
     * @return bool
     */
    public function hasMethodClass()
    {
        $methodClass = $this->getMethodClass();

        return (
            (is_string($methodClass))
                && (!empty($methodClass))
        );
    }

    /**
     * @param string|object $class
     * @param string $method
     * @param int $line
     * @return JsonRpcException
     */
    public function setMethodInfo($class, $method, $line)
    {
        $this->setMethodClass($class);
        $this->setMethod($method);
        $this->setMethodLine($line);

        return $this;
    }

}
