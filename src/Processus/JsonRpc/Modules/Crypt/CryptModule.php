<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 11:04 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Crypt;

use Processus\JsonRpc\Modules\Base\BaseModule;

class CryptModule extends BaseModule
{

    /**
     * @var bool
     */
    protected $_isEnabled = true;


    /**
     * @return CryptModule
     */
    public function handleRequest()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }
        $this->_decryptRequestData();

        return $result;
    }

    /**
     * @return CryptModule
     */
    public function handleResponse()
    {
        $result = $this;
        if (!$this->getIsEnabled()) {

            return $result;
        }
        $this->_encryptResponseReadyData();

        return $result;
    }


    /**
     * @return CryptModule
     */
    private function _decryptRequestData()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        // IMPLEMENT ALGORITHM IN SUBCLASSES

        $rpc     = $this->getRpc();
        $request = $rpc->getRequest();

        $requestData = $request->getData();

        // decrypt data keys ...

        $request->setData($requestData);

        return $result;
    }


    /**
     *
     * @return CryptModule
     */
    private function _encryptResponseReadyData()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        // IMPLEMENT ALGORITHM IN SUBCLASSES

        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();


        return $result;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return CryptModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new CryptModuleException(
            (string)$message, (int)$code, $previous
        );

        return $exception;
    }


}
