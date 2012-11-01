<?php

require __DIR__ . "/../../../../../../vendor/autoload.php";

// ========== run ============

use \TestStackExample\Api\V1\TestStack\Modules\GatewayModule;

$debugEnabled = false;
$mockEnabled = false;

$mockRequest = array(
    'method' => 'TestStack.Test.ping',
    'params' => array(),
);


if  (
    (isset($_GET))
    && (isset($_GET['mock_enabled']))
) {
    $mockEnabled = (json_decode($_GET['mock_enabled'], true)===true);
}
if  (
    (isset($_GET))
    && (isset($_GET['debug_enabled']))
) {
    $debugEnabled = (json_decode($_GET['debug_enabled'], true)===true);
}

$startTS = microtime(true);

$gtw = new GatewayModule();
$gtw->init();
$gtw->setIsAutoFetchRequestTextEnabled(true);
$gtw->setIsAutoEmitResponseEnabled(true);
$gtw->setIsDebugEnabled($debugEnabled);
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
            'debugEnabled'=>$debugEnabled,
            'mockEnabled'=>$mockEnabled,
            'mockRequest'=>$mockRequest,
        ));
}