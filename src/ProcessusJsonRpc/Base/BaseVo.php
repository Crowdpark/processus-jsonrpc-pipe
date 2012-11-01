<?php

namespace ProcessusJsonRpc\Base;

class BaseVo
{

    /**
     * @var array
     */
    protected $_data = array();


    /**
     * @param array $data
     * @return BaseVo
     */
    public function setData($data = array())
    {
        $result = $this;

        if (!is_array($data)) {
            $data = array();
        }
        $this->_data = $data;

        return $result;
    }

    /**
     * @return array
     */
    public function getData()
    {

        return (array)$this->_data;
    }

    /**
     * @return BaseVo
     */
    public function unsetData()
    {
        $result = $this;

        $this->_data = null;

        return $result;
    }

    /**
     * @param array $mixin
     * @return BaseVo
     */
    public function mixinData($mixin = array())
    {
        $result = $this;

        if (!is_array($mixin)) {

            return $result;
        }

        $data = $this->_data;

        if (!is_array($data)) {
            $data = array();
        }
        foreach ($mixin as $key => $value) {
            $data[$key] = $value;
        }

        $this->_data = $data;

        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getDataKey($key)
    {
        $result = null;

        $data = $this->_data;

        if (!is_array($data)) {
            $data        = array();
            $this->_data = $data;
        }
        if (!array_key_exists($key, $data)) {

            return $result;
        }

        return $data[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return BaseVo
     */
    public function setDataKey($key, $value)
    {
        $result = $this;

        if (!is_array($this->_data)) {
            $this->_data = array();
        }

        $this->_data[$key] = $value;

        return $result;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasDataKey($key)
    {
        $result = false;

        if (!is_array($this->_data)) {
            $this->_data = array();

            return $result;
        }

        return array_key_exists($key, $this->_data);
    }

    /**
     * @param $key string
     * @return BaseVo
     */
    public function unsetDataKey($key)
    {
        $result = $this;

        if (!is_array($this->_data)) {

            return $result;
        }

        unset($this->_data[$key]);

        return $result;
    }

    /**
     * @param array $keysList
     * @return BaseVo
     */
    public function unsetDataKeys($keysList = array())
    {
        $result = $this;

        if (!is_array($keysList)) {

            return $result;
        }

        if (!is_array($this->_data)) {
            $this->_data = array();
        }

        foreach ($keysList as $key) {

            unset($this->_data[$key]);
        }

        return $result;
    }

    /**
     * @param array $dictionary
     * @return BaseVo
     */
    public function ensureData(
        $dictionary = array()
    ) {
        $result = $this;

        if (!is_array($this->_data)) {
            $this->_data = array();
        }

        if (!is_array($dictionary)) {

            return $result;
        }

        foreach ($dictionary as $key => $value) {

            if (!array_key_exists($key, $this->_data)) {
                $this->_data[$key] = $value;
            }
        }

        return $result;
    }

}


