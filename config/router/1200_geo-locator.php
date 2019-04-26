<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "GeoLocator.",
            "mount" => "geo",
            "handler" => "\Anna\GeoLocator\GeoLocatorController",
        ],
    ]
];
