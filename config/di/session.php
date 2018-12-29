<?php
/**
 * Creating the session as a $di service.
 */
return [
    // Services to add to the container.
    "services" => [
        "session" => [
            "active" => defined("ANAX_WITH_SESSION") && ANAX_WITH_SESSION, // true|false
            "shared" => true,
            "callback" => function () {
                // $session = new \Anax\Session\Session();
                // Test 181227 to use my own class Session2:
                $session = new \Anna\Session\Session2();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("session");

                // Set session name
                $name = $config["config"]["name"] ?? null;
                if (is_string($name)) {
                    $session->name($name);
                }

                $session->start();

                return $session;
            }
        ],
    ],
];
