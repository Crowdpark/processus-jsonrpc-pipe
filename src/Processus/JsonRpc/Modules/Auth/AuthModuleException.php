<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/18/12
 * Time: 11:13 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Auth;

use Processus\JsonRpc\Modules\Base\BaseModuleException;

class AuthModuleException extends BaseModuleException
{

    const ERROR_LOGIN_REQUIRED = 'ERROR_LOGIN_REQUIRED';

}
