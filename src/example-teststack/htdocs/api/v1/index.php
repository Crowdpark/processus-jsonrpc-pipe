<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 11/1/12
 * Time: 11:19 AM
 * To change this template use File | Settings | File Templates.
 */

// ========== bootstrap ======

require_once dirname(__FILE__) . '/../../../Bootstrap.php';

Bootstrap::getInstance()
    ->init();

// ========== run ============

$mockEnabled = false;
$mockRequest = array(
    'method' => 'TestStack.Test.ping',
    'params' => array(),
);

use Api\V1\TestStack\Modules\GatewayModule;

$gtw = new \Api\V1\TestStack\Modules\GatewayModule();
$gtw->init();
$gtw->setIsAutoFetchRequestTextEnabled(true);
$gtw->setIsAutoEmitResponseEnabled(true);
$gtw->setIsDebugEnabled(true);
if($mockEnabled) {
    $gtw->setIsAutoFetchRequestTextEnabled(false);
    $gtw->setRawRequestData($mockRequest);
}
$gtw->run();