<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 11/1/12
 * Time: 1:03 PM
 * To change this template use File | Settings | File Templates.
 */

// ========== bootstrap ======

require_once dirname(__FILE__) . '/../Bootstrap.php';

Bootstrap::getInstance()
    ->init();

// ========== run ============

\Task\Runner::run();
