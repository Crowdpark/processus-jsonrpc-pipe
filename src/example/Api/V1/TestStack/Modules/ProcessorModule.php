<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 2:16 PM
 * To change this template use File | Settings | File Templates.
 *
 */
namespace Api\V1\TestStack\Modules;

class ProcessorModule
    extends
    \ProcessusJsonRpc\Modules\Processor\ProcessorModule
{

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
        'authModule'     => array(
            'config' => array(
                'isEnabled' => true,
            ),
        ),
        'cryptModule'    => array(
            'config' => array(
                'isEnabled' => false,
            ),
        ),
        'securityModule' => array(
            'config' => array(
                'isEnabled' => false,
            ),
        ),
    );

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
     * @return AuthModule
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
     * @return DebugModule
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


}
