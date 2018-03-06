<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/26/2018
 * Time: 11:49 PM
 */

namespace Assassins;


use Base\LtsGame;
use function Symfony\Component\Debug\Tests\testHeader;
use Underline\Module\ConfigModule;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

/**
 * Class General for general purpose ajax functions.
 * @package Assassins
 */
class General extends TemplateController {

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
     * Default controller load.
     */
    public function defaultLoad(): void {
        // TODO: Implement defaultLoad() method.
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
        $ajaxResponse = array();
        if (!isset($data['function'])) {
            $ajaxResponse['error'] = 'Invalid function.';
            return $ajaxResponse;
        }
        // Resend verification mail
        if ($data['function'] == 'verificationEmail') {
            return $this->verificationEmail($data);
        } else if ($data['function'] == 'landingPage') {
            return $this->landingPage($data);
        } else {
            $ajaxResponse['error'] = 'Invalid function.';
            return $ajaxResponse;
        }
    }

    private function verificationEmail(array $data) {
        $ajaxResponse = array();
        if (!isset($data['username']) || !isset($data['password'])) {
            $ajaxResponse['error'] = 'No fields set.';
            return $ajaxResponse;
        }
        // Validate username
        if (!DatabaseHelp::verifyDatabaseUsername($data['username'])) {
            GlobalStatic::$logger->warning('Invalid username.');
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Check if new username is unique
        $userCheck = \UserQuery::create()->findOneByUsername($data['username']);
        if ($userCheck == null) {
            GlobalStatic::$logger->warning('User does not exist.');
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Check if passwords match
        if (!password_verify($data['password'], $userCheck->getPassword())) {
            $ajaxResponse['error'] = 'Invalid username or password.';
            return $ajaxResponse;
        }
        // Prepare body
        $body = file_get_contents('body.html');
        str_replace('$username', $userCheck->getUsername(), $body);
        str_replace('$verificationLink',
            GlobalStatic::$configModule->getBaseUrl() . '/api/verification/'
            . $userCheck->getId() . '/' . $userCheck->getVerificationToken(), $body);
        // Get ready to mail
        $body = str_replace('$verificationLink',
            'https://reallifegames.net/assassins/verification?id=' . $userCheck->getId() . '&token=' .
            $userCheck->getVerificationToken(), $body);
        $body = str_replace('$username', $userCheck->getUsername(), $body);
        $mailResult = GlobalStatic::mail($userCheck->getEmail(), $userCheck->getRealName(),
            GlobalStatic::$configModule->getSmtpFromAddress(),
            GlobalStatic::$configModule->getSmtpFromName(),
            'Game account registration', $body);
        if ($mailResult == 0) {
            GlobalStatic::$logger->error("Email failed to be sent to user with id '" . $userCheck->getId() . "'.");
            $ajaxResponse['warning'] = 'Verification email failed to be sent to the following address: ' .
                $data['register-email'] . '. If you believe this is an error please contact the site admin.';
        } else {
            GlobalStatic::$logger->info("Email sent to user with id '" . $userCheck->getId() . "'.");
            $ajaxResponse['success'] = 'Verification email sent.';
        }
        return $ajaxResponse;
    }

    private function landingPage(array $data) {
        $ajaxResponse = array();
        // Count games
        $totalGames = \GameQuery::create()->count();
        $totalGames += \LtsGameQuery::create()->count();
        // Count users
        $activeUsers = \UserQuery::create()->filterByActive(true)->count();
        // Return data
        $ajaxResponse['totalGames'] = $totalGames;
        $ajaxResponse['activeUsers'] = $activeUsers;
        return $ajaxResponse;
    }
}