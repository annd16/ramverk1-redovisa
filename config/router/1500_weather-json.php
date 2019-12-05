<?php
/**
 * Load the ip validator as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "WheatherJson.",
            "mount" => "weather",
            "handler" => "\Anna\WeatherJson\WeatherJsonController",
        ],
    ]
];
