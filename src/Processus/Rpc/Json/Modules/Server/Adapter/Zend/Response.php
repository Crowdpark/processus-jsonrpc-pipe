<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 5:04 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Server\Adapter\Zend;

use Processus\Rpc\Json\Base\JsonRpcException;
use Processus\Rpc\Json\Base\RpcUtil;

class Response
    extends
    \Zend\Json\Server\Response
{

    /**
     * @var array|null
     */
    protected $_rawData;

    /**
     * @var string
     */
    protected $_rawText;

    /**
     * @var array|null
     */
    protected $_headers;
    /**
     * @var \Exception|null
     */
    protected $_exception;

    /**
     * @param \Exception $exception
     * @return Response
     */
    public function setException(\Exception $exception)
    {
        $this->_exception = $exception;

        return $this;
    }

    /**
     * @return \Exception|null
     */
    public function getException()
    {

        return $this->_exception;
    }

    /**
     * @return bool
     */
    public function hasException()
    {

        return ($this->getException() instanceof \Exception);
    }

    /**
     * @param bool $isDebugEnabled
     * @return array
     */
    public function createRawData($isDebugEnabled)
    {
        $isDebugEnabled = ($isDebugEnabled === true);

        $response = array(
            'id'     => $this->getId(),
            'result' => null,
            'error'  => null,
        );
        // response.jsonrpc
        $version = $this->getVersion();
        if (
            (is_string($version))
            && (!empty($version))
        ) {
            $response['jsonrpc'] = $version;
        }

        // response.error
        if ($this->hasException()) {
            $response['error'] = $this->_exportExceptionAsArray(
                $this->getException(),
                $isDebugEnabled
            );

            return $response;
        }

        // response.result
        $response['result'] = $this->getResult();

        return $response;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        if (!is_array($this->_headers)) {
            $this->_headers = array(
                'Content-Type: application/json',
            );
        }

        return $this->_headers;
    }

    /**
     * @param \Exception $exception
     * @param bool $isDebugEnabled
     * @return array
     */
    protected function _exportExceptionAsArray(
        \Exception $exception,
        $isDebugEnabled
    ) {
        $error = RpcUtil::exceptionAsArray($exception, $isDebugEnabled);

        return $error;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function setRawData($data)
    {
        if (!is_array($data)) {
            $data = null;
        }
        $this->_rawData = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRawData()
    {
        $result = null;

        $data = $this->_rawData;
        if (!is_array($data)) {

            return $result;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasRawData()
    {
        $data = $this->getRawData();

        return (
            (is_array($data))
                && (count($data) > 0)
        );
    }

    /**
     * @return Response
     */
    public function unsetRawData()
    {
        $this->_rawData = null;

        return $this;
    }


    /**
     * @param string $text
     * @return Response
     */
    public function setRawText($text)
    {
        if (!is_array($text)) {
            $text = null;
        }
        $this->_rawText = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRawText()
    {
        $result = null;

        $text = $this->_rawText;
        if (!is_string($text)) {

            return $result;
        }

        return $text;
    }

    /**
     * @return bool
     */
    public function hasRawText()
    {
        $text = $this->getRawText();

        return (
            (is_string($text))
                && ($text !== '')
        );
    }

    /**
     * @return Response
     */
    public function unsetRawText()
    {
        $this->_rawText = null;

        return $this;
    }


    /**
     * @override
     * @deprecated
     * @return string|void
     * @throws JsonRpcException
     */
    public function toJson()
    {
        $e = new JsonRpcException(
            JsonRpcException::ERROR_DEPRECATED_METHOD_CALL
        );
        $e->setMethodInfo($this, __METHOD__, __LINE__);

        throw $e;
    }


}
