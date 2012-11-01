<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/22/12
 * Time: 5:56 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Base;

use ProcessusJsonRpc\Base\JsonRpcException;

class BaseModuleException extends JsonRpcException
{
    const ERROR_MODULE_NOT_ENABLED
        = 'ERROR_MODULE_NOT_ENABLED';

}
