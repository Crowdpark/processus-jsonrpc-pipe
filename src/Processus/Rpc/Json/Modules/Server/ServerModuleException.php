<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 5:25 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Server;

use Processus\Rpc\Json\Modules\Base\BaseModuleException;

class ServerModuleException extends BaseModuleException
{

    const ERROR_SERVERMODULE_SERVICECLASS_NOT_EXIST
        = 'ERROR_SERVERMODULE_SERVICECLASS_NOT_EXIST';
    const ERROR_SERVERMODULE_SERVICECLASS_NOT_INVOCABLE
        = 'ERROR_SERVERMODULE_SERVICECLASS_NOT_INVOCABLE';

    const ERROR_SERVERMODULE_SERVICEMETHOD_NOT_EXISTS
        = 'ERROR_SERVERMODULE_SERVICECLASS_NOT_EXISTS';

    const ERROR_SERVERMODULE_SERVICEMETHOD_NOT_INVOCABLE
        = 'ERROR_SERVERMODULE_SERVICECLASS_NOT_INVOCABLE';

}
