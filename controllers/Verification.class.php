<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/28/2018
 * Time: 5:47 AM
 */

namespace Assassins;


use Underline\Module\ConfigModule;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

class Verification extends TemplateController {

    /**
     * Verification constructor.
     *
     * @param ConfigModule  $configModule  The config module to use.
     * @param SessionModule $sessionModule The session module to use.
     * @param CookieModule  $cookieModule  The cookie module to use.
     */
    public function __construct(ConfigModule $configModule, SessionModule $sessionModule, CookieModule $cookieModule) {
        parent::__construct($configModule, $sessionModule, $cookieModule);
    }

    /**
     * Default controller load.
     */
    public function defaultLoad(): void {
        // If invalid request
        if (!isset($_GET)) {
            header('Location: ./login');
        }
        // Check if vars are set
        if (!is_numeric($_GET['id']) || strlen($_GET['token']) != 32) {
            header('Location: ./login');
        }
        $extraInfo = array();
        // Check if user exists
        $user = \UserQuery::create()->findOneById($_GET['id']);
        if ($user == null) {
            $extraInfo['swalType'] = 'error';
            $extraInfo['swalText'] = 'Invalid user. If you believe this is an error please contact the site admin.';
            $this->setExtraInformation($extraInfo);
        } else {
            // Verify user
            $response = DatabaseHelp::verifyUser($user, $_GET['token']);
            if ($response == -3) {
                $extraInfo['swalType'] = 'error';
                $extraInfo['swalText'] = 'Internal error. If you believe this is an error please contact the site admin.';
            } else if ($response == -2) {
                $extraInfo['swalType'] = 'error';
                $extraInfo['swalText'] = 'Invalid verification token. If you believe this is an error please contact the site admin.';
            } else if ($response == -1) {
                $extraInfo['swalType'] = 'error';
                $extraInfo['swalText'] = 'Invalid user. If you believe this is an error please contact the site admin.';
            } else if ($response == 0) {
                $extraInfo['swalType'] = 'warning';
                $extraInfo['swalText'] = 'User is already verified.';
            } else if ($response == 1) {
                $extraInfo['swalType'] = 'success';
                $extraInfo['swalText'] = 'User has been successful verified.';
            }
            // Set info
            $this->setExtraInformation($extraInfo);
        }
    }

    /**
     * @param array $postData The post data for the controller.
     */
    public function post(array $postData): void {
        // TODO: Implement post() method.
    }

    /**
     * @param array $data The data for ajax request.
     *
     * @return array The info to send back to the client.
     */
    public function ajax(array $data): array {
        // TODO: Implement ajax() method.
    }
}