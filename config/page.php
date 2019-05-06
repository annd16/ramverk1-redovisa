<?php
/**
 * Configuration file for page which can create and put together web pages
 * from a collection of views. Through configuration you can add the
 * standard parts of the page, such as header, navbar, footer, stylesheets,
 * javascripts and more.
 */
return [
    // This layout view is the base for rendering the page, it decides on where
    // all the other views are rendered.
    "layout" => [
        "region" => "layout",
        "template" => "anax/v2/layout/dbwebb_se",
        "data" => [
            "baseTitle" => " | ramverk1",
            "bodyClass" => null,
            "favicon" => "favicon.ico",
            "htmlClass" => null,
            "lang" => "sv",
            "stylesheets" => [
                // "css/dbwebb-se.min.css",
                // "css/dbwebb-se_v2.min.css",
                // "theme/htdocs/css/framework1_kmom01.min.css",
                "css/framework1_kmom01.min.css",
            ],
            "javascripts" => [
                "js/responsive-menu.js",
                //  "http://www.openlayers.org/api/OpenLayers.js",
                 "js/open-layers.js"            // Om jag kommenterar bort denna fungerar det INTE!
            ],
        ],
    ],

    // These views are always loaded into the collection of views.
    "views" => [
        [
            "region" => "header-col-1",
            "template" => "anax/v2/header/site_logo",
            "data" => [
                "class" => "large",
                // "siteLogo"      => "image/theme/leaf_256x256.png",
                "siteLogo"      => "image/theme/Frame192magenta_276x300px.png",
                "siteLogoAlt"   => "A frame",
            ],
        ],
        [
            "region" => "header-col-1",
            "template" => "anax/v2/header/site_logo_text",
            "data" => [
                "homeLink"      => "",
                "siteLogoText"  => "framework1",
                // "siteLogoText"  => "",
                // "siteLogoTextIcon" => "image/theme/leaf_40x40.png",
                "siteLogoTextIcon" => "image/theme/Frame192magenta_40x40px.png",
                "siteLogoTextIconAlt" => "A frame",
            ],
        ],
        [
            "region" => "header-col-2",
            "template" => "anax/v2/navbar/navbar_submenus",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar/header.php",
            ],
        ],
        [
            "region" => "header-col-3",
            "template" => "anax/v2/navbar/responsive_submenus",
            "data" => [
                "navbarConfig" => require __DIR__ . "/navbar/responsive.php",
            ],
        ],
        [
            // flashbild på alla sidor.
            "region" => "flash",
            "template" => "anax/v2/image/default",
            "data" => [
                "src" => "image/theme/Frame192yellow_flash_1014x150px.png?width=1028&height=150",
                "alt" => "A flashimage should be seen here...",
            ],
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/columns/multiple_columns",
            "data" => [
                "class"  => "footer-column",
                "columns" => [
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-1",
                    ],
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-2",
                    ],
                    [
                        "template" => "anax/v2/block/default",
                        "contentRoute" => "block/footer-col-3",
                    ]
                ]
            ],
            "sort" => 1
        ],
        [
            "region" => "footer",
            "template" => "anax/v2/block/default",
            "data" => [
                "class"  => "site-footer",
                "contentRoute" => "block/footer",
            ],
            "sort" => 2
        ],
    ],
];
