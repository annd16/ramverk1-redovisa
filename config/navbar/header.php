<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Home",
            "url" => "",
            "title" => "First page, start here.",
        ],
        [
            "text" => "Reports",
            "url" => "redovisning",
            "title" => "Reports for the kmoms.",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Kmom01",
                        "url" => "redovisning/kmom01",
                        "title" => "Redovisning för kmom01.",
                    ],
                    [
                        "text" => "Kmom02",
                        "url" => "redovisning/kmom02",
                        "title" => "Redovisning för kmom02.",
                    ],
                ],
            ],
        ],
        [
            "text" => "About",
            "url" => "om",
            "title" => "About this website.",
        ],
        [
            "text" => "IP",
            "url" => "ip",
            "title" => "IP validator.",
        ],
        [
            "text" => "Geo",
            "url" => "geo",
            "title" => "Geolocator.",
        ],
        [
            "text" => "Weather",
            "url" => "weather",
            "title" => "Weather.",
        ],
        [
            "text" => "API",
            "url" => "api",
            "title" => "API.",
        ],
        [
            "text" => "Curl",
            "url" => "curl",
            "title" => "Curl.",
        ],
        [
            "text" => "Stylechooser",
            "url" => "style",
            "title" => "Choose stylesheet.",
        ],
        [
            "text" => "Tools",
            "url" => "verktyg",
            "title" => "Tools and possibilities for development.",
        ],
    ],
];
