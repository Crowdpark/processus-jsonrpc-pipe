<?php
/**
 * @EXPERIMENTAL
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/19/12
 * Time: 11:05 AM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Security;

use ProcessusJsonRpc\Modules\Base\BaseModule;
use ProcessusJsonRpc\Base\RpcUtil;

class SecurityModule extends BaseModule
{
    /**
     * @var string
     */
    private $_signatureRequestSecret = 'secret';
    /**
     * @var string
     */
    private $_signatureResponseSecret = 'secret';

    /**
     * @var string
     */
    private $_signatureSignRequestKeys = array(
        'method',
        'params',
    );
    /**
     * @var string
     */
    private $_signatureSignResponseKeys = array(
        'result',
    );

    /**
     * @var string
     */
    private $_signatureAlgorithm = 'HMAC-SHA256';

    /**
     * @return SecurityModule
     */
    public function handleRequest()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $this->validateRequestDataSignature();
        $this->checkRequestReplayAttack();
        $this->saveRequestToPreventReplayAttack();

        return $result;
    }

    /**
     * @return SecurityModule
     */
    public function handleResponse()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $this->signResponseReadyRawData();

        return $result;
    }

    /**
     * @return string
     */
    private function getSignatureRequestSecret()
    {
        return (string)$this->_signatureRequestSecret;
    }

    /**
     * @return string
     */
    private function getSignatureResponseSecret()
    {
        return (string)$this->_signatureResponseSecret;
    }

    /**
     * @return string
     */
    private function getSignatureAlgorithm()
    {
        return (string)$this->_signatureAlgorithm;
    }

    /**
     * @param $secret
     * @return SecurityModule
     */
    private function setSignatureRequestSecret($secret)
    {
        $this->_signatureRequestSecret = (string)$secret;

        return $this;
    }

    /**
     * @param $secret
     * @return SecurityModule
     */
    private function setSignatureResponseSecret($secret)
    {
        $this->_signatureResponseSecret = (string)$secret;

        return $this;
    }

    /**
     * @return array
     */
    private function getSignatureSignResponseKeys()
    {
        return (array)$this->_signatureSignResponseKeys;
    }

    /**
     * @return array
     */
    private function getSignatureSignRequestKeys()
    {
        return (array)$this->_signatureSignRequestKeys;
    }

    /**
     * @return array
     */
    private function signResponseReadyRawData()
    {
        $result = $this;

        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();


        $rawData = $response->getReadyData();

        $responseData = $rawData;

        if (!is_array($responseData)) {
            $responseData = array();
        }

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $signSecret    = $this->getSignatureRequestSecret();
        $signAlgorithm = $this->getSignatureAlgorithm();

        $signData = array();
        $signKeys = $this->getSignatureSignRequestKeys();
        foreach ($signKeys as $key) {
            $value = null;
            if (array_key_exists($responseData, $key)) {
                $value = $responseData[$key];
            }
            $signData[$key] = $value;
        }

        $signature = RpcUtil::
            createRequestSignature(
            $signData,
            $signKeys,
            $signSecret,
            $signAlgorithm,
            time()
        );

        $responseData['signature'] = $signature;

        $response->setReadyData($responseData);

        return $result;
    }


    /**
     *
     * @return SecurityModule
     * @throws \Exception
     */
    private function validateRequestDataSignature()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $rpc     = $this->getRpc();
        $request = $rpc->getRequest();

        $requestData = $request->getData();

        if (!is_array($requestData)) {
            $requestData = array();
        }
        $signatureGiven = $request->getDataKey('signature');

        $signSecret    = $this->getSignatureRequestSecret();
        $signAlgorithm = $this->getSignatureAlgorithm();

        $signData = array();
        $signKeys = $this->getSignatureSignRequestKeys();
        foreach ($signKeys as $key) {
            $value = null;
            if (array_key_exists($requestData, $key)) {
                $value = $requestData[$key];
            }
            $signData[$key] = $value;
        }


        $signatureIsValid = RpcUtil::
            validateSignedRequest(
            $signatureGiven,
            $signData,
            $signKeys,
            $signSecret,
            $signAlgorithm
        );

        if (!$signatureIsValid) {

            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_RPC_SIGNATURE_INVALID);
            $e->setMethodInfo($this, __METHOD__, __LINE__);

            throw $e;
        }

        return $result;
    }


    /**
     * @return SecurityModule
     */
    private function checkRequestReplayAttack()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        return $result;
    }

    /**
     * @return SecurityModule
     */
    private function saveRequestToPreventReplayAttack()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        return $result;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return SecurityModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {

        $exception = new SecurityModuleException($message, $code, $previous);

        return $exception;
    }


}
