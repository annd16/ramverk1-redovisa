<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "IpValidator.",
            "mount" => "ip",
            "handler" => "\Anna\IpValidator\IpValidatorController",
        ],
    ]
];
