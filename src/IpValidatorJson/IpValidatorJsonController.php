<?php

namespace Anna\IpValidatorJson;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class IpValidatorJsonController implements ContainerInjectableInterface
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
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = "active";
    }


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



    // /**
    //  * Display the IP-validator with details on current selected style.
    //  *
    //  * @return object
    //  */
    // public function indexAction() : object
    // {
    //     $form =  require __DIR__ . "/../../config/form_ipvalidation.php";         // fungerar!
    //
    //     $formIp = null;
    //     $game = "ipvalidation";
    //     $class2 = "session";
    //     $method = "post";
    //     $title = "Ipvalidator";
    //     $mount = "ip";
    //
    //     $page = $this->di->get("page");
    //     $session = $this->di->get("session");
    //
    //     $active = $session->get(self::$key, null);
    //
    //     $formVars = \Anna\Form3\Form3::populateFormVars2($form, $this->di, true);
    //     // The game is started (i.e. a gameobject is created) in the start route, so I remove the if-statement here
    //     // if (!$dicegame && $formVars['noPlayers'] === null || isset($resCode) && $resCode === "winner") {
    //         echo "An IP form has been created";
    //         $validNames = ["ipAdress", "submit"];
    //         $formIp = new \Anna\Form3\Form3($formVars, $form, ["Web", "Json"], $validNames, 2);
    //     // }
    //
    //     $page->add("anax/ipvalidator/index", [
    //         "message" => "hello!",
    //         "styles" => $this->styles,
    //         "activeStyle" => $active,
    //         "activeShortDescription" => $this->styles[$active]["shortDescription"] ?? null,
    //         "activeLongDescription" => $this->styles[$active]["longDescription"] ?? null,
    //         "session" => $session
    //         // "formIp" => $formIp,
    //     //     "formAttrs" => [
    //     //     "game" => $game,
    //     //     "save" => $class2,
    //     //     "method" => $method
    //     // ]
    //     ]);
    //     if (isset($formIp)) {
    //     // $page->add("anax/form_start/default", $data);
    //     $page->add("anax/form/default", [
    //         "formIp" => $formIp,
    //         // "formAttrs" => $formAttrs
    //         "mount" => $mount,
    //         "formAttrs" => [
    //         // "game" => $game,
    //         "game" => "Tjoho!",
    //         "save" => $class2,
    //         "method" => $method,
    //     ]
    //     ]);
    //     }
    //
    //     return $page->render([
    //         "title" => $title,
    //     ]);
    // }


    // /**
    //  * This is the index method action, it handles:
    //  * GET METHOD mountpoint
    //  * GET METHOD mountpoint/
    //  * GET METHOD mountpoint/index
    //  *
    //  * @return array
    //  */
    // public function indexActionGet() : array
    // {
    //     // Deal with the action and return a response.
    //     $json = [
    //         "message" => __METHOD__ . ", \$db is {$this->db}",
    //     ];
    //     return [$json];
    // }


    /**
     * Update current selected style.
     *
     * @return object
     */
    public function jsonActionPost() : array
    {
        $response = $this->di->get("response");
        $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("ipvalidator");
        $ipAdress = $request->getPost("ipAdress");

        $session->set("flashmessage", "The Ip form was sent with POST.");

        if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $message = "$ipAdress is a valid IPv6 address";
        } elseif (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $message = "$ipAdress is a valid IPv4 address";
        } else {
            $message = "$ipAdress is NOT a valid IPv4 or IPv6 address!";
        }
        $json = ["message" => $message];
        die("inside jsonActionPost-routen");
        return [$json];
    }

        /**
         * This sample method action takes a variadic list of arguments:
         * GET mountpoint/variadic/
         * GET mountpoint/variadic/<value>
         * GET mountpoint/variadic/<value>/<value>
         * GET mountpoint/variadic/<value>/<value>/<value>
         * etc.
         *
         * @param array $value as a variadic parameter.
         *
         * @return string
         */
        // public function variadicActionGet(...$value) : string
        public function jsonActionGet(...$ipAddresses) : array
        {
            $messages = [];
            $response = $this->di->get("response");
            $request = $this->di->get("request");
            $session = $this->di->get("session");

            $session->set("flashmessage", "The Ip form was sent with GET.");
            $session->set("flashmessage", "__METHOD__ ". "\$db is {$this->db}, got '" . count($ipAddresses) . "' arguments: " . implode(', ', $ipAddresses));

            // $key = $request->getPost("ipvalidator");
            // $ipAddresses = $request->getGet("ipAddresses");      Fungerar inte

            // var_dump($ipAddresses);
            // die();
            foreach ($ipAddresses as $key => $ipAdress) {
                if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $messages[] = "$ipAdress is a valid IPv6 address";
                } elseif (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $messages[] = "$ipAdress is a valid IPv4 address";
                } else {
                    $messages[] = "$ipAdress is NOT a valid IPv4 or IPv6 address!";
                }
            }


            $json = ["messages" => $messages,
                     "ipAddresses" => $ipAddresses];
            // die("inside jsonActionGet-routen");
            return [$json];
            // Deal with the action and return a response.


        }


    // }



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
}
