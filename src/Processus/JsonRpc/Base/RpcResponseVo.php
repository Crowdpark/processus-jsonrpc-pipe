<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 5:04 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Base;

class RpcResponseVo
    extends
    BaseVo
{


    /**
     * @var array
     */
    protected $_readyData = array();

    /**
     * @var string
     */
    protected $_readyText = '';

    /**
     * @var \Exception|null
     */
    protected $_exception = null;

    /**
     * @param \Exception $exception
     * @return RpcResponseVo
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
     * @return mixed
     */
    public function getId()
    {
        return $this->getDataKey('id');
    }

    /**
     * @param $value
     * @return RpcResponseVo
     */
    public function setId($value)
    {
        $this->setDataKey('id', $value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->getDataKey('version');
    }

    /**
     * @param $value
     * @return RpcResponseVo
     */
    public function setVersion($value)
    {
        $this->setDataKey('version', $value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsonrpc()
    {
        return $this->getDataKey('jsonrpc');
    }

    /**
     * @param $value
     * @return RpcResponseVo
     */
    public function setJsonrpc($value)
    {
        $this->setDataKey('jsonrpc', $value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->getDataKey('result');
    }

    /**
     * @param $value
     * @return RpcResponseVo
     */
    public function setResult($value)
    {
        $this->setDataKey('result', $value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->getDataKey('error');
    }


    /**
     * @param $key
     * @param $value
     * @return RpcResponseVo
     */
    public function setReadyDataKey($key, $value)
    {
        $result = $this;

        if (!is_array($this->_readyData)) {
            $this->_readyData = array();
        }
        $this->_readyData[$key] = $value;

        return $result;
    }

    /**
     * @param $key
     * @return null|mixed
     */
    public function getReadyDataKey($key)
    {
        $result = null;

        if (!is_array($this->_readyData)) {
            $this->_readyData = array();
        }
        $data = $this->_readyData;
        if (!array_key_exists($key, $data)) {

            return $result;
        }

        return $data[$key];
    }

    /**
     * @param $key
     * @return RpcResponseVo
     */
    public function unsetReadyDataKey($key)
    {
        $result = $this;

        if (!is_array($this->_readyData)) {

            return $result;
        }

        unset($this->_readyData[$key]);

        return $result;
    }

    /**
     * @return RpcResponseVo
     */
    public function createReadyData()
    {
        $result = $this;

        $readyData = array(
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
            $readyData['jsonrpc'] = $version;
        }

        // response.error
        if ($this->hasException()) {
            // complete exception data to be added by debugModule
            $readyData['error'] = $this->_exportExceptionAsArray(
                $this->getException(),
                false
            );
        } else {
            // response.result
            $readyData['result'] = $this->getResult();
        }

        $this->setReadyData($readyData);

        return $result;
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
     * @return RpcResponseVo
     */
    public function setReadyData($data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $this->_readyData = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getReadyData()
    {
        $result = null;

        if (!is_array($this->_readyData)) {
            $this->_readyData = array();
        }

        return $this->_readyData;
    }

    /**
     * @return bool
     */
    public function hasReadyData()
    {
        $data = $this->getReadyData();

        return (
            (is_array($data))
                && (count($data) > 0)
        );
    }

    /**
     * @return RpcResponseVo
     */
    public function unsetReadyData()
    {
        $this->_readyData = array();

        return $this;
    }


    /**
     * @param string $text
     * @return RpcResponseVo
     */
    public function setReadyText($text)
    {
        if (!is_array($text)) {
            $text = '';
        }
        $this->_readyText = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getReadyText()
    {
        $result = '';

        $text = $this->_readyText;
        if (!is_string($text)) {

            return $result;
        }

        return (string)$text;
    }

    /**
     * @return bool
     */
    public function hasReadyText()
    {
        $text = $this->getReadyText();

        return (
            (is_string($text))
                && ($text !== '')
        );
    }

    /**
     * @return RpcResponseVo
     */
    public function unsetReadyText()
    {
        $this->_readyText = null;

        return $this;
    }


}
