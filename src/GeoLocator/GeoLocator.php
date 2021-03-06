<?php

/**
 * A module for GeoLocator class.
 *
 * This is the module containing the GeoLocator class, a model class for the GeoLocatorController.
 *
 * @author  Anna
 */

namespace Anna\GeoLocator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;
use Anna\Commons\GeoLocatorInterface;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class GeoLocator implements ContainerInjectableInterface, GeoLocatorInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;

    //
    // /**
    //  * @var string $cssUrl The baseurl to where the css files are.
    //  * @var string $cssDir The path to the directory storing css files.
    //  * @var array  $styles The styles available in the style directory.
    //  * @var string $key    The session key used to store the active style.
    //  */
    // // public static $responseFromIpStack = "Intitialt!";
    // public static $message = "";

    // private $geolocator;


    /**
    * Geolocator::__construct(). An empty constructor to be able to integrate it in the framework's DI-container?
    *
    * @return void
    */
    public function __construct()
    {
    }


    /**
    * Geolocator::checkIfDestroy().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param object $request the request object.
    * @param object $session the session object.
    * @param object $response the response object.
    * @param string $mount the mount point as a string.
    *
    * @return void
    */
    public function checkIfDestroy($request, $session, $response, $mount)
    {
        // Om destroy finns i GET så avslutas sessionen med header redirect
        if (null !== $request->getGet("destroy")) {
            // echo("\$session inside checkIfDestroy");
            // var_dump($session);
            # Delete cookies and kill session
            $session->destroy($session->get("name"));
            echo "Session has been killed!";
            //
            // echo("\$request->getGet('destroy') inside checkIfDestroy");
            // var_dump($request->getGet('destroy'));
            //
            // echo("\$session inside checkIfDestroy");
            // var_dump($session);

            // die();
            // header("Location: " . \Anax\View\url($mount.'/session'));
            $path = \Anax\View\url("$mount");
            $response->redirect($path);
            // Show incoming variables and view helper functions
            //echo showEnvironment(get_defined_vars(), get_defined_functions());
        }
    }


    /**
    * Geolocator::getGeoLocation().
    *
    * Get geolocation information for a given IP-address from an external API
    *
    * @param string $ipAddress - the IP address.
    * @param array $config - the configuration array.
    * @param resource $curl2 - a curl handle.
    *
    * @return string $responseFromIpStack - the response from IpStack as a json string
    */
    public function getGeoLocation($ipAddress, $config, $curl2)
    {
        $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";
        // $responseFromIpStack = \Anna\Curl\Curl::curlAnUrl($url);
        $responseFromIpStack = $curl2->curlAnUrl($url);
            // Show incoming variables and view helper functions
            //echo showEnvironment(get_defined_vars(), get_defined_functions());
        return $responseFromIpStack;
    }

    /**
    * Geolocator::convertToJsonObject().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param object $responseFromIpStack - the response from IpStack as a json string.
    * @param array $geoJson - the array to be sent in a json response.
    * @param string $ipAddress - the IP address.
    * @param string $ipType - the IP type (IPv4 or IPv6).
    *
    * @return json $responseFromIpStack - the response from IpStack as a json string
    */
    public function convertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType)
    {
        $responseFromIpStack = json_decode($responseFromIpStack);

        // echo "typeof2 = " . gettype($responseFromIpStack);               // ==> object

        // Sanitizing the output
        $responseFromIpStack->latitude = htmlentities($responseFromIpStack->latitude);
        $responseFromIpStack->longitude = htmlentities($responseFromIpStack->longitude);

        $geoJson["ip"] = htmlentities($ipAddress);
        $geoJson["version"] = htmlentities($ipType);
        $geoJson["latitude"] = $responseFromIpStack->latitude;
        $geoJson["longitude"] = $responseFromIpStack->longitude;
        $geoJson["country_name"] = htmlentities($responseFromIpStack->country_name);
        $geoJson["country_flag"] = htmlentities($responseFromIpStack->location->country_flag);
        // $geoJson["map"] = "https://www.openstreetmap.org/?mlat=30.486&mlon=-90.195"
        $geoJson["map"] = "https://www.openstreetmap.org/?mlat=$responseFromIpStack->latitude&mlon=$responseFromIpStack->longitude";

        return $geoJson;
    }
}
