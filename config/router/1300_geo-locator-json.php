<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Geo-Locator.",
            "mount" => "geo",
            "handler" => "\Anna\GeoLocatorJson\GeoLocatorJsonController",
        ],
    ]
];
