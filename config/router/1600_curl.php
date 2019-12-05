<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Curl.",
            "mount" => "curl",
            "handler" => "\Anna\Curl\CurlController",
        ],
    ]
];
