<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Weather.",
            "mount" => "weather",
            "handler" => "\Anna\Weather\WeatherController",
        ],
    ]
];
