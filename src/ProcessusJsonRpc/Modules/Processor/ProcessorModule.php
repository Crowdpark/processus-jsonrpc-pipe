<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/22/12
 * Time: 3:18 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Processor;

use ProcessusJsonRpc\Modules\Base\BaseModule;

use ProcessusJsonRpc\Modules\Auth\AuthModule;
use ProcessusJsonRpc\Modules\Crypt\CryptModule;
use ProcessusJsonRpc\Modules\Debug\DebugModule;
use ProcessusJsonRpc\Modules\Router\RouterModule;
use ProcessusJsonRpc\Modules\Security\SecurityModule;
use ProcessusJsonRpc\Modules\Server\ServerModule;

use ProcessusJsonRpc\Base\RpcUtil;
use ProcessusJsonRpc\Base\Rpc;
use ProcessusJsonRpc\Modules\Gateway\GatewayModule;

class ProcessorModule extends BaseModule
{
    /**
     * @var ServerModule
     */
    protected $_serverModule;
    /**
     * @var RouterModule
     */
    protected $_routerModule;
    /**
     * @var CryptModule
     */
    protected $_cryptModule;
    /**
     * @var SecurityModule
     */
    protected $_securityModule;
    /**
     * @var AuthModule
     */
    protected $_authModule;
    /**
     * @var DebugModule
     */
    protected $_debugModule;

    /**
     * @var array
     */
    protected $_config = array(
        // DI ...
        'serverModule'   => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
        'routerModule'   => array(
            'config' => array(
                'isEnabled' => true,

            ),
        ),
        'debugModule'    => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
        'cryptModule'    => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
        'authModule'     => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
        'securityModule' => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
    );

    // ========== config =======================
    /**
     * @return array
     */
    public function getConfig()
    {

        if (!is_array($this->_config)) {
            $this->_config = array();
        }

        return $this->_config;
    }

    /**
     *
     * @param $key
     *
     * @return mixed | null
     */
    public function getConfigValue($key)
    {
        $result = null;

        $config = $this->getConfig();

        if (!is_array($config)) {

            return $result;
        }

        if (array_key_exists($key, $config)) {

            return $config[$key];
        }

        return $result;
    }

    /**
     * @param $key
     * @return array
     */
    protected function _getModuleConfigItemByKey($key)
    {
        $result = array(
            'config' => null,
            'class'  => null,
        );

        $item   = $this->getConfigValue($key);
        $result = RpcUtil::arrayEnsure($item, $result);

        return $result;
    }

    // ========== base accessors =======================

    /**
     * @return GatewayModule|null
     */
    protected function _getGateway()
    {
        return $this->getRpc()->getGateway();
    }

    /**
     * @return bool
     */
    protected function _getIsDebugEnabled()
    {
        return $this->_getGateway()->getIsDebugEnabled();
    }

    // ========== run =======================

