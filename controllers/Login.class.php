<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/25/2018
 * Time: 5:27 PM
 */

namespace Assassins;


use Propel\Runtime\Exception\PropelException;
use Underline\Module\ConfigModule;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

/**
 * Class Login Allows the user to login.
 * @package Assassins
 */
class Login extends TemplateController {

    /**
     * Login constructor.
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
        // Get cookie if present
        $userId = $this->getCookieModule()->getCookie('rememberMe') == null ? -1 :
            (int)$this->getCookieModule()->getCookie('rememberMe');
        if (DatabaseHelp::verifyDatabaseId($userId)) {
            // Check if new username is unique
            $userCheck = \UserQuery::create()->findOneById($userId);
            if ($userCheck != null) {
                $rememberMeToken = $this->getCookieModule()->getCookie('rememberMeToken');
                // Only check string tokens
                if (strlen($rememberMeToken) == 64) {
                    // Check hash
                    if (hash_equals($userCheck->getCookieToken(), $rememberMeToken)) {
                        try {
                            $cookieToken = hash('sha256', bin2hex(random_bytes(32)));
                            $userCheck->setCookieToken($cookieToken);
                            $userCheck->save();
                            // Set cookie on client
                            $this->getCookieModule()->setCookie('rememberMeToken', $cookieToken);
                            // Set user session id if unset
                            if ($this->getSessionModule()->getData('userId') == -1) {
                                $this->getSessionModule()->setData('userId', $userCheck->getId());
                            }
                            // Redirect user
                            header('Location: ./dashboard');
                        } catch (\Exception $e) {
                            GlobalStatic::$logger->critical('Error creating cookie token: ' . $e->getMessage());
                        }
                    } else {
                        // Assume stolen cookie delete token and save user.
                        try {
                            $userCheck->setCookieToken('');
                            $userCheck->save();
                        } catch (PropelException $e) {
                            GlobalStatic::$logger->critical('Error saving user: ' . $e->getMessage());
                        }
                    }
                }
            }
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
        // If user is signed in redirect to dashboard
        if (!$this->permissionCheck()) {
            header('Location: ./dashboard');
        }
        // check for to many login attempts
        if ($this->getSessionModule()->getData('loginAttempt') >= 5) {
            $ajaxResponse['error'] = 'Too many login attempts. Please wait 30 minutes before trying again.';
        }
        $ajaxResponse = array();
        if (!isset($data['login-username']) || !isset($data['login-password'])) {
            $ajaxResponse['error'] = 'No fields set.';
            return $ajaxResponse;
        }
        // add to login count
        $this->getSessionModule()->setData('loginAttempt',
            $this->getSessionModule()->getData('loginAttempt') + 1);
        // Validate username
        if (!DatabaseHelp::verifyDatabaseUsername($data['login-username'])) {
            GlobalStatic::$logger->warning('Invalid username.');
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Check if new username is unique
        $userCheck = \UserQuery::create()->findOneByUsername($data['login-username']);
        if ($userCheck == null) {
            GlobalStatic::$logger->warning('User does not exist.');
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Check if passwords match
        if (!password_verify($data['login-password'], $userCheck->getPassword())) {
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Check if user is active
        if (!$userCheck->isActive()) {
            $ajaxResponse['error'] = 'Account not active.';
            $ajaxResponse['active'] = false;
            return $ajaxResponse;
        }
        // Add cookie if remember me
        if (isset($data['login-remember-me']) && $data['login-remember-me'] == true) {
            try {
                $cookieToken = hash('sha256', bin2hex(random_bytes(32)));
                $userCheck->setCookieToken($cookieToken);
                $userCheck->save();
                // Set cookie on client
                $this->getCookieModule()->setCookie('rememberMe', $userCheck->getId());
                $this->getCookieModule()->setCookie('rememberMeToken', $cookieToken);
            } catch (\Exception $e) {
                GlobalStatic::$logger->critical('Error creating cookie token: ' . $e->getMessage());
            }
        }
        // return success
        $this->getSessionModule()->setData('userId', $userCheck->getId());
        $ajaxResponse['success'] = 'User logged in.';
        return $ajaxResponse;
    }
}