<?php

namespace Anna\Api;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class ApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    // public static $responseFromIpStack = "Intitialt!";
    public static $message = "";

    /**
     * Display the IP-validator form on a rendered page.
     *
     * @return object
     */
    public function indexAction() : object
    {
        $form =  require __DIR__ . "/../../config/form_ipvalidation.php";         // fungerar!
        $navbarConfig =  require __DIR__ . "/../../config/navbar/navbar_sub.php";         // fungerar!

        $formIp = null;
        $game = "api";
        // $class2 = "session";
        // $method = "post";
        $title = "Api";
        $mount = "api";

        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $data =
        [
            "formIp" => $formIp,
            // "formAttrs" => $formAttrs
            "mount" => $mount,
            // "responseFromDarkSky" => $responseFromDarkSky,
            // "responseObject" => $responseObject,
            "session" => $session,
            // "formAttrs" => [
            // // "game" => $game,
            // "game" => "Tjoho!",
            // "save" => $class2,
            // "method" => $method,
            // ]
            // "navbarConfig" => $navbarConfig,
            // "message" => static::$message,
        ];
        $page->add("anax/api/index", $data); 
        // if (isset($formIp)) {
        // // $page->add("anax/form_start/default", $data);
        //
        // // $page->add("anax/form/default", $data);
        // $page->add("anax/form/default", [
        //     "formIp" => $formIp,
        //     // "formAttrs" => $formAttrs
        //     "mount" => $mount,
        //     "formAttrs" => [
        //     // "game" => $game,
        //     "game" => "Tjoho!",
        //     "save" => $class2,
        //     "method" => $method,
        // ]
        // ]);
        // }

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return;
    }
}
