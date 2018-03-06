<?php

/*
 * All Rights Reserved
 *
 * Copyright (c) 2017 Tyler Bucher
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */

namespace Assassins\api;

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../Underline/autoload.php';
require_once '../GlobalStatic.class.php';
require_once '../DatabaseHelp.class.php';

use Assassins\GlobalStatic;
use Exception;
use Underline\Module\ApiModule;
use Underline\RestApi;

// Construct Underline's config module and require any needed files.
try {
    $globalStatic = new GlobalStatic('../', 'config.json');
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
//$configModule = new ConfigModule();
try {
    // Construct Underline's rest api module and initialize it.
    $apiModule = new ApiModule(GlobalStatic::$configModule);
    $apiModule->init();
    // If first letter is lowercase make it uppercase
    $args = explode('/', isset($_GET) ? $_GET['request'] : '');
    $args[0] = ucfirst($args[0]);
    // Only allow public api calls
    $restApi = new RestApi($args);
    // Return api endpoint
    echo $restApi->processApi('endpoints/', 'Assassins\api\endpoints\\');
} catch (Exception $e) {
    GlobalStatic::$apiLogger->critical('Api index error: ' . $e->getMessage());
    echo json_encode(Array('error' => $e->getMessage()));
}