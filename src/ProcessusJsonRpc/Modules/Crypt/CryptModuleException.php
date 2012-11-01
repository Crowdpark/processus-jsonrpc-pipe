<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/18/12
 * Time: 11:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Crypt;

use ProcessusJsonRpc\Modules\Base\BaseModuleException;

class CryptModuleException extends BaseModuleException
{

    const ERROR_DECRYPT_REQUEST_FAILED
        = 'ERROR_DECRYPT_REQUEST_FAILED';
    const ERROR_ENCRYPT_RESPONSE_FAILED
        = 'ERROR_ENCRYPT_RESPONSE_FAILED';
}
