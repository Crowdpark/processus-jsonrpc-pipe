<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 1:40 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\JsonRpc\Modules\Server\Adapter\Zend;

class Request
    extends
    \Zend\Json\Server\Request\Http
{

    /**
     * @override
     */
    public function __construct()
    {
        // we do not want to auto decode json within the constructor!!!
    }


}
