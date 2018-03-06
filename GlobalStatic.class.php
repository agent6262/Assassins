<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 12/11/2017
 * Time: 11:05 PM
 */

namespace Assassins;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Underline\Module\Controller;

require_once 'Configuration.class.php';

/**
 * Class GlobalStatic allows global access to some functions and variables.
 * @package Assassins
 */
class GlobalStatic {

    /**
     * @var Configuration The global configuration module.
     */
    public static $configModule;

    /**
     * @var Swift_SmtpTransport the transport layer for Swift Mailer.
     */
    public static $mailTransport;

    /**
     * @var Swift_Mailer The Swift mailer.
     */
    public static $mailer;

    /**
     * @var int System log severity level.
     */
    public static $info = 0;

    /**
     * @var int System log severity level.
     */
    public static $warning = 1;

    /**
     * @var int System log severity level.
     */
    public static $error = 2;

    /**
     * @var Logger Api file logger.
     */
    public static $apiLogger;

    /**
     * @var Logger Primary logger.
     */
    public static $logger;

    // ============================================================
    // ===================== Game Permissions =====================
    // ============================================================
    public static $playerPermission              = 0b00000000000000000000000000000001;
    public static $guessAssassinPermission       = 0b00000000000000000000000000000010;
    public static $setActivePermission           = 0b00000000000000000000000000000100;
    public static $setOwnerPermission            = 0b00000000000000000000000000001000;
    public static $setStartedPermission          = 0b00000000000000000000000000010000;
    public static $setPausedPermission           = 0b00000000000000000000000000100000;
    public static $setRulesPermission            = 0b00000000000000000000000001000000;
    public static $setInvitePermission           = 0b00000000000000000000000010000000;
    public static $setRequestInvitePermission    = 0b00000000000000000000000100000000;
    public static $setAutoJoinGroupIdPermission  = 0b00000000000000000000001000000000;
    public static $setAutoPlacePermission        = 0b00000000000000000000010000000000;
    public static $addGroupPermission            = 0b00000000000000000000100000000000;
    public static $editGroupPermission           = 0b00000000000000000001000000000000;
    public static $removeGroupPermission         = 0b00000000000000000010000000000000;
    public static $addPlayerGroupPermission      = 0b00000000000000000100000000000000;
    public static $removePlayerGroupPermission   = 0b00000000000000001000000000000000;
    public static $addCircuitPlayerPermission    = 0b00000000000000010000000000000000;
    public static $editCircuitPlayerPermission   = 0b00000000000000100000000000000000;
    public static $removeCircuitPlayerPermission = 0b00000000000001000000000000000000;

    // ============================================================
    // ===================== Game Log Actions =====================
    // ============================================================
    public static $createGame  = 0;
    public static $updateGame  = 1;
    public static $deleteGame  = 2;
    public static $addUser     = 3;
    public static $updateUser  = 4;
    public static $removeUser  = 5;
    public static $addGroup    = 6;
    public static $updateGroup = 7;
    public static $removeGroup = 8;

    /**
     * GlobalStatic constructor.
     *
     * @param string $path       The general location of the files.
     * @param string $configFile The config file name.
     *
     * @throws \Exception If Log StreamHandler cannot be constructed.
     */
    public function __construct(string $path, string $configFile) {
        // Init config
        GlobalStatic::$configModule = new Configuration();
        GlobalStatic::$configModule->init(array($path . $configFile));
        //Init mailer
        // Create the Transport
        if (GlobalStatic::$configModule->getSmtpHostAddress() == 'localhost' ||
            GlobalStatic::$configModule->getSmtpHostAddress() == '127.0.0.1') {
            GlobalStatic::$mailTransport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');
        } else {
            GlobalStatic::$mailTransport = (new Swift_SmtpTransport(GlobalStatic::$configModule->getSmtpHostAddress(),
                GlobalStatic::$configModule->getSmtpPort()))
                ->setUsername(GlobalStatic::$configModule->getSmtpUsername())
                ->setPassword(GlobalStatic::$configModule->getSmtpPassword());
        }
        // Set smtp encryption
        if (GlobalStatic::$configModule->getSmtpEncryption() !== '') {
            GlobalStatic::$mailTransport->setEncryption(GlobalStatic::$configModule->getSmtpEncryption());
        }
        // Create the Mailer using your created Transport
        GlobalStatic::$mailer = new Swift_Mailer(GlobalStatic::$mailTransport);
        // Api logger
        GlobalStatic::$apiLogger = new Logger('Api Logger');
        GlobalStatic::$apiLogger->pushHandler(new StreamHandler(
            $path . GlobalStatic::$configModule->getApiLogFile(),
            GlobalStatic::$configModule->getApiLogLevel()
        ));
        // Primary logger
        GlobalStatic::$logger = new Logger('Primary Logger');
        GlobalStatic::$logger->pushHandler(new StreamHandler(
            $path . GlobalStatic::$configModule->getPrimaryLogFile(),
            GlobalStatic::$configModule->getPrimaryLogLevel()
        ));
    }

    /**
     * @param string $toAddress
     * @param string $toName
     * @param string $fromAddress
     * @param string $fromName
     * @param string $subject
     * @param string $body
     * @param string $replyAddress
     * @param string $replyName
     *
     * @return int
     */
    public static function mail(string $toAddress, string $toName, string $fromAddress, string $fromName,
                                string $subject, string $body, string $replyAddress = '', string $replyName = '') {
        // Create a message
        $message = (new Swift_Message($subject))
            ->setFrom([$fromAddress => $fromName])
            ->setTo([$toAddress => $toName]);
        if ($replyAddress != '' && $replyName != '') {
            $message->setReplyTo([$replyAddress => $replyName]);
        }
        //$message->getHeaders()->addParameterizedHeader('Content-Type', 'text/html', ['charset=UTF-8']);
        $message->setBody($body, 'text/html');
        // Send the message
        return GlobalStatic::$mailer->send($message);
    }

    /**
     * @param TemplateController $controller
     * @param string             $filePath
     * @param bool               $renderDashboardGlobal
     * @param string             $dashboardTemplate
     */
    public static function renderTemplate(TemplateController $controller, string $filePath, bool $renderDashboardGlobal,
                                          string $dashboardTemplate) {
        $elements = $controller->getBaseElements();
        $info = $controller->getExtraInformation();
        // Check to see if the template file exists before including it
        if (file_exists($filePath)) {
            if ($renderDashboardGlobal) {
                if (file_exists($dashboardTemplate)) {
                    // Include sub template with php execution
                    ob_start();
                    /** @noinspection PhpIncludeInspection */
                    include_once $filePath;
                    $dashboardPageTemplate = ob_get_clean();
                    /** @noinspection PhpIncludeInspection */
                    include_once $dashboardTemplate;
                } else {
                    // Render 500 page
                    GlobalStatic::$logger->error('Cannot find template: ' . $filePath);
                    GlobalStatic::renderHtmlTemplate('templates/oneui/500.html');
                }
            } else {
                /** @noinspection PhpIncludeInspection */
                include_once $filePath;
            }
        } else {
            // Render 500 page
            GlobalStatic::$logger->error('Cannot find template: ' . $filePath);
            GlobalStatic::renderHtmlTemplate('templates/oneui/500.html');
        }
    }

    /**
     * @param string $filePath
     */
    public static function renderHtmlTemplate(string $filePath) {
        /** @noinspection PhpIncludeInspection */
        include $filePath;
    }
}