<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 11/1/12
 * Time: 11:19 AM
 * To change this template use File | Settings | File Templates.
 */

// ========== bootstrap ======

    // @TODO: Bootstrap ,autoloader etc.


// ========== run ============

use Api\V1\TestStack\Modules\GatewayModule;

$gtw = new \Api\V1\TestStack\Modules\GatewayModule();
$gtw->init();
$gtw->setIsAutoFetchRequestTextEnabled(true);
$gtw->setIsAutoEmitResponseEnabled(true);
$gtw->setIsDebugEnabled(true);

$gtw->run();