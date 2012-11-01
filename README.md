processus-jsonrpc-pipe
======================

yet another json-rpc


Example: ZMQ Server (Task)
==========================

cd src/example-teststack/bin
php runtask.php Zmq.TestStackClient

Example: ZMQ Client (Task)
==========================

cd src/example-teststack/bin
php runtask.php Zmq.TestStackClient


Example: Api Request
====================
// file: example-teststack/htdocs/api/v1/index.php

$mockEnabled = true;
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
    $gtw->setIsAutoFetchRequestTextEnabled(false); // do not fetch from php://input
    $gtw->setRawRequestData($mockRequest); // use that mock data instead
}
$gtw->run();