<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Wheather.",
            "mount" => "weather",
            "handler" => "\Anna\Weather\WeatherController",
        ],
    ]
];
