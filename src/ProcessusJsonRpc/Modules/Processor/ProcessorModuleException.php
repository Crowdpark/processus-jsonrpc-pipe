<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/22/12
 * Time: 5:27 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Processor;

use ProcessusJsonRpc\Modules\Base\BaseModuleException;

class ProcessorModuleException extends BaseModuleException
{

    const ERROR_CREATE_READY_RESPONSE_FAILED
        = 'ERROR_CREATE_READY_RESPONSE_FAILED';

    const ERROR_DEBUG_MODULE_HANDLE_RESPONSE_FAILED
        = 'ERROR_DEBUG_MODULE_HANDLE_RESPONSE_FAILED';

    const ERROR_SECURITY_MODULE_HANDLE_RESPONSE_FAILED
        = 'ERROR_SECURITY_MODULE_HANDLE_RESPONSE_FAILED';

    const ERROR_CRYPT_MODULE_HANDLE_RESPONSE_FAILED
        = 'ERROR_CRYPT_MODULE_HANDLE_RESPONSE_FAILED';

    const ERROR_GATEWAY_NOT_ENABLED = 'ERROR_GATEWAY_NOT_ENABLED';

}
