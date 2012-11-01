<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/23/12
 * Time: 6:36 PM
 * To change this template use File | Settings | File Templates.
 */
namespace TestStackExample\Api\V1\TestStack\Modules;

class AuthModule
    extends
    \Processus\Rpc\Json\Modules\Auth\AuthModule
{

    /**
     * @var bool
     */
    protected $_isLoginRequired = false;

    /**
     * @return AuthModule
     */
    public function handleRequest()
    {
        parent::handleRequest();

        return $this;
    }

}
