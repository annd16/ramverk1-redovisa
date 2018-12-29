<?php
/**
* Module cantaining IpValidator class that analyzes ip:s.
*/

/**
 * A module for IpValidator class.
 *
 * This is the module containing the IpValidator class that analyzes ip:s.
 *
 * @author  Anna
 */

namespace Anna\IpValidator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class IpValidator implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

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
    public static function checkIfValidIp($ipAddress)
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // echo("$ipAddress is a valid IPv4 address");
            // $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
            // $session->set(self::$key, $key);
            return "Ipv4";
        } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // echo("$ipAddress is a valid IPv6 address");
            // $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
            // $session->set(self::$key, $key);
            return "Ipv6";
        } else {
            return false;
        }
    }

    /**
     * IpValidator::checkIfAdressIsPrivOrRes()
     *
     * Check if IP adddress is private or reserved.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - string with "private" or "reserved", or false if neither.
     */
    public static function checkIfAdressIsPrivOrRes($ipAddress)
    {
        if ($ipAddress === "" || $ipAddress === null) {
            return false;
        } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            // echo("$ipAddress is not a private IPv4 address");
            // $session->set(self::$key, $key);
            return "private";
        } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
            // echo("$ipAddress is not a reserved IPv6 address");
            // $session->set(self::$key, $key);
            return "reserved";
        } else {
            return false;
        }
    }


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
    public static function getClientIpServer($request)
    {
            $ipAddress = '';
            // Show incoming variables and view helper functions
            // echo \Anax\View\showEnvironment(get_defined_vars(), get_defined_functions());
            // var_dump($request);
            // die();
        if ($request->getServer('HTTP_CLIENT_IP')) {
            $ipAddress = $request->getServer('HTTP_CLIENT_IP');
        } else if ($request->getServer('HTTP_X_FORWARDED_FOR')) {
            $ipAddress = $request->getServer('HTTP_X_FORWARDED_FOR');
        } else if ($request->getServer('HTTP_X_FORWARDED')) {
            $ipAddress = $request->getServer('HTTP_X_FORWARDED');
        } else if ($request->getServer('HTTP_FORWARDED_FOR')) {
            $ipAddress = $request->getServer('HTTP_FORWARDED_FOR');
        } else if ($request->getServer('HTTP_FORWARDED')) {
            $ipAddress = $request->getServer('HTTP_FORWARDED');
        } else if ($request->getServer('REMOTE_ADDR')) {
            $ipAddress = $request->getServer('REMOTE_ADDR');
        } else {
            $ipAddress = 'unknown';
        }
            return $ipAddress;
    }
}
