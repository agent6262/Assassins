<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/20/2018
 * Time: 12:55 AM
 */

namespace Assassins;

use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

require_once 'vendor/autoload.php';
require_once 'generated-conf/config.php';
require_once 'Underline/autoload.php';
require_once 'GlobalStatic.class.php';
require_once 'DatabaseHelp.class.php';
require_once 'TemplateController.class.php';

// Construct Underline's config module and require any needed files.
try {
    $globalStatic = new GlobalStatic('', 'config.json');
} catch (\Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
// Load up session and cookie module
$sessionModule = new SessionModule(GlobalStatic::$configModule);
$cookieModule = new CookieModule(GlobalStatic::$configModule);
// Initialize modules
$sessionModule->init();
$cookieModule->init();
// Get url info
// If first letter is lowercase make it uppercase
$args = explode('/', isset($_GET['request']) ? $_GET['request'] : '');
error_log(print_r($args, true));
// html only pages
$htmlPages = array('about', 'privacy');
if (empty($args) || $args[0] == '') {
    // Render landing page if no request
    GlobalStatic::renderHtmlTemplate('templates/oneui/Landing.html');
} else if (in_array($args[0], $htmlPages)) {
    $args[0] = ucfirst($args[0]);
    // Render html page
    GlobalStatic::renderHtmlTemplate('templates/oneui/' . $args[0] . '.html');
} else {
    $dashboardTemplate = GlobalStatic::$configModule->getDashboardGlobalTemplate();
    $useGlobalTemplate = false;
    // Check if dashboard
    if ($args[0] == 'dashboard') {
        $useGlobalTemplate = true;
        if (count($args) >= 2) {
            array_shift($args);
        }
    }
    // Prep for controller search
    $args[0] = ucfirst($args[0]);
    // Try and load controller
    // Check to see if the api file exists before including it
    if (file_exists('controllers/' . $args[0] . '.class.php')) {
        /** @noinspection PhpIncludeInspection */
        include_once 'controllers/' . $args[0] . '.class.php';
        // Initialize the api object and class name
        $controllerObject = null;
        $className = 'Assassins\\' . $args[0];
        // Check to see if the api class exists inside the file
        if (class_exists($className)) {
            $controllerObject = new $className(GlobalStatic::$configModule, $sessionModule, $cookieModule);
            // Check to see if the api implements the ControllerInterface
            if ($controllerObject instanceof TemplateController) {
                // Check if session is new
                if (empty($sessionModule->getData('exists'))) {
                    // Set user info
                    $sessionModule->setData('exists', true);
                    $sessionModule->setData('userId', -1);
                    $sessionModule->setData('loginAttempt', 0);
                }
                // Check if ajax is set then post. If none then default
                if (isset($_GET['ajax'])) {
                    header('HTTP/1.1 200 OK');
                    echo json_encode($controllerObject->ajax($_GET['ajax'] == 'get' ? $_GET : $_POST));
                } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $controllerObject->post($_POST);
                    // Call handle on controller
                    GlobalStatic::renderTemplate($controllerObject, 'templates/oneui/' . $args[0] . '.phtml',
                        $useGlobalTemplate, $dashboardTemplate);
                } else {
                    $controllerObject->defaultLoad();
                    // Call handle on controller
                    GlobalStatic::renderTemplate($controllerObject, 'templates/oneui/' . $args[0] . '.phtml',
                        $useGlobalTemplate, $dashboardTemplate);
                }
            } else {
                GlobalStatic::$logger->error('Mis-configured Controller: ' . $className);
                // render 500 page
                GlobalStatic::renderHtmlTemplate('templates/oneui/500.html');
            }
        } else {
            GlobalStatic::$logger->error('Mis-configured class: ' . $className);
            // render 500 page
            GlobalStatic::renderHtmlTemplate('templates/oneui/500.html');
        }
    } else {
        // Render 404 page
        GlobalStatic::renderHtmlTemplate('templates/oneui/404.html');
    }
}
