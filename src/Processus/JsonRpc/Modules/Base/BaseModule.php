<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/19/12
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Base;

use Processus\JsonRpc\Base\Rpc;

class BaseModule
{
    /**
     * @var Rpc
     */
    protected $_rpc;

    /**
     * @var bool
     */
    protected $_isEnabled;

    /**
     * @var \ReflectionClass
     */
    protected $_reflectionClass;


    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return ($this->_isEnabled === true);
    }

    /**
     * @param bool $enabled
     * @return BaseModule
     */
    public function setIsEnabled($enabled)
    {
        $this->_isEnabled = ($enabled === true);

        return $this;
    }

    /**
     * @return BaseModule
     */
    public function init()
    {

        return $this;
    }

    /**
     * @param array $config
     * @return BaseModule
     */
    public function applyConfig($config)
    {

        $result = $this;

        if (!is_array($config)) {
            return $result;
        }
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'isEnabled':
                {
                    $this->setIsEnabled(($value === true));

                    break;
                }
                default:
                    break;
            }
        }

        return $result;
    }

    /**
     * @param Rpc $rpc
     * @return BaseModule
     */
    public function setRpc(Rpc $rpc)
    {
        $this->_rpc = $rpc;

        return $this;
    }

    /**
     * @return Rpc|null
     */
    public function getRpc()
    {

        return $this->_rpc;
    }

    /**
     * @return bool
     */
    public function hasRpc()
    {

        return ($this->getRpc() instanceof Rpc);
    }

    /**
     * @return BaseModule
     */
    public function unsetRpc()
    {
        $this->_rpc = null;

        return $this;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        if (!($this->_reflectionClass instanceof \ReflectionClass)) {
            $this->_reflectionClass = new \ReflectionClass($this);
        }

        return $this->_reflectionClass;
    }


    /**
     * @return bool
     */
    public function requireIsEnabled(
        $class = null,
        $method = null,
        $line = null,
        $debugData = null
    ) {
        $result = $this;

        if ($this->getIsEnabled()) {

            return $result;
        }

        $e = $this->newModuleException();
        $e->setMessage($e::ERROR_MODULE_NOT_ENABLED);
        if ($class === null) {
            $class = get_class($this);
        }
        if ($method === null) {
            $method = __METHOD__;
        }
        if ($line === null) {
            $line = __LINE__;
        }
        $e->setMethodInfo($class, $method, $line);
        if (is_array($debugData)) {
            $e->setDebugData($debugData);
        }

        return $result;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return BaseModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new BaseModuleException(
            (string)$message, (int)$code, $previous
        );

        return $exception;
    }

}
