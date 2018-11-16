<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "IP-validator.",
            "mount" => "ip",
            "handler" => "\Anna\IpValidatorJson\IpValidatorJsonController",
        ],
    ]
];
