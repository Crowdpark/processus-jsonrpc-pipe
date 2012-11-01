<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/22/12
 * Time: 11:29 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Gateway;

use Processus\Rpc\Json\Modules\Base\BaseModule;
use Processus\Rpc\Json\Base\RpcUtil;
use Processus\Rpc\Json\Base\Rpc;

use Processus\Rpc\Json\Modules\Processor\ProcessorModule;


class GatewayModule extends BaseModule
{

    //protected $_isEnabled;
    /**
     * @var null|\Exception
     */
    protected $_lastException = null;
    /**
     * @var bool
     */
    protected $_isDebugEnabled = false;
    /**
     * @var bool
     */
    protected $_isAutoFetchRequestTextEnabled = true;
    /**
     * @var bool
     */
    protected $_isAutoEmitResponseEnabled = false;
    /**
     * @var null|string|mixed
     */
    protected $_rawRequestText = null;
    /**
     * @var null|array|mixed
     */
    protected $_rawRequestData = null;
    /**
     * @var null|array|mixed
     */
    protected $_rawResponseData = null;
    /**
     * @var null|string|mixed
     */
    protected $_rawResponseText = null;
    /**
     * @var bool
     */
    protected $_isRawRequestBatched = false;

    /**
     * @var
     */
    protected $_rpcQueue = array();

    /**
     * @var Rpc
     */
    //protected $_currentRpc;
    /**
     * @var \ReflectionClass
     */
    //protected $_reflectionClass;
    // ========== modules =======================

    /**
     * @var ProcessorModule
     */
    protected $_processorModule;

    /**
     * @var array
     */
    protected $_config = array(
        // DI ...
        'processorModule' => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
    );

    // ========== base accessors =======================

    /**
     * @return bool
     */
    /**
    public function getIsEnabled()
    {
    return ($this->_isEnabled === true);
    }
     **/

