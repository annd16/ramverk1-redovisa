<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "form5" => [
            //"shared" => true,
            "shared" => false,      // Needs to be false to be able to create more than one object
            "callback" => function () {
                // $obj = new \Anax\Request\Request();
                // // Test 181227 to get the unit test to pass
                // $obj = new \Anna\Request\RequestUnit();
                $obj = new \Anna\Form4\Form5();
                // $obj->init();
                return $obj;
            }
        ],
    ],
];
