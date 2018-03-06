<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 1/21/2018
 * Time: 1:47 AM
 */

namespace Assassins;


use Underline\Module\ConfigModule;
use Underline\Module\Controller;
use Underline\Module\CookieModule;
use Underline\Module\SessionModule;

/**
 * Class TemplateController Allows rendering of templates from the controller.
 * @package Assassins
 */
abstract class TemplateController extends Controller {

    /**
     * @var SessionModule The session module to use.
     */
    private $sessionModule;

    /**
     * @var CookieModule The cookie module to use.
     */
    private $cookieModule;

    /**
     * @var array An array of extra info to pass to the template.
     */
    private $extraInformation = array();

    /**
     * TemplateController constructor.
     *
     * @param ConfigModule  $configModule  The configuration module to use.
     * @param SessionModule $sessionModule The session module to use.
     * @param CookieModule  $cookieModule  The cookie module to use.
     */
    public function __construct(ConfigModule $configModule, SessionModule $sessionModule, CookieModule $cookieModule) {
        parent::__construct($configModule);
        $this->sessionModule = $sessionModule;
        $this->cookieModule = $cookieModule;
    }

    /**
     * @return SessionModule The session module.
     */
    public function getSessionModule(): SessionModule {
        return $this->sessionModule;
    }

    /**
     * @return CookieModule The cookie module.
     */
    public function getCookieModule(): CookieModule {
        return $this->cookieModule;
    }

    /**
     * @return array An array of extra info to pass to the template.
     */
    public function getExtraInformation(): array {
        return $this->extraInformation;
    }

    /**
     * @param array $extraInformation An array of extra info to pass to the template.
     */
    public function setExtraInformation(array $extraInformation): void {
        $this->extraInformation = $extraInformation;
    }

    /**
     * Default controller load.
     */
    public abstract function defaultLoad(): void;

    /**
     * @param array $postData The post data for the controller.
     */
    public abstract function post(array $postData): void;

    /**
     * @param array $data The data for ajax request.
     *
     * @return array The info to send back to the client.
     */
    public abstract function ajax(array $data): array;
}