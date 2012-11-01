<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 4:58 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Router;

use Processus\JsonRpc\Modules\Base\BaseModuleException;

class RouterModuleException extends BaseModuleException
{

    const ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO
        = 'ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO';
    const ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO_CLASSNAME
        = 'ERROR_ROUTE_NOT_FOUND_NO_SERVICEINFO_CLASSNAME';
    const ERROR_ROUTE_NOT_FOUND_INVALID_SERVICE_METHODNAME
        = 'ERROR_ROUTE_NOT_FOUND_INVALID_SERVICE_METHODNAME';
    const ERROR_ROUTE_NOT_FOUND_METHODFILTER_ALLOW
        = 'ERROR_ROUTE_NOT_FOUND_METHODFILTER_ALLOW';
    const ERROR_ROUTE_NOT_FOUND_METHODFILTER_DENY
        = 'ERROR_ROUTE_NOT_FOUND_METHODFILTER_DENY';
}
