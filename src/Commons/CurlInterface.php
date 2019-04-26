<?php

/**
 * A module for CurlInterface class.
 *
 * This is the module containing the IpValidatorInterface class for IP-analyzations.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use IpValidatorInterface;

/**
 * IpValidatorInterface
 *
 * Ip-analyzation.
 */
interface CurlInterface
{
    // use ContainerInjectableTrait;

    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    // private static $key = "AnaxCurl";

    // private $di;


    // /**
    //  * Set up an Curl object
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     global $di;
    //
    //     // Setup di
    //     $this->di = new \Anax\DI\DIFactoryConfig();
    //     $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
    //
    //     // View helpers uses the global $di so it needs its value
    //     $di = $this->di;
    //
    //     // // Setup the controller
    //     // $this->controller = new CurlJsonController();
    //     // $this->controller->setDI($this->di);
    //
    //     $this->response = new \Anax\Response\Response();
    //     $this->request = new \Anax\Request\Request();
    //     // $this->session = new  \Anax\Session\Session();
    // }



    /**
     * CurlInterface::init()
     *
     * Initialize a cURL session
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed(?) - returns a cURL handle on success, FALSE on errors.
     */
     // Initialize a cURL session
     // and store it in $curl (which is a so called cUrl handle)
     // $curl = $this->init();
    public function init();

    // /**
    //  * CurlInterface::init()
    //  *
    //  * Check if valid IP.
    //  *
    //  * @param string $ipAddress - the IP address to check
    //  *
    //  * @return mixed - the IP-version as a string if valid, or false otherwise.
    //  */
    // // public function setOptionsArray($curl, $array, $url);
    // public function setOptionsArray($array, $url);


    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function setOptionsArray($curl, $array, $url);
    public function setOptionsArray($array);


    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public function exec();
    // public function exec($curl);

    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function getInfo($curl);
    public function getInfo();

    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function close($curl);
    public function close();
}
