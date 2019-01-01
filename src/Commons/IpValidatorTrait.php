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

namespace Anna\Commons;

// use Anax\Commons\ContainerInjectableInterface;
// use Anax\Commons\ContainerInjectableTrait;
use IpValidatorInterface;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
trait IpValidatorTrait
{
    // use ContainerInjectableTrait;


    /**
     * IpValidatorTrait::checkIfValidIp()
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
     * IpValidatorTrait::checkIfAdressIsPrivOrRes()
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
     * IpValidatorTrait::getClientIpServer()
     *
     * Get the client ip Address from the $_SERVER if available.
     *
     * @param object $request - the request object
     *
     * @return string - the IP-address on success, else "UNKNOWN" is returned.
     */
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
