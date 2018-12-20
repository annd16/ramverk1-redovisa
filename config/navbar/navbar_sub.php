<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Reset Game",
            "url" => "tärning/session?reset=true",
            "title" => "Dice Game.",
        ],
        [
            "text" => "Kill Session",
            "url" => "?destroy=true",
            "title" => "Destroy",
        ],
        // [
        //     "text" => "View Game Status",
        //     "url" => "tärning/status",
        //     "title" => "Dice Game.",
        // ],
        // [
        //     "text" => "View Game Statistics",
        //     "url" => "tärning/statistics",
        //     "title" => "Dice Game.",
        // ],
    ],
];
