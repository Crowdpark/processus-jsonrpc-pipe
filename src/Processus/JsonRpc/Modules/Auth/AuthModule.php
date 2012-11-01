<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 11:50 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Auth;

use Processus\JsonRpc\Modules\Base\BaseModule;

class AuthModule
    extends BaseModule
{
    /**
     * @var bool
     */
    protected $_isLoggedIn = false;

    /**
     * @var bool
     */
    protected $_isLoginRequired = true;

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return ($this->_isEnabled === true);
    }

    /**
     * @param bool $enabled
     * @return AuthModule
     */
    public function setIsEnabled($enabled)
    {
        $this->_isEnabled = ($enabled === true);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsLoggedIn()
    {

        return ($this->_isLoggedIn === true);
    }

    /**
     * @param bool $isLoggedIn
     * @return AuthModule
     */
    public function setIsLoggedIn($isLoggedIn)
    {
        $this->_isLoggedIn = ($isLoggedIn === true);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsLoginRequired()
    {

        return ($this->_isLoginRequired === true);
    }

    /**
     * @param bool $isRequired
     * @return AuthModule
     */
    public function setIsLoginRequired($isRequired)
    {
        $this->_isLoginRequired = ($isRequired === true);

        return $this;
    }


    /**
     * @return AuthModule
     */
    public function init()
    {

        return $this;
    }

    /**
     * @return AuthModule
     */
    public function handleRequest()
    {

        $result = $this;
        if (!$this->getIsEnabled()) {

            return $result;
        }

        $this->_authenticate();
        $isLoggedIn = $this->getIsLoggedIn();

        // is not logged in?
        if (!$isLoggedIn) {
            // login required?
            if ($this->getIsLoginRequired()) {
                $e = $this->newModuleException();
                $e->setMessage($e::ERROR_LOGIN_REQUIRED);
                $e->setMethodInfo($this, __METHOD__, __LINE__);

                throw $e;
            }
        }

        return $result;
    }

    /**
     * @return AuthModule
     * @throws \Exception
     */
    private function _authenticate()
    {
        $result = $this;
        if (!$this->getIsEnabled()) {

            return $result;
        }

        $rpc     = $this->getRpc();
        $request = $rpc->getRequest();

        // implement auth algorithm here in subclass

        return $this;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return AuthModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new AuthModuleException(
            (string)$message, (int)$code, $previous
        );

        return $exception;
    }
}
