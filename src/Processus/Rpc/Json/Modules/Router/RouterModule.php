<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/19/12
 * Time: 6:59 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Router;

use Processus\Rpc\Json\Modules\Base\BaseModule;
use Processus\Rpc\Json\Base\ServiceInfoVo;

class RouterModule extends BaseModule
{
    /**
     * @var array
     */
    protected $_servicesList = array(
        // a service
        array(
            'serviceName' => 'TestStack.Test',
            'className'
                                            => 'Application\\Core\\Lib\\Rpc\Json\\\Service\\Ping',
            'isValidateMethodParamsEnabled' => true,
            'classMethodFilter'             => array(
                'allow' => array(
                    '*',
                ),
                'deny'  => array(
                    '*myPrivateMethod',
                ),
            ),

        ),
        // another service

    );

    /**
     * @return RouterModule
     */
    public function handleRequest()
    {
        $result = $this;

        if (!$this->getIsEnabled()) {

            return $result;
        }

        $this->findRequestRoute();
        $this->validateRequestRoute();
        $this->findRequestParams();
        $this->validateRequestParams();

        return $result;
    }

    /**
     * @return RouterModule
     */
    public function findRequestRoute()
    {
        $result = $this;

        $rpc = $this->getRpc();

        $rpcMethod = $rpc->getRequest()->getMethod();
        if (!is_string($rpcMethod)) {
            $rpcMethod = '';
        }

        // parse for route

        $parts      = (array)explode('.', $rpcMethod);
        $methodName = (string)array_pop($parts);

        $parts     = (array)$parts;
        $className = (string)array_pop($parts);

        $parts                   = (array)$parts;
        $packageName             = (string)implode('.', $parts);
        $classQualifiedNameParts = array();
        if ((is_string($packageName)) && (!empty($packageName))) {
            $classQualifiedNameParts[] = $packageName;
        }
        if ((is_string($className)) && (!empty($className))) {
            $classQualifiedNameParts[] = $className;
        }

        $classQualifiedName = (string)implode(
            '.',
            $classQualifiedNameParts
        );
        $servicesList       = (array)$this->getServicesList();
        foreach ($servicesList as $serviceInfoData) {
            if (!is_array($serviceInfoData)) {

                continue;
            }
            if (!array_key_exists('serviceName', $serviceInfoData)) {

                continue;
            }

            if (trim(strtolower($serviceInfoData['serviceName']))
                !== trim(strtolower($classQualifiedName))
            ) {

                continue;
            }

            $serviceInfo = $this->newServiceInfo();
            $serviceInfo->setData($serviceInfoData);
            $serviceInfoClassName = (string)$serviceInfo->getClassName();
            if (empty($serviceInfoClassName)) {

                break;
            }
            $serviceInfoClassName = str_replace(
                '{{NAMESPACE}}',
                $this->getReflectionClass()->getNamespaceName(),
                $serviceInfoClassName
            );
            $serviceInfo->setClassName($serviceInfoClassName);

            //$servicesDictionaryKey = '' . $serviceInfo->getServiceUid();
            $rpc->setRouterServiceInfo($serviceInfo);

            break;
        }
        if (!$rpc->hasRouterServiceInfo()) {

            return $result;
        }

        $serviceInfo = $rpc->getRouterServiceInfo();
        $rpc->setRouterServiceQualifiedName($serviceInfo->getClassName());
        $rpc->setRouterServiceMethodName(
            (string)strtolower(
                trim((string)$methodName)
            )
        );

        return $result;

    }

    /**
     *
     */
    public function findRequestParams()
    {
        $rpc = $this->getRpc();

        $rpcParams = $rpc->getRequest()->getParams();
        if (!is_array($rpcParams)) {
            $rpcParams = array();
        }

        $rpc->setRouterServiceMethodParams($rpcParams);
    }

