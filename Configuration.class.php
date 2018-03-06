<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 12/11/2017
 * Time: 12:02 AM
 */

namespace Assassins;


use Monolog\Logger;
use Underline\Module\ConfigModule;

class Configuration extends ConfigModule {

    /**
     * @var string The base url of this app.
     */
    private $baseUrl = '';

    /**
     * @var string Should the library log any api issues.
     */
    private $smtpHostAddress = 'localhost';

    /**
     * @var int The port to connect to.
     */
    private $smtpPort = 25;

    /**
     * @var string The username to use when connecting to the smtp server.
     */
    private $smtpUsername = "";

    /**
     * @var string The password to use when connecting to the smtp server.
     */
    private $smtpPassword = "";

    /**
     * @var string What encryption type to use when connecting to the smtp server.
     */
    private $smtpEncryption = "";

    /**
     * @var string The address to send emails to.
     */
    private $smtpToAddress = "";

    /**
     * @var string The name of the toEmail address.
     */
    private $smtpToName = "";

    /**
     * @var string The email address to send emails from.
     */
    private $smtpFromAddress = "";

    /**
     * @var string The name of the fromEmail address.
     */
    private $smtpFromName = "";

    /**
     * @var int Current logging level for the api logger.
     */
    private $apiLogLevel = Logger::EMERGENCY;

    /**
     * @var int Current logging level for the primary logger.
     */
    private $primaryLogLevel = Logger::EMERGENCY;

    /**
     * @var string The application log file.
     */
    private $primaryLogFile = 'application.log';

    /**
     * @var string The php offset name.
     */
    private $timeZoneOffset = 'America/Chicago';

    /**
     * @var string The path to the global template.
     */
    private $dashboardGlobalTemplate = '';

    /**
     * @var int The id of the super admin user.
     */
    private $superUserId = 0;

    /**
     * @return array of config values currently set.
     */
    public function exportConfigurationArray(): array {
        $exportArray = array();
        foreach (get_object_vars($this) as $key => $value) {
            $exportArray[$key] = $value;
        }
        return array_merge(parent::exportConfigurationArray(), $exportArray);
    }

    /**
     * @param array $configValues array of config values to set.
     */
    public function initConfigValues(array $configValues): void {
        foreach (get_object_vars($this) as $key => $value) {
            $this->{$key} = $configValues[$key];
        }
        parent::initConfigValues($configValues);
    }

    /**
     * @return string The address of the smtp host.
     */
    public function getSmtpHostAddress(): string {
        return $this->smtpHostAddress;
    }

    /**
     * @param string $smtpHostAddress The address of the smtp host.
     */
    public function setSmtpHostAddress(string $smtpHostAddress): void {
        $this->smtpHostAddress = $smtpHostAddress;
    }

    /**
     * @return int The port to connect to.
     */
    public function getSmtpPort(): int {
        return $this->smtpPort;
    }

    /**
     * @param int $smtpPort The port to connect to.
     */
    public function setSmtpPort(int $smtpPort): void {
        $this->smtpPort = $smtpPort;
    }

    /**
     * @return string The username to use when connecting to the smtp server.
     */
    public function getSmtpUsername(): string {
        return $this->smtpUsername;
    }

    /**
     * @param string $smtpUsername The username to use when connecting to the smtp server.
     */
    public function setSmtpUsername(string $smtpUsername): void {
        $this->smtpUsername = $smtpUsername;
    }

    /**
     * @return string The password to use when connecting to the smtp server.
     */
    public function getSmtpPassword(): string {
        return $this->smtpPassword;
    }

    /**
     * @param string $smtpPassword The password to use when connecting to the smtp server.
     */
    public function setSmtpPassword(string $smtpPassword): void {
        $this->smtpPassword = $smtpPassword;
    }

    /**
     * @return string What encryption type to use when connecting to the smtp server.
     */
    public function getSmtpEncryption(): string {
        return $this->smtpEncryption;
    }

