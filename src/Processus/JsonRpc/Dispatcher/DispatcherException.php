<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 4:40 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Dispatcher;

use Processus\JsonRpc\Base\JsonRpcException;

class DispatcherException extends JsonRpcException
{

    const ERROR_DISPATCHER_NOT_INITIALIZED
        = 'ERROR_DISPATCHER_NOT_INITIALIZED';

}