    /**
     * @return RouterModule
     * @throws RouterModuleException
     */
    public function validateRequestRoute()
    {
        // blacklist/whitelist

        $rpc = $this->getRpc();

        if (!$rpc->hasRouterServiceInfo()) {
            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO);
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                    => $rpc->getRequest()->getMethod(),
                )
            );

            throw $e;
        }

        $serviceInfo          = $rpc->getRouterServiceInfo();
        $serviceInfoClassName = $serviceInfo->getClassName();
        if (!(
            (is_string($serviceInfoClassName))
                && (!empty($serviceInfoClassName)))
        ) {
            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO_CLASSNAME);
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                                           => $rpc->getRequest()->getMethod(),
                    'serviceInfoClassName' => $serviceInfoClassName
                )
            );

            throw $e;
        }

        $methodName = $rpc->getRouterServiceMethodName();
        if (!(
            (is_string($serviceInfoClassName))
                && (!empty($serviceInfoClassName)))
        ) {
            $e = $this->newModuleException();
            $e->setMessage(
                $e::ERROR_ROUTE_NOT_FOUND_INVALID_SERVICE_METHODNAME
            );
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                                           => $rpc->getRequest()->getMethod(),
                    'serviceInfoClassName' => $serviceInfoClassName
                )
            );

            throw $e;
        }

        if (strpos($methodName, '_') !== false) {
            $e = $this->newModuleException();
            $e->setMessage(
                $e::ERROR_ROUTE_NOT_FOUND_INVALID_SERVICE_METHODNAME
            );
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'description'
                    => 'method must not contain underscore chars!',
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                    => $rpc->getRequest()->getMethod(),
                    'serviceInfoClassName'
                    => $serviceInfoClassName
                )
            );

            throw $e;
        }


        $allowMethods = (array)$serviceInfo->getClassMethodFilterAllow();
        $denyMethods  = (array)$serviceInfo->getClassMethodFilterDeny();
        if (defined(FNM_CASEFOLD)) {
            define('FNM_CASEFOLD', 16);
        }

        $isMatched = false;
        foreach ($allowMethods as $pattern) {
            $isMatched = fnmatch(
                '' . $pattern,
                $methodName,
                FNM_CASEFOLD
            );
            if ($isMatched) {

                break;
            }
        }
        $isAllowed = ($isMatched === true);
        if (!$isAllowed) {
            $e = $this->newModuleException();
            $e->setMessage(
                $e::ERROR_ROUTE_NOT_FOUND_METHODFILTER_ALLOW
            );
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                                           => $rpc->getRequest()->getMethod(),
                    'serviceInfoClassName' => $serviceInfoClassName,
                    'methodFilter'         => array(
                        'allow' => $allowMethods,
                        'deny'  => $denyMethods,
                    ),
                )
            );

            throw $e;
        }

        $isMatched = false;
        foreach ($denyMethods as $pattern) {
            $isMatched = fnmatch(
                '' . $pattern,
                $methodName,
                FNM_CASEFOLD
            );
            if ($isMatched) {

                break;
            }
        }
        $isDenied = ($isMatched === true);
        if ($isDenied) {
            $e = $this->newModuleException();
            $e->setMessage(
                $e::ERROR_ROUTE_NOT_FOUND_METHODFILTER_DENY
            );
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'routerServiceQualifiedName'
                    => $rpc->getRouterServiceQualifiedName(),
                    'routerServiceMethodName'
                    => $rpc->getRouterServiceMethodName(),
                    'rpcMethod'
                                           => $rpc->getRequest()->getMethod(),
                    'serviceInfoClassName' => $serviceInfoClassName,
                    'methodFilter'         => array(
                        'allow' => $allowMethods,
                        'deny'  => $denyMethods,
                    ),
                )
            );

            throw $e;
        }

        return $this;
    }

    /**
     * @return ServiceInfoVo
     */
    public function newServiceInfo()
    {
        return new ServiceInfoVo();
    }

    /**
     * @return array
     */
    public function getServicesList()
    {
        return $this->_servicesList;
    }

    /**
     * @return RouterModule
     */
    public function validateRequestParams()
    {

        return $this;
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return RouterModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {

        $exception = new RouterModuleException($message, $code, $previous);

        return $exception;
    }


}
