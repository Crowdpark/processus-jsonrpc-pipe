<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 1:40 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Base;

class RpcRequestVo
    extends BaseVo
{

    /**
     * @var array
     */
    protected $_rawData;


    /**
     * @param $data
     * @return RpcRequestVo
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
     * @return RpcRequestVo
     */
    public function unsetRawData()
    {
        $this->_rawData = null;

        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    public function getRawDataKey($key)
    {
        $result = null;

        $data = $this->_rawData;

        if (!is_array($data)) {

            return $result;
        }
        if (!array_key_exists($key, $data)) {

            return $result;
        }

        return $data[$key];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getDataKey('id');
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->getDataKey('version');
    }

    public function getJsonrpc()
    {
        return $this->getDataKey('jsonrpc');
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->getDataKey('method');
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->getDataKey('params');
    }


    /**
     * @return RpcRequestVo
     */
    public function init()
    {

        return $this;
    }

    /**
     * @return RpcRequestVo
     */
    public function applyRawData()
    {
        $rawData = $this->getRawData();
        // extract data
        $data = RpcUtil::arrayEnsure(
            $rawData,
            array(
                'id'      => null,
                'version' => null,
                'jsonrpc' => null,
                'method'  => null,
                'params'  => null,
            )
        );
        $this->setData($data);

        return $this;
    }

    /**
     * @return RpcRequestVo
     */
    public function parse()
    {

        return $this;
    }


}
