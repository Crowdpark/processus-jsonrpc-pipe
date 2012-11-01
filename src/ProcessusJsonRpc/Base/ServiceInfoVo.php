<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Base;

class ServiceInfoVo
    extends BaseVo
{
    /**
     * @var array
     */
    protected $_data = array(

        "serviceName"                   => "Seb.User",
        "className"                     => "{{NAMESPACE}}\\WebService\\Ping",
        'isValidateMethodParamsEnabled' => true,
        "classMethodFilter"             => array(
            "allow" => array(
                "*"
            ),
            "deny"  => array(
                "*myPrivateMethod"
            ),
        ),
    );

    /**
     * @return string
     */
    public function getServiceUid()
    {
        return '' . trim(strtolower('' . $this->getServiceName()));
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return '' . $this->getDataKey('className');
    }

    /**
     * @return string
     * @return ServiceInfoVo
     */
    public function setClassName($className)
    {
        $this->setDataKey('className', '' . $className);

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return '' . $this->getDataKey('serviceName');
    }

    /**
     * @return string
     * @return ServiceInfoVo
     */
    public function setServiceName($serviceName)
    {
        $this->setDataKey('serviceName', '' . $serviceName);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsValidateMethodParamsEnabled()
    {
        return ($this->getDataKey(
            'isValidateMethodParamsEnabled'
        ) === true);
    }

    /**
     * @param $value
     * @return ServiceInfoVo
     */
    public function setIsValidateMethodParamsEnabled($value)
    {
        $this->setDataKey(
            'isValidateMethodParamsEnabled',
            ($value === true)
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getClassMethodFilter()
    {
        return (array)$this->getDataKey('classMethodFilter');
    }

    /**
     * @param array $filter
     * @return ServiceInfoVo
     */
    public function setClassMethodFilter($filter = array())
    {
        $this->setDataKey('classMethodFilter', $filter);

        return $this;
    }

    /**
     * @return array
     */
    public function getClassMethodFilterKey($filterKey)
    {
        $filterKey = '' . $filterKey;

        $filter = $this->getClassMethodFilter();

        if (!array_key_exists($filterKey, $filter)) {
            $filter[$filterKey] = array();
        }
        if (!is_array($filter[$filterKey])) {
            $filter[$filterKey] = array();
        }

        $this->setClassMethodFilter($filter);

        return $filter[$filterKey];
    }

    /**
     * @return array
     */
    public function getClassMethodFilterAllow()
    {
        $result = $this->getClassMethodFilterKey('allow');

        return $result;
    }

    /**
     * @return array
     */
    public function getClassMethodFilterDeny()
    {
        $result = $this->getClassMethodFilterKey('deny');

        return $result;
    }
}