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
interface GeoLocatorInterface
{
    // use ContainerInjectableTrait;

    // public function __construct();

    public function checkIfDestroy($request, $session, $response, $mount);

    public function getGeoLocation($ipAddress, $config, $curl2);

    public function convertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType);
}
