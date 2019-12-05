<?php

/**
 * A module for GeoLocatorTrait.
 *
 * This is the module containing the GeoLocatorTrait for Geolocation.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use GeoLocatorInterface;

/**
 * GeoLocatorTrait
 *
 * Geolocalization.
 */
trait GeoLocatorTrait
{

    /**
    * GeolocatorTrait::getGeoLocation().
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
    * GeolocatorTrait::convertToJsonObject().
    *
    * Convert a response to json
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