    /**
     * @param bool $enabled
     * @return GatewayModule
     */
    public function setIsAutoFetchRequestTextEnabled($enabled)
    {
        $this->_isAutoFetchRequestTextEnabled = ($enabled === true);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAutoFetchRequestTextEnabled()
    {

        return ($this->_isAutoFetchRequestTextEnabled === true);
    }

    /**
     * @param bool $enabled
     * @return GatewayModule
     */
    public function setIsAutoEmitResponseEnabled($enabled)
    {
        $this->_isAutoEmitResponseEnabled = ($enabled === true);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAutoEmitResponseEnabled()
    {

        return ($this->_isAutoEmitResponseEnabled === true);
    }

    /**
     * @param bool $enabled
     * @return GatewayModule
     */
    public function setIsDebugEnabled($enabled)
    {
        $this->_isDebugEnabled = ($enabled === true);

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDebugEnabled()
    {

        return ($this->_isDebugEnabled === true);
    }

    /**
     * @return string
     */
    public function getRawRequestText()
    {

        return (string)$this->_rawRequestText;
    }

    /**
     * @param $text
     * @return GatewayModule
     */
    public function setRawRequestText($text)
    {
        $this->_rawRequestText = (string)$text;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRawRequestText()
    {
        $result = false;
        if (!is_string($this->_rawRequestText)) {

            return $result;
        }

        return ($this->_rawRequestText !== '');
    }

    /**
     * @return GatewayModule
     */
    public function unsetRawRequestText()
    {
        $this->_rawRequestText = null;

        return $this;
    }

    /**
     * @param array $data
     */
    public function setRawRequestData($data)
    {
        $this->_rawRequestData = $data;
    }

    /**
     * @return array|mixed|null
     */
    public function getRawRequestData()
    {
        return $this->_rawRequestData;
    }

    /**
     * @return bool
     */
    public function hasRawRequestData()
    {
        $result = false;
        if (!is_array($this->_rawRequestData)) {

            return $result;
        }

        return (count($this->_rawRequestData) > 0);
    }

    /**
     * @return array|mixed|null
     */
    public function getRawResponseData()
    {
        return $this->_rawResponseData;
    }

    /**
     * @return bool
     */
    public function hasRawResponseData()
    {

        $data = $this->_rawResponseData;

        return ((is_array($data)) && (count($data) > 0));
    }

    /**
     * @return mixed|null|string
     */
    public function getRawResponseText()
    {

        return $this->_rawResponseText;
    }

    /**
     * @return bool
     */
    public function hasRawResponseText()
    {

        $text = $this->_rawResponseText;

        return ((is_string($text)) && ($text !== ''));
    }


    /**
     * @return bool
     */
    /*
    public function hasCurrentRpc()
    {

        return ($this->_currentRpc instanceof Rpc);
    }
    */
    /**
     * @return rpc
     */
    /*
    public function getCurrentRpc()
    {

        return $this->_currentRpc;
    }
    */
    /**
     * @return \ReflectionClass
     */
    /**
    public function getReflectionClass()
    {
    if (!($this->_reflectionClass instanceof \ReflectionClass)) {
    $this->_reflectionClass = new \ReflectionClass($this);
    }

    return $this->_reflectionClass;
    }
     **/

    /**
     * @return string
     */
    public function getNamespaceName()
    {
        return (string)$this->getReflectionClass()->getName();
    }

    // ========== config =======================

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
     *
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
     * @param $key
     * @return bool
     */
    public function hasConfigKey($key)
    {
        return (
            (array_key_exists($key, $this->getConfig()))
                === true
        );
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

    // ========== init / run =======================

    /**
     * @return GatewayModule
     */
    public function init()
    {
        $this->_resetBeforeRun();

        return $this;
    }

    /**
     * @return GatewayModule
     */
    public function run()
    {
        $result = $this;

        // (1) Gateway: validate gateway, config, factory
        try {
            $this->_resetBeforeRun();
            $this->_validateGateway();
            $this->_resetModules();
        } catch (\Exception $e) {
            $this->_lastException = $e;
            $hasException         = true;
            $fatalException       = $this->newModuleException();
            $fatalException->setMessage(
                $fatalException::ERROR_GATEWAY_PREPARE_FATAL_INTERNAL
            );
            $fatalException->setMethodInfo($this, __METHOD__, __LINE__);
            $fatalException->setFault($e);
            $this->_onErrorGatewayPrepare($fatalException);

            return $result;
        }

        // (2) RawRequest: fetch & decode text as raw request data
        try {
            $this->_rawRequestDecode();
            $this->_rawRequestValidate();
            $rawRequestData             = $this->getRawRequestData();
            $this->_isRawRequestBatched = (
            !RpcUtil::isAssocArray($rawRequestData)
            );
        } catch (\Exception $e) {
            $this->_lastException = $e;
            $hasException         = true;
            $fatalException       = $this->newModuleException();
            $fatalException->setMessage(
                $fatalException::ERROR_GATEWAY_RAW_REQUEST_INVALID
            );
            $fatalException->setMethodInfo($this, __METHOD__, __LINE__);
            $fatalException->setFault($e);
            $this->_onErrorRawRequestInvalid($fatalException);

            return $result;
        }

        // (3) Batch: create batch, process all rpc items
        $batch = array();
        if (!$this->_isRawRequestBatched) {
            $batch = array(
                $rawRequestData,
            );
        } else {
            $batch = $rawRequestData;
        }
        $this->_rpcQueue = $batch;

        $responseList           = array();
        $this->_rawResponseData = $responseList;

        foreach ($batch as $itemData) {
            if (!is_array($itemData)) {
                $itemData = array();
            }
            $rpc = $this->getProcessorModule()->newRpc();
            $rpc->setGateway($this);
            $this->_rpc = $rpc;
            $rpc->getRequest()
                ->setRawData($itemData);
            $this->_currentRpcProcess();

            $itemResponseData       = $rpc->getResponse()
                ->getReadyData();
            $responseList[]         = $itemResponseData;
            $this->_rawResponseData = $responseList;
        }

        // (5) Gateway Response: create, encode, validate
        try {
            $this->_rawResponseCreate();
            $this->_rawResponseEncode();
            //$this->_rawResponseValidate();
        } catch (\Exception $e) {
            $this->_lastException = $e;
            $hasException         = true;
            $fatalException       = $this->newModuleException();
            $fatalException->setMessage(
                $fatalException::ERROR_GATEWAY_RAW_RESPONSE_INVALID
            );
            $fatalException->setMethodInfo($this, __METHOD__, __LINE__);
            $fatalException->setFault($e);
            $this->_onErrorRawResponseInvalid($fatalException);

            return $result;
        }

        // (6) Gateway Response: emit
        try {
            if ($this->getIsAutoEmitResponseEnabled()) {

                $this->_rawResponseEmit();
            }
        } catch (\Exception $e) {
            $this->_lastException = $e;
            $hasException         = true;
            $fatalException       = $this->newModuleException();
            $fatalException->setMessage(
                $fatalException::ERROR_GATEWAY_RAW_RESPONSE_EMIT_FAILED
            );
            $fatalException->setMethodInfo($this, __METHOD__, __LINE__);
            $fatalException->setFault($e);
            $this->_onErrorRawResponseEmitFailed($fatalException);

            return $result;
        }

        return $result;
    }

    // ================== run: process rpc ========================
    /**
     *
     */
    protected function _currentRpcProcess()
    {
        $hasException    = false;
        $rpc             = $this->getRpc();
        $processorModule = $this->getProcessorModule();
        $processorModule->setRpc($rpc);
        $processorModule->run();

        // try serialize response
        // we do not want to invalidate the entire batch
        // if serializing a single item with that batch fails
        $responseData     = $rpc->getResponse()
            ->getReadyData();
        $responseDataJson = RpcUtil::jsonEncode($responseData, false);
        if (!is_string($responseDataJson)) {
            // lets create a bare metal response
            $response = $rpc->getResponse();
            $response->getReadyData();

            $e         = $this->newModuleException();
            $readyData = array(
                'result' => null,
                'error'  => array(
                    'message' => $e::
                        ERROR_RPC_ITEM_SERIALIZE_FAILED
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
        }
        unset($responseDataJson);

    }


    // ========== run: prepare/validate gateway =======================

    /**
     * @return GatewayModule
     */
    protected function _resetBeforeRun()
    {
        $this->_isRawRequestBatched = false;
        $this->_rawResponseText     = null;
        $this->_rawResponseData     = null;
        $this->_rpcQueue            = array();
        $this->_rpc                 = null;
        $this->_lastException       = null;

        return $this;
    }

    /**
     * @return GatewayModule
     */
    protected function _resetModules()
    {
        return $this;
    }

    /**
     * @return GatewayModule
     * @throws GatewayModuleException
     */
    protected function _validateGateway()
    {

        // factory: rpc & vo's

        $processorModule = $this->newProcessorModule();
        $rpc             = $processorModule->newRpc();

        return $this;
    }

    /**
     * @param GatewayModuleException $exception
     * @return GatewayModule
     * @throws GatewayModuleException
     */
    protected function _onErrorGatewayPrepare(
        GatewayModuleException $exception
    ) {
        $result = $this;

        // we may not have a reponse object
        // lets create & emit basic response
        $isDebugEnabled            = $this->getIsDebugEnabled();
        $isAutoEmitResponseEnabled = $this->getIsAutoEmitResponseEnabled();
        if ($isAutoEmitResponseEnabled) {
            $responseData = array(
                'result' => null,
                'error'  => RpcUtil::exceptionAsArray(
                    $exception,
                    $isDebugEnabled
                ),
            );

            $responseText = RpcUtil::jsonEncode($responseData, false);
            if (!is_string($responseText)) {
                $responseData = array(
                    'result' => null,
                    'error'  => RpcUtil::exceptionAsArray(
                        $exception,
                        false
                    ),
                );
                $responseText = RpcUtil::jsonEncode($responseData, false);
            }

            $headersList = array(
                'HTTP/1.1 500 Internal Server Error',
            );
            $this->_emitResponseHeaders($headersList);
            echo (string)$responseText;

            return $result;
        }

    }


    // ========== run: raw request (text/data): fetch, decode, validate ======
    /**
     * @return GatewayModule
     */
    protected function _rawRequestDecode()
    {
        if ($this->getIsAutoFetchRequestTextEnabled()) {
            $requestText           = (string)$this->_rawRequestFetchText();
            $this->_rawRequestText = $requestText;
            $requestData           = RpcUtil::jsonDecode(
                $requestText,
                true,
                false
            );
            if (!is_array($requestData)) {
                $requestData = array();
            }
            $this->_rawRequestData = $requestData;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function _rawRequestFetchText()
    {
        $requestText = (string)file_get_contents('php://input');

        return (string)$requestText;
    }

    /**
     * @return GatewayModule
     * @throws GatewayModuleException
     */
    protected function _rawRequestValidate()
    {
        if (!$this->hasRawRequestData()) {
            $exception = $this->newModuleException();
            $exception->setMessage(
                $exception::ERROR_GATEWAY_RAW_REQUEST_NODATA
            );
            $exception->setMethodInfo($this, __METHOD__, __LINE__);

            throw $exception;
        }

        return $this;
    }

    /**
     * @param GatewayModuleException $exception
     * @return GatewayModule
     */
    protected function _onErrorRawRequestInvalid(
        GatewayModuleException $exception
    ) {
        $result = $this;
        // no valid request raw data
        $isDebugEnabled            = $this->getIsDebugEnabled();
        $isAutoEmitResponseEnabled = $this->getIsAutoEmitResponseEnabled();
        if ($isAutoEmitResponseEnabled) {
            $responseData = array(
                'result' => null,
                'error'  => RpcUtil::exceptionAsArray(
                    $exception,
                    $isDebugEnabled
                ),
            );
            $responseText = RpcUtil::jsonEncode($responseData, false);
            if (!is_string($responseText)) {
                $responseData = array(
                    'result' => null,
                    'error'  => RpcUtil::exceptionAsArray(
                        $exception,
                        false
                    ),
                );
                $responseText = RpcUtil::jsonEncode($responseData, false);
            }

            $headersList = array(
                'HTTP/1.1 500 Internal Server Error',
            );
            $this->_emitResponseHeaders($headersList);
            echo (string)$responseText;

            return $result;
        }

        return $result;
    }

    // == run: raw response (data/text/headers): create, decode, validate ====
    /**
     * @return GatewayModule
     */
    protected function _rawResponseCreate()
    {
        $rawResponseData  = $this->getRawResponseData();
        $isRequestBatched = ($this->_isRawRequestBatched === true);
        if (!$isRequestBatched) {
            if (
                (is_array($rawResponseData))
                && (array_key_exists(0, $rawResponseData))
            ) {
                $rawResponseData = $rawResponseData[0];
            } else {
                $rawResponseData = null;
            }
        }

        $this->_rawResponseData = $rawResponseData;

        return $this;
    }

    /**
     * @return GatewayModule
     */
    protected function _rawResponseEncode()
    {
        $rawResponseData = $this->getRawResponseData();

        $rawResponseText        = RpcUtil::jsonEncode(
            $rawResponseData,
            false
        );
        $this->_rawResponseText = $rawResponseText;

        return $this;
    }

    /**
     * @return GatewayModule
     * @throws GatewayModuleException
     */
    protected function _rawResponseValidate()
    {
        $rawResponseData = $this->getRawResponseData();
        if (!
        (
            (is_array($rawResponseData))
                && (count($rawResponseData) > 0)
        )
        ) {

            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_GATEWAY_RAW_RESPONSE_NODATA);
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'description' => 'rawResponseData is empty!',
                )
            );

            throw $e;
        }

        $rawResponseText = $this->getRawResponseText();
        if (!
        (
            (is_string($rawResponseText))
                && ($rawResponseText !== '')
        )
        ) {
            $e = $this->newModuleException();
            $e->setMessage($e::ERROR_GATEWAY_RAW_RESPONSE_NODATA);
            $e->setMethodInfo($this, __METHOD__, __LINE__);
            $e->setDebugData(
                array(
                    'description' => 'rawResonseText is empty!',
                )
            );

            throw $e;
        }

        return $this;
    }

    /**
     * @param GatewayModuleException $exception
     * @return GatewayModule
     */
    protected function _onErrorRawResponseInvalid(
        GatewayModuleException $exception
    ) {
        $result = $this;
        // no valid request raw data
        $isDebugEnabled            = $this->getIsDebugEnabled();
        $isAutoEmitResponseEnabled = $this->getIsAutoEmitResponseEnabled();
        if ($isAutoEmitResponseEnabled) {
            $responseData = array(
                'result' => null,
                'error'  => RpcUtil::exceptionAsArray(
                    $exception,
                    $isDebugEnabled
                ),
            );
            $responseText = RpcUtil::jsonEncode($responseData, false);
            if (!is_string($responseText)) {
                $responseData = array(
                    'result' => null,
                    'error'  => RpcUtil::exceptionAsArray(
                        $exception,
                        false
                    ),
                );
                $responseText = RpcUtil::jsonEncode($responseData, false);
            }

            $headersList = array(
                'HTTP/1.1 500 Internal Server Error',
            );
            $this->_emitResponseHeaders($headersList);
            echo (string)$responseText;

            return $result;
        }

        return $result;
    }

    /**
     * @return GatewayModule
     */
    protected function _rawResponseEmit()
    {
        $result = $this;

        if (!$this->getIsAutoEmitResponseEnabled()) {

            return $result;
        }

        $rawResponseText = (string)$this->getRawResponseText();
        $headersList     = array(
            'Content-type: application/json',
        );
        $this->_emitResponseHeaders($headersList);

        //$this->_emitResponseHeaders()
        echo (string)$rawResponseText;

        return $result;
    }

    /**
     * @param GatewayModuleException $exception
     * @return GatewayModule
     */
    protected function _onErrorRawResponseEmitFailed(
        GatewayModuleException $exception
    ) {
        $result = $this;
        // no valid request raw data
        $isDebugEnabled            = $this->getIsDebugEnabled();
        $isAutoEmitResponseEnabled = $this->getIsAutoEmitResponseEnabled();
        if ($isAutoEmitResponseEnabled) {
            $responseData = array(
                'result' => null,
                'error'  => RpcUtil::exceptionAsArray(
                    $exception,
                    $isDebugEnabled
                ),
            );
            $responseText = RpcUtil::jsonEncode($responseData, false);
            if (!is_string($responseText)) {
                $responseData = array(
                    'result' => null,
                    'error'  => RpcUtil::exceptionAsArray(
                        $exception,
                        false
                    ),
                );
                $responseText = RpcUtil::jsonEncode($responseData, false);
            }

            $headersList = array(
                'HTTP/1.1 500 Internal Server Error',
            );
            $this->_emitResponseHeaders($headersList);
            echo (string)$responseText;

            return $result;
        }

        return $result;
    }

    /**
     * @param $headersList
     * @return GatewayModule
     */
    protected function _emitResponseHeaders($headersList)
    {
        $result                    = $this;
        $isAutoEmitResponseEnabled = $this->getIsAutoEmitResponseEnabled();

        if (!$isAutoEmitResponseEnabled) {

            return $result;
        }

        if (!is_array($headersList)) {

            return $result;
        }
        foreach ($headersList as $headerText) {
            if (
                (is_string($headerText))
                && (!(empty($headerText)))
            ) {
                try {
                    header($headerText);
                } catch (\Exception $e) {
                    //NOP
                }
            }
        }

        return $result;
    }

    // ================== factory: rpc ========================
    /**
     * @return Rpc
     * @throws \Exception
     */
    public function newRpc()
    {
        $result = null;
        $rpc    = new Rpc();
        $rpc->setGateway($this);
        // initialize ....
        $rpc->init();

        return $rpc;
    }

    // ================== factory: Modules ========================
    // ========== processorModule =======================

    /**
     * @return ProcessorModule
     */
    public function getProcessorModule()
    {
        $result = null;

        $module = $this->_processorModule;
        if (!($module instanceof ProcessorModule)) {

            $this->_processorModule = $this->newProcessorModule();
        }

        return $this->_processorModule;
    }

    /**
     * @return ProcessorModule
     * @throws \Exception
     */
    public function newProcessorModule()
    {
        $result = null;

        $configKey      = 'processorModule';
        $moduleConfig   = $this->_getModuleConfigItemByKey($configKey);
        $moduleInstance = new ProcessorModule();
        // initialize module ....
        $moduleInstance->init();

        $moduleInstance->applyConfig($moduleConfig['config']);

        return $moduleInstance;
    }

    /**
     * @param ProcessorModule $processorModule
     * @return ProcessorModule
     */
    public function setProcessorModule(ProcessorModule $processorModule)
    {
        $this->_processorModule = $processorModule;

        return $this;
    }

    /**
     * @return ProcessorModule
     */
    public function unsetProcessorModule()
    {
        $this->_processorModule = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasProcessorModule()
    {

        return ($this->_processorModule instanceof ProcessorModule);
    }

    /**
     * @param string $message
     * @param int $code
     * @param null|\Exception $previous
     * @return GatewayModuleException
     */
    public function newModuleException(
        $message = '',
        $code = 0,
        $previous = null
    ) {
        $exception = new GatewayModuleException($message, $code, $previous);

        return $exception;
    }


}