    /**
     * @param string $smtpEncryption What encryption type to use when connecting to the smtp server.
     */
    public function setSmtpEncryption(string $smtpEncryption): void {
        $this->smtpEncryption = $smtpEncryption;
    }

    /**
     * @return string The address to send emails to.
     */
    public function getSmtpToAddress(): string {
        return $this->smtpToAddress;
    }

    /**
     * @param string $smtpToAddress The address to send emails to.
     */
    public function setSmtpToAddress(string $smtpToAddress): void {
        $this->smtpToAddress = $smtpToAddress;
    }

    /**
     * @return string The name of the toEmail address.
     */
    public function getSmtpToName(): string {
        return $this->smtpToName;
    }

    /**
     * @param string $smtpToName The name of the toEmail address.
     */
    public function setSmtpToName(string $smtpToName): void {
        $this->smtpToName = $smtpToName;
    }

    /**
     * @return string The email address to send emails from.
     */
    public function getSmtpFromAddress(): string {
        return $this->smtpFromAddress;
    }

    /**
     * @param string $smtpFromAddress The email address to send emails from.
     */
    public function setSmtpFromAddress(string $smtpFromAddress): void {
        $this->smtpFromAddress = $smtpFromAddress;
    }

    /**
     * @return string The name of the fromEmail address.
     */
    public function getSmtpFromName(): string {
        return $this->smtpFromName;
    }

    /**
     * @param string $smtpFromName The name of the fromEmail address.
     */
    public function setSmtpFromName(string $smtpFromName): void {
        $this->smtpFromName = $smtpFromName;
    }

    /**
     * @return string The base url of this app.
     */
    public function getBaseUrl(): string {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl The base url of this app.
     */
    public function setBaseUrl(string $baseUrl): void {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return int Current logging level for the api logger.
     */
    public function getApiLogLevel(): int {
        return $this->apiLogLevel;
    }

    /**
     * @param int $apiLogLevel logging level for the api logger.
     */
    public function setApiLogLevel(int $apiLogLevel): void {
        $this->apiLogLevel = $apiLogLevel;
    }

    /**
     * @return int Current logging level for the primary logger.
     */
    public function getPrimaryLogLevel(): int {
        return $this->primaryLogLevel;
    }

    /**
     * @param int $primaryLogLevel logging level for the primary logger.
     */
    public function setPrimaryLogLevel(int $primaryLogLevel): void {
        $this->primaryLogLevel = $primaryLogLevel;
    }

    /**
     * @return string The application log file.
     */
    public function getPrimaryLogFile(): string {
        return $this->primaryLogFile;
    }

    /**
     * @param string $primaryLogFile The application log file.
     */
    public function setPrimaryLogFile(string $primaryLogFile): void {
        $this->primaryLogFile = $primaryLogFile;
    }

    /**
     * @return string The php offset name.
     */
    public function getTimeZoneOffset(): string {
        return $this->timeZoneOffset;
    }

    /**
     * @param string $timeZoneOffset The php offset name.
     */
    public function setTimeZoneOffset(string $timeZoneOffset): void {
        $this->timeZoneOffset = $timeZoneOffset;
    }

    /**
     * @return string The path to the global template.
     */
    public function getDashboardGlobalTemplate(): string {
        return $this->dashboardGlobalTemplate;
    }

    /**
     * @param string $dashboardGlobalTemplate The path to the global template.
     */
    public function setDashboardGlobalTemplate(string $dashboardGlobalTemplate): void {
        $this->dashboardGlobalTemplate = $dashboardGlobalTemplate;
    }

    /**
     * @return int The id of the super admin user.
     */
    public function getSuperUserId(): int {
        return $this->superUserId;
    }

    /**
     * @param int $superUserId The id of the super admin user.
     */
    public function setSuperUserId(int $superUserId): void {
        $this->superUserId = $superUserId;
    }
}