    /**
     * @return ProcessorModule
     */
    public function run()
    {
        $hasException = false;

        $rpc = $this->getRpc();

        // decrypt, parse, auth
        try {
            // parse request
            $this->_parseRequestRawData();
            // check module is enabled
            $this->requireIsEnabled($this, __METHOD__, __LINE__, null);
            // CryptModule: decrypt
            $this->_cryptModuleHandleRequest();
            // SecurityModule: validate request signature
            $this->_securityModuleHandleRequest();
            // AuthModule: checkAuth
            $this->_authModuleHandleRequest();
            // from here, we know the user (if there is one)!
            $this->_debugModuleHandleRequest();
            // RouterModule: find route
            $this->_routerModuleHandleRequest();
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onBeforeRouteRequestError($e);
        }
        // invoke rpc
        try {
            if (!$hasException) {
                $this->_serverModuleHandleRequest();
            }
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onServerModuleHandleRequestError($e);
        }
        // create response ready data
        try {
            $this->_createResponseReadyData();
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onCreateResponseReadyDataError($e);
        }
        // DebugModule: add debugInfo to responseReadyData
        try {
            $this->_debugModuleHandleResponseReadyData();
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onDebugModuleHandleResponseError($e);
        }
        // sign response raw data
        try {
            $this->_securityModuleHandleResponse();
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onSecurityModuleHandleResponseError($e);
        }
        // encrypt response raw data
        try {
            $this->_cryptModuleHandleResponse();
        } catch (\Exception $e) {
            $hasException = true;
            $rpc->getResponse()
                ->setException($e);
            $this->_onCryptModuleHandleResponseError($e);
        }

        return $this;
    }


    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onBeforeRouteRequestError(\Exception $exception)
    {
        return $this;
    }

    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onServerModuleHandleRequestError(\Exception $exception)
    {
        return $this;
    }

    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onCreateResponseReadyDataError(\Exception $exception)
    {
        // lets create a bare metal response
        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();
        $response->getReadyData();
        $e         = $this->newModuleException();
        $readyData = array(
            'result' => null,
            'error'  => array(
                'message' => $e::
                    ERROR_CREATE_READY_RESPONSE_FAILED
            ),
        );

        try {
            $readyData['id'] = $rpc->getRequest()->getId();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['version'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['jsonrpc'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }

        $response->setReadyData($readyData);

        return $this;
    }

    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onDebugModuleHandleResponseError(
        \Exception $exception
    ) {
        // lets create a bare metal response
        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();
        $response->getReadyData();
        $e         = $this->newModuleException();
        $readyData = array(
            'result' => null,
            'error'  => array(
                'message' => $e::
                    ERROR_DEBUG_MODULE_HANDLE_RESPONSE_FAILED
            ),
        );

        try {
            $readyData['id'] = $rpc->getRequest()->getId();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['version'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['jsonrpc'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }

        $response->setReadyData($readyData);

        return $this;
    }

    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onSecurityModuleHandleResponseError(
        \Exception $exception
    ) {
        // lets create a bare metal response
        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();
        $response->getReadyData();
        $e         = $this->newModuleException();
        $readyData = array(
            'result' => null,
            'error'  => array(
                'message' => $e::
                    ERROR_SECURITY_MODULE_HANDLE_RESPONSE_FAILED
            ),
        );

        try {
            $readyData['id'] = $rpc->getRequest()->getId();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['version'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['jsonrpc'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }

        $response->setReadyData($readyData);

        return $this;
    }

    /**
     * @param \Exception $exception
     * @return ProcessorModule
     */
    protected function _onCryptModuleHandleResponseError(
        \Exception $exception
    ) {
        // lets create a bare metal response
        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();
        $response->getReadyData();
        $e         = $this->newModuleException();
        $readyData = array(
            'result' => null,
            'error'  => array(
                'message' => $e::
                    ERROR_CRYPT_MODULE_HANDLE_RESPONSE_FAILED
            ),
        );

        try {
            $readyData['id'] = $rpc->getRequest()->getId();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['version'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }
        try {
            $readyData['jsonrpc'] = $rpc->getRequest()->getVersion();
        } catch (\Exception $e) {
            //NOP
        }

        $response->setReadyData($readyData);

        return $this;
    }


    /**
     * @return ProcessorModule
     */
    protected function _parseRequestRawData()
    {
        $result = $this;

        $rpc = $this->getRpc();

        $request  = $rpc->getRequest();
        $response = $rpc->getResponse();
        // import rawData
        $request->applyRawData();
        $response->setId(
            $request->getId()
        );
        $response->setVersion(
            $request->getVersion()
        );

        /*
        $response->setJsonrpc(
            $request->getJsonrpc()
        );
        */

        return $result;
    }

    /**
     * @param object|string $class
     * @param string $method
     * @param int $line
     * @param null|array $debugData
     * @return ProcessorModule|bool
     * @throws ProcessorModuleException
     */
    public function requireIsEnabled(
        $class = null,
        $method = null,
        $line = null,
        $debugData = null
    ) {
        parent::requireIsEnabled($class, $method, $line, $debugData);

        if (!$this->_getGateway()->getIsEnabled()) {
            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_GATEWAY_NOT_ENABLED);
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            if (is_array($debugData)) {
                $e->setDebugData($debugData);
            }

            throw $e;
        }

        return $this;
    }


    /**
     * @return ProcessorModule
     */
    protected function _cryptModuleHandleRequest()
    {
        $result = $this;

        $module = $this->getCryptModule();
        if (!$module->getIsEnabled()) {

            return $result;
        }

        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleRequest();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _cryptModuleHandleResponse()
    {
        $result = $this;

        $module = $this->getCryptModule();
        if (!$module->getIsEnabled()) {

            return $result;
        }

        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleResponse();

        return $result;
    }


    /**
     * @return ProcessorModule
     */
    protected function _securityModuleHandleRequest()
    {
        $result = $this;

        $module = $this->getSecurityModule();
        if (!$module->getIsEnabled()) {

            return $result;
        }

        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleRequest();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _securityModuleHandleResponse()
    {
        $result = $this;

        $module = $this->getSecurityModule();
        if (!$module->getIsEnabled()) {

            return $result;
        }

        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleResponse();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _authModuleHandleRequest()
    {
        $result = $this;

        $module = $this->getAuthModule();
        if (!$module->getIsEnabled()) {

            return $result;
        }
        $rpc = $this->getRpc();
        $module->setRpc($rpc);

        $module->handleRequest();
        $isLoggedIn = $module->getIsLoggedIn();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _routerModuleHandleRequest()
    {
        $result = $this;

        $module = $this->getRouterModule();
        $module->requireIsEnabled(
            $this,
            __METHOD__,
            __LINE__,
            array(
                'moduleClass' => get_class($module),
            )
        );
        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleRequest();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _serverModuleHandleRequest()
    {
        $result = $this;

        $module = $this->getServerModule();
        $module->requireIsEnabled(
            $this,
            __METHOD__,
            __LINE__,
            array(
                'moduleClass' => get_class($module),
            )
        );
        $rpc = $this->getRpc();
        $module->setRpc($rpc);
        $module->handleRequest();

        return $result;
    }


    /**
     * @return ProcessorModule
     */
    protected function _createResponseReadyData()
    {
        $result = $this;

        $rpc      = $this->getRpc();
        $response = $rpc->getResponse();
        $response->createReadyData();

        return $result;
    }


    /**
     * @return ProcessorModule
     */
    protected function _debugModuleHandleRequest()
    {
        $result = $this;
        $rpc    = $this->getRpc();

        $debugModule = $this->getDebugModule();
        $debugModule->setRpc($rpc);
        if (!$debugModule->getIsEnabled()) {

            return $result;
        }

        $debugModule->handleRequest();

        return $result;
    }

    /**
     * @return ProcessorModule
     */
    protected function _debugModuleHandleResponseReadyData()
    {
        $result = $this;
        $rpc    = $this->getRpc();

        $debugModule = $this->getDebugModule();
        $debugModule->setRpc($rpc);
        if (!$debugModule->getIsEnabled()) {

            return $result;
        }

        $debugModule->handleResponseReadyData();

        return $result;
    }


    // ========== factory: rpc =======================

    /**
     * @return Rpc
     */
    public function newRpc()
    {
        $rpc = new Rpc();
        $rpc->init();

        return $rpc;
    }

    // ========== serverModule =======================

    /**
     * @return ServerModule
     */
    public function getServerModule()
    {
        $result = null;

        $module = $this->_serverModule;
        if (!($module instanceof ServerModule)) {

            $this->_serverModule = $this->newServerModule();
        }

        return $this->_serverModule;
    }

    /**
     * @return ServerModule
     * @throws \Exception
     */
    public function newServerModule()
    {
        $result = null;

        $configKey      = 'serverModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new ServerModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param ServerModule $serverModule
     * @return ProcessorModule
     */
    public function setServerModule(ServerModule $serverModule)
    {
        $this->_serverModule = $serverModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetServerModule()
    {
        $this->_serverModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasServerModule()
    {

        return ($this->_serverModule instanceof ServerModule);
    }


    // ========== routerModule =======================

    /**
     * @return RouterModule
     */
    public function getRouterModule()
    {
        $result = null;

        $module = $this->_routerModule;
        if (!($module instanceof RouterModule)) {

            $this->_routerModule = $this->newRouterModule();
        }

        return $this->_routerModule;
    }

    /**
     * @return RouterModule
     * @throws \Exception
     */
    public function newRouterModule()
    {
        $result = null;

        $configKey      = 'routerModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new RouterModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param RouterModule $routerModule
     * @return ProcessorModule
     */
    public function setRouterModule(RouterModule $routerModule)
    {
        $this->_routerModule = $routerModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetRouterModule()
    {
        $this->_routerModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRouterModule()
    {

        return ($this->_routerModule instanceof RouterModule);
    }


    // ========== cryptModule =======================

    /**
     * @return CryptModule
     */
    public function getCryptModule()
    {
        $result = null;

        $module = $this->_cryptModule;
        if (!($module instanceof CryptModule)) {

            $this->_cryptModule = $this->newCryptModule();
        }

        return $this->_cryptModule;
    }

    /**
     * @return CryptModule
     * @throws \Exception
     */
    public function newCryptModule()
    {
        $result = null;

        $configKey      = 'cryptModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new CryptModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param CryptModule $cryptModule
     * @return ProcessorModule
     */
    public function setCryptModule(CryptModule $cryptModule)
    {
        $this->_cryptModule = $cryptModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetCryptModule()
    {
        $this->_cryptModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCryptModule()
    {

        return ($this->_cryptModule instanceof CryptModule);
    }

// ========== securityModule =======================

    /**
     * @return SecurityModule
     */
    public function getSecurityModule()
    {
        $result = null;

        $module = $this->_securityModule;
        if (!($module instanceof SecurityModule)) {

            $this->_securityModule = $this->newSecurityModule();
        }

        return $this->_securityModule;
    }

    /**
     * @return SecurityModule
     * @throws \Exception
     */
    public function newSecurityModule()
    {
        $result = null;

        $configKey      = 'securityModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new SecurityModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param SecurityModule $securityModule
     * @return ProcessorModule
     */
    public function setSecurityModule(SecurityModule $securityModule)
    {
        $this->_securityModule = $securityModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetSecurityModule()
    {
        $this->_securityModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSecurityModule()
    {

        return ($this->_securityModule instanceof SecurityModule);
    }


    // ========== authModule =======================

    /**
     * @return AuthModule
     */
    public function getAuthModule()
    {
        $result = null;

        $module = $this->_authModule;
        if (!($module instanceof AuthModule)) {

            $this->_authModule = $this->newAuthModule();
        }

        return $this->_authModule;
    }

    /**
     * @return AuthModule
     * @throws \Exception
     */
    public function newAuthModule()
    {
        $result = null;

        $configKey      = 'authModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new AuthModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param AuthModule $authModule
     * @return ProcessorModule
     */
    public function setAuthModule(AuthModule $authModule)
    {
        $this->_authModule = $authModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetAuthModule()
    {
        $this->_authModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAuthModule()
    {

        return ($this->_authModule instanceof AuthModule);
    }


    // ========== debugModule =======================

    /**
     * @return DebugModule
     */
    public function getDebugModule()
    {
        $result = null;

        $module = $this->_debugModule;
        if (!($module instanceof DebugModule)) {

            $this->_debugModule = $this->newDebugModule();
        }

        return $this->_debugModule;
    }

    /**
     * @return DebugModule
     * @throws \Exception
     */
    public function newDebugModule()
    {
        $result = null;

        $configKey      = 'debugModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new DebugModule();
        // initialize module ....
        $moduleInstance->init();
        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param DebugModule $debugModule
     * @return ProcessorModule
     */
    public function setDebugModule(DebugModule $debugModule)
    {
        $this->_debugModule = $debugModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetDebugModule()
    {
        $this->_debugModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDebugModule()
    {

        return ($this->_debugModule instanceof DebugModule);
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return ProcessorModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new ProcessorModuleException($message, $code, $previous);

        return $exception;
    }


}
