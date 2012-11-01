<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/22/12
 * Time: 4:26 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Api\V1\TestStack\Modules;

class GatewayModule
    extends
    \Processus\JsonRpc\Modules\Gateway\GatewayModule
{

    /**
     * @override
     * @var bool
     */
    protected $_isEnabled = true;

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

    /**
     * @return ProcessorModule
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


}
