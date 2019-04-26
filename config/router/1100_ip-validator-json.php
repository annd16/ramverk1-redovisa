<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "IpValidatorJson.",
            "mount" => "ip",
            "handler" => "\Anna\IpValidatorJson\IpValidatorJsonController",
        ],
    ]
];
