<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 10/17/12
 * Time: 4:10 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Rpc\Json\Modules\Server\Adapter\Zend;

class Server
    extends \Zend\Json\Server\Server
{

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!($this->_response instanceof Response)) {
            $this->_response = new Response();
        }

        return $this->_response;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!($this->_request instanceof Request)) {
            $this->_request = new Request();
        }

        return $this->_request;
    }

    /**
     *
     */
    public function unsetRequest()
    {
        $this->_request = null;
    }

    /**
     *
     */
    public function unsetResponse()
    {
        $this->_response = null;
    }


    /**
     * we do not(!) use zend fault handling, turn it into exception!
     * @param null|string $fault
     * @param int $code
     * @param null $data
     *
     * @throws \Exception
     */
    public function fault($fault = null, $code = 404, $data = null)
    {
        $message = 'Server Fault (' . $code . ')';
        if (!((is_string($fault)) && (!empty($fault)))) {
            $message = $fault . ' (code = ' . $code . ')';
        }

        throw new \Exception($message);
    }


}
