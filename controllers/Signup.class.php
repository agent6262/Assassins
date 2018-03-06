<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/23/2018
 * Time: 6:08 PM
 */

namespace Assassins;


use Underline\Module\ConfigModule;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

/**
 * Class Signup Allows a user to sign up for the site.
 * @package Assassins
 */
class Signup extends TemplateController {

    /**
     * Signup constructor.
     *
     * @param ConfigModule  $configModule  The config module to use.
     * @param SessionModule $sessionModule The session module to use.
     * @param CookieModule  $cookieModule  The cookie module to use.
     */
    public function __construct(ConfigModule $configModule, SessionModule $sessionModule, CookieModule $cookieModule) {
        parent::__construct($configModule, $sessionModule, $cookieModule);
    }

    /**
     * @return bool True if use has permission false if not.
     */
    private function permissionCheck(): bool {
        // Check if user is signed in
        if ($this->getSessionModule()->getData('userId') > -1) {
            return false;
        }
        return true;
    }

    /**
     * Default controller load.
     */
    public function defaultLoad(): void {
        // If user is signed in redirect to dashboard
        if (!$this->permissionCheck()) {
            header('Location: ./dashboard');
        }
    }

    /**
     * @param array $postData The post data for the controller.
     */
    public function post(array $postData): void {
        // If user is signed in redirect to dashboard
        if (!$this->permissionCheck()) {
            header('Location: ./dashboard');
        }
    }

    /**
     * @param array $data The data for ajax request.
     *
     * @return array The info to send back to the client.
     */
    public function ajax(array $data): array {
        $ajaxResponse = array();
        if (!isset($data['register-password']) || !isset($data['register-password2']) || !isset($data['register-email'])
            || !isset($data['register-username']) || !isset($data['register-realname']) || !isset($data['register-password'])) {
            $ajaxResponse['error'] = 'No fields set.';
            return $ajaxResponse;
        }
        // double check passwords
        if ($data['register-password2'] != $data['register-password']) {
            $ajaxResponse['error'] = 'Passwords do not match.';
            return $ajaxResponse;
        }
        // Try and create user
        $response = DatabaseHelp::createUser($data['register-email'],
            $data['register-username'],
            $data['register-realname'],
            $data['register-password'],
            true
        );
        // Send info back based on response
        if ($response == -7) {
            $ajaxResponse['error'] = 'User creation error. Please contact site admin.';
        } else if ($response == -6) {
            $ajaxResponse['error'] = 'Invalid password. If you believe this is an error please contact the site admin.';
        } else if ($response == -5) {
            $ajaxResponse['error'] = 'Invalid real name. If you believe this is an error please contact the site admin.';
        } else if ($response == -4) {
            $ajaxResponse['error'] = 'Username already in use.';
        } else if ($response == -3) {
            $ajaxResponse['error'] = 'Invalid username. If you believe this is an error please contact the site admin.';
        } else if ($response == -2) {
            $ajaxResponse['error'] = 'Email address already in use.';
        } else if ($response == -1) {
            $ajaxResponse['error'] = 'Invalid email address. If you believe this is an error please contact the site admin.';
        } else if ($response == 0) {
            $ajaxResponse['warning'] = 'User account successfully created however the conformation email failed to be 
            sent to the following address: ' . $data['register-email'] . '. If you believe this is an error please 
            contact the site admin.';
        } else if ($response == 1) {
            $ajaxResponse['success'] = 'User account successfully created. Please check your mail for the conformation email.';
        } else if ($response == 2) {
            $ajaxResponse['success'] = 'User account successfully created.';
        }
        // return response
        return $ajaxResponse;
    }
}