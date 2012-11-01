<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/18/12
 * Time: 11:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Gateway;

use ProcessusJsonRpc\Modules\Base\BaseModuleException;

class GatewayModuleException extends BaseModuleException
{

    const ERROR_GATEWAY_PREPARE_FATAL_INTERNAL
        = 'ERROR_GATEWAY_PREPARE_FATAL_INTERNAL';
    const ERROR_GATEWAY_NOT_ENABLED
        = 'ERROR_GATEWAY_NOT_ENABLED';
    const ERROR_GATEWAY_RAW_REQUEST_INVALID
        = 'ERROR_GATEWAY_RAW_REQUEST_INVALID';
    const ERROR_GATEWAY_RAW_REQUEST_NODATA
        = 'ERROR_GATEWAY_RAW_REQUEST_NODATA';
    const ERROR_GATEWAY_RAW_RESPONSE_NODATA
        = 'ERROR_GATEWAY_RAW_RESPONSE_NODATA';
    const ERROR_GATEWAY_RAW_RESPONSE_INVALID
        = 'ERROR_GATEWAY_RAW_RESPONSE_INVALID';
    const ERROR_GATEWAY_RAW_RESPONSE_EMIT_FAILED
        = 'ERROR_GATEWAY_RAW_RESPONSE_EMIT_FAILED';
    const ERROR_RPC_ITEM_SERIALIZE_FAILED
        = 'ERROR_RPC_ITEM_SERIALIZE_FAILED';


}

