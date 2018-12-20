<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "API.",
            "mount" => "api",
            "handler" => "\Anna\Api\ApiController",
        ],
    ]
];
