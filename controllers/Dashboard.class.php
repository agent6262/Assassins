<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/29/2018
 * Time: 1:35 PM
 */

namespace Assassins;


use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Underline\Module\ConfigModule;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

/**
 * Class Dashboard The base dashboard for users.
 * @package Assassins
 */
class Dashboard extends TemplateController {

    /**
     * Dashboard constructor.
     *
     * @param ConfigModule  $configModule  The config module to use.
     * @param SessionModule $sessionModule The session module to use.
     * @param CookieModule  $cookieModule  The cookie module to use.
     */
    public function __construct(ConfigModule $configModule, SessionModule $sessionModule, CookieModule $cookieModule) {
        parent::__construct($configModule, $sessionModule, $cookieModule);
    }

    /**
     * @return array Array of info for the DashboardGlobal template.
     */
    private function controllerInfo(): array {
        return array(
            'title' => 'Dashboard',
            'controller' => 'dashboard'
        );
    }

    /**
     * @return bool True if use has permission false if not.
     */
    private function permissionCheck(): bool {
        // Check if user is signed in
        if ($this->getSessionModule()->getData('userId') < 0) {
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
            header('Location: ./login');
        }
        // Extra info to pass to template
        $extraInfo = array();
        // Get user info
        $user = \UserQuery::create()->findOneById($this->getSessionModule()->getData('userId'));
        if ($user != null) {
            $extraInfo['currentGames'] = 0;
            try {
                $extraInfo['currentGames'] = $user->getOwnedGames()->count();
            } catch (PropelException $e) {
                GlobalStatic::$logger->critical('Error trying to get owned games: ' . $e->getMessage());
            }
            $extraInfo['totalCurrentGames'] = $user->getGames()->count();
            $extraInfo['currentMoney'] = $user->getMoney();
            $extraInfo['totalMoney'] = $user->getTotalMoney();
            $extraInfo['username'] = $user->getUsername();
        }
        // Set global template info
        $this->setExtraInformation(array_merge($this->controllerInfo(), $extraInfo));
    }

    /**
     * @param array $postData The post data for the controller.
     */
    public function post(array $postData): void {
        // If user is signed in redirect to dashboard
        if (!$this->permissionCheck()) {
            header('Location: ./login');
        }
        // Set global template info
        $this->setExtraInformation($this->controllerInfo());
    }

    /**
     * @param array $data The data for ajax request.
     *
     * @return array The info to send back to the client.
     */
    public function ajax(array $data): array {
        // If user is signed in redirect to dashboard
        if (!$this->permissionCheck()) {
            header('Location: ./login');
        }
        // check function
        if (!isset($data['function'])) {
            return array('error' => 'Invalid function.');
        }
        if ($data['function'] == 'chartMoney') {
            return $this->chartMoney($data);
        } else {
            return array('error' => 'Invalid function.');
        }
    }

    private function chartMoney(array $data): array {
        $ajaxData = array();
        $user = \UserQuery::create()->findOneById($this->getSessionModule()->getData('userId'));
        if ($user != null) {
            // get times
            $today = strtotime('today');
            $lastWeekStart = strtotime('-13 days', $today);
            $thisWeekStart = strtotime('-6 days', $today);
            // get data labels
            $ajaxData['lastWeekDays'] = array();
            for ($i = 0; $i < 7; $i++) {
                array_push($ajaxData['lastWeekDays'],
                    strtoupper(date('D', strtotime("+$i days", $thisWeekStart))));
            }
            // Get last week data
            for ($i = 0; $i < 7; $i++) {
                $money = 0;
                try {
                    // Current list
                    $list = \CircuitPlayerQuery::create()
                                                 ->filterByPlayer($user)
                                                 ->filterByDateCompleted(array('min' => 'asd', 'max' => 'yesterday'))
                                                 ->find();
                    // add money
                    foreach ($list as $player) {
                        $money += $player->getPay();
                        $money -= $player->getMoneySpent();
                    }
                } catch (PropelException $e) {
                }
                array_push($ajaxData['lastWeekDays'],
                    strtoupper(date('D', strtotime("+$i days", $thisWeekStart))));
            }
        }
        return $ajaxData;
    }
}