<?php

/**
 * A module for GeoLocatorInterface class.
 *
 * This is the module containing the GeoLocatorInterface class for Geolocation.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * GeoLocatorInterface
 *
 * Geolocalization.
 */
interface GeoLocatorInterface
{
    // use ContainerInjectableTrait;

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
    public function checkIfDestroy($request, $session, $response, $mount);


    /**
    * Geolocator::getGeoLocation().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param string $ipAddress - the IP address.
    * @param array $config - the configuration array.
    * @param resource $curl2 - a curl handle.
    *
    * @return string $responseFromIpStack - the response from IpStack as a json string
    */
    public function getGeoLocation($ipAddress, $config, $curl2);


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
    public function convertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType);
}
