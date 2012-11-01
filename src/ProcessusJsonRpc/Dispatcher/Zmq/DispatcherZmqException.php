<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/25/12
 * Time: 4:53 PM
 * To change this template use File | Settings | File Templates.
 */
namespace ProcessusJsonRpc\Dispatcher\Zmq;

use ProcessusJsonRpc\Dispatcher\DispatcherException;

class DispatcherZmqException extends DispatcherException
{
    const ERROR_ZMQ_EXTENSION_NOT_FOUND
        = 'ERROR_ZMQ_EXTENSION_NOT_FOUND';
    const ERROR_ZMQ_SOCKET_URI_INVALID
        = 'ERROR_ZMQ_SOCKET_URI_INVALID';
}
