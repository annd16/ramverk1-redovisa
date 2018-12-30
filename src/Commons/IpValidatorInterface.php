<?php
/**
* Module cantaining IpValidatorInterface class that analyzes ip:s.
*/

/**
 * A module for IpValidatorInterface class.
 *
 * This is the module containing the IpValidator class that analyzes ip:s.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
// use IpValidatorInterface;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
interface IpValidatorInterface
{
    // use ContainerInjectableTrait;

    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    // private static $key = "AnaxIpValidator";

    // private $di;


    // /**
    //  * Set up an IpValidator object
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
    //     // $this->controller = new IpValidatorJsonController();
    //     // $this->controller->setDI($this->di);
    //
    //     $this->response = new \Anax\Response\Response();
    //     $this->request = new \Anax\Request\Request();
    //     // $this->session = new  \Anax\Session\Session();
    // }

    /**
     * IpValidator::checkIfValidIp()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public static function checkIfValidIp($ipAddress);

    /**
     * IpValidator::checkIfAdressIsPrivOrRes()
     *
     * Check if IP adddress is private or reserved.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - string with "private" or "reserved", or false if neither.
     */
    public static function checkIfAdressIsPrivOrRes($ipAddress);


    /**
     * IpValidator::getClientIpServer()
     *
     * Get the client ip Address from the $_SERVER if available.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return string - the IP-address on success, else "UNKNOWN" is returned.
     */
    // public static function getClientIpServer($request) {
    public static function getClientIpServer($request);
}
