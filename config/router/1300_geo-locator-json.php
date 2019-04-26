<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "GeoLocatorJson.",
            "mount" => "geo",
            "handler" => "\Anna\GeoLocatorJson\GeoLocatorJsonController",
        ],
    ]
];
