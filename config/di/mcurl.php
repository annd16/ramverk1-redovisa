<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "mcurl" => [
            "shared" => true,
            "callback" => function () {
                // $obj = new \Anax\Request\Request();
                // // Test 181227 to get the unit test to pass
                // $obj = new \Anna\Request\RequestUnit();
                $obj = new \Anna\Curl\CurlMulti();
                // $obj->init();
                return $obj;
            }
        ],
    ],
];
