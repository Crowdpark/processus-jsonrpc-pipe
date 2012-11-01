<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/18/12
 * Time: 11:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Modules\Auth;

use ProcessusJsonRpc\Modules\Base\BaseModuleException;

class AuthModuleException extends BaseModuleException
{

    const ERROR_LOGIN_REQUIRED = 'ERROR_LOGIN_REQUIRED';

}
