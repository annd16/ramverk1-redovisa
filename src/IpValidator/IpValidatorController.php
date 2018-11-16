<?php

namespace Anna\IpValidator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class IpValidatorController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    private $cssUrl = "css";
    private $cssDir = ANAX_INSTALL_PATH . "/htdocs/css";
    private $styles = [];
    private static $key = "AnaxIpValidator";



    /**
     * Get the session key to use to retrieve the active stylesheet.
     *
     * @return string
     */
    public static function getSessionKey() : string
    {
        return self::$key;
    }



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     foreach (glob("{$this->cssDir}/*.css") as $file) {
    //         $filename = basename($file);
    //         $url = "{$this->cssUrl}/$filename";
    //         $content = file_get_contents($file);
    //         $comment = strstr($content, "*/", true);
    //         $comment = preg_replace(["#\/\*!#", "#\*#"], "", $comment);
    //         $comment = preg_replace("#@#", "<br>@", $comment);
    //         $first = strpos($comment, ".");
    //         $short = substr($comment, 0, $first + 1);
    //         $long = substr($comment, $first + 1);
    //         $this->styles[$url] = [
    //             "shortDescription" => $short,
    //             "longDescription" => $long,
    //         ];
    //     }
    //
    //     foreach ($this->styles as $key => $value) {
    //         $isMinified = strstr($key, ".min.css", true);
    //         if ($isMinified) {
    //             unset($this->styles["$isMinified.css"]);
    //         }
    //     }
    // }



    /**
     * Display the IP-validator with details on current selected style.
     *
     * @return object
     */
    public function indexAction() : object
    {
        $form =  require __DIR__ . "/../../config/form_ipvalidation.php";         // fungerar!

        $formIp = null;
        $game = "ipvalidation";
        $class2 = "session";
        $method = "post";
        $title = "Ipvalidator";
        $mount = "ip";

        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $active = $session->get(self::$key, null);

        $formVars = \Anna\Form3\Form3::populateFormVars2($form, $this->di, true);
        // The game is started (i.e. a gameobject is created) in the start route, so I remove the if-statement here
        // if (!$dicegame && $formVars['noPlayers'] === null || isset($resCode) && $resCode === "winner") {
            echo "An IP form has been created";
            $validNames = ["ipAddress", "submit"];
            $formIp = new \Anna\Form3\Form3($formVars, $form, ["Web", "Json"], $validNames, 2);
        // }

        $page->add("anax/ipvalidator/index", [
            "message" => "hello!",
            "styles" => $this->styles,
            "activeStyle" => $active,
            "activeShortDescription" => $this->styles[$active]["shortDescription"] ?? null,
            "activeLongDescription" => $this->styles[$active]["longDescription"] ?? null,
            "session" => $session
            // "formIp" => $formIp,
        //     "formAttrs" => [
        //     "game" => $game,
        //     "save" => $class2,
        //     "method" => $method
        // ]
        ]);
        if (isset($formIp)) {
        // $page->add("anax/form_start/default", $data);
        $page->add("anax/form/default", [
            "formIp" => $formIp,
            // "formAttrs" => $formAttrs
            "mount" => $mount,
            "formAttrs" => [
            // "game" => $game,
            "game" => "Tjoho!",
            "save" => $class2,
            "method" => $method,
        ]
        ]);
        }

        return $page->render([
            "title" => $title,
        ]);
    }



    /**
     * Update current selected style.
     *
     * @return object
     */
    public function webActionPost() : object
    {
        $response = $this->di->get("response");
        $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("ipvalidator");
        $ipAddress = $request->getPost("ipAddress");

        $session->set("flashmessage", "The Ip form was sent with POST.");

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            echo("$ipAddress is a valid IPv6 address");
            $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
            $session->set(self::$key, $key);
        } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            echo("$ipAddress is a valid IPv4 address");
            $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
            $session->set(self::$key, $key);
        } else {
            echo("$ipAddress is NOT a valid IPv4 or IPv6 address!");
            $session->set("flashmessage", "$ipAddress is NOT a valid IPv4 or IPv6 address!");
        }
        $host = gethostbyaddr($ipAddress);
        if (isset($host) && $host !== $ipAddress) {
            $session->set("flashmessage", "The domain name (i.e. the host name) is $host");
        }
        return $response->redirect("ip");
    }



    /**
     * Update current selected style using a GET url and redirect to last
     * page visited.
     *
     * @param string $style the key to the style to use.
     *
     * @return object
     */
    // public function updateActionGet($ip) : object
    // public function processActionGet($ip) : object
    public function processActionGet() : object
    {
        $response = $this->di->get("response");
        $session = $this->di->get("session");

        // $key = $this->cssUrl . "/" . $style . ".css";
        // $keyMin = $this->cssUrl . "/" . $style . ".min.css";

        // if ($style === "none") {
        //     $session->set("flashmessage", "Unsetting the style and using the default style.");
        //     $session->set(self::$key, null);
        // } elseif (array_key_exists($keyMin, $this->styles)) {
        //     $session->set("flashmessage", "Now using the style '$keyMin'.");
        //     $session->set(self::$key, $keyMin);
        // } elseif (array_key_exists($key, $this->styles)) {
        //     $session->set("flashmessage", "Now using the style '$key'.");
        //     $session->set(self::$key, $key);
        // }

        $session->set("flashmessage", "The Ip form was sent with GET.");
        // if ($ip === "4") {
        //     $session->set("flashmessage", "The Ip form was sent with GET.");
        //     // $session->set(self::$key, null);
        // }

        // $message = "The Ip form was sent with GET.";
        // echo $message;
        // die();
        // } elseif (array_key_exists($keyMin, $this->styles)) {
        //     $session->set("flashmessage", "Now using the style '$keyMin'.");
        //     $session->set(self::$key, $keyMin);
        // } elseif (array_key_exists($key, $this->styles)) {
        //     $session->set("flashmessage", "Now using the style '$key'.");
        //     $session->set(self::$key, $key);
        // }


        // $url = $session->getOnce("redirect", "style");
        $url = $session->getOnce("redirect", "ip");

        // die("inside processActionGet-routen");
        return $response->redirect($url);
    }



    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. YOu can then reply with an actual response or return void to
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
