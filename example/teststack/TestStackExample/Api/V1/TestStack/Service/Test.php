<?php

namespace TestStackExample\Api\V1\TestStack\Service;

class Test
    extends \TestStackExample\Api\V1\TestStack\Base\BaseService
{
    /**
     * @return bool
     */
    public function ping()
    {
        return true;
    }

    /**
     * @param $msg
     * @return mixed
     */
    public function say($msg)
    {
        return $msg;
    }

    /**
     * @throws \Exception
     */
    public function error()
    {
        throw new \Exception(
            'This is a example exception.'
                . ' ' . get_class($this)
                . ' ' . __METHOD__
                . ' ' . __LINE__
        );
    }
}
