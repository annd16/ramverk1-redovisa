<?php

/**
 * A module for GeoLocatorInterface class.
 *
 * This is the module containing the GeoLocatorInterface class.
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
    * GeolocatorInterface::getGeoLocation().
    *
    * Get geolocation information for a given IP-address from an external API
    *
    * @param string $ipAddress - the IP address.
    * @param array $config - the configuration array.
    * @param resource $curl2 - a curl handle.
    *
    * @return string $responseFromIpStack - the response from IpStack as a json string
    */
    public function getGeoLocation($ipAddress, $config, $curl2);


    /**
    * GeolocatorInterface::convertToJsonObject().
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
    public function convertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType);
}
