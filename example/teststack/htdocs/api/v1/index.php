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

$startTS = microtime(true);

use Api\V1\TestStack\Modules\GatewayModule;
$gtw = new \Api\V1\TestStack\Modules\GatewayModule();
$gtw->init();
$gtw->setIsAutoFetchRequestTextEnabled(true);
$gtw->setIsAutoEmitResponseEnabled(true);
$gtw->setIsDebugEnabled(false);
if($mockEnabled) {
    $gtw->setIsAutoFetchRequestTextEnabled(false);
    $gtw->setRawRequestData($mockRequest);
}
$gtw->run();

$stopTS = microtime(true);
$durationTS = $stopTS-$startTS;

if($mockEnabled) {
    var_dump(array(
            'duration' =>$durationTS,
        ));
}
