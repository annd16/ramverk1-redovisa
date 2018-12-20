<?php
/**
* Module cantaining IpValidator class that analyzes ip:s.
*/

/**
 * A module for IpValidator class.
 *
 * This is the module containing the IpValidator class that analyzes ip:s.
 *
 * @author  Anna
 */

namespace Anna\IpValidator;
//
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class IpValidator implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    // private static $key = "AnaxIpValidator";

    // private $di;


    // /**
    //  * Set up an IpValidator object
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     global $di;
    //
    //     // Setup di
    //     $this->di = new \Anax\DI\DIFactoryConfig();
    //     $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
    //
    //     // View helpers uses the global $di so it needs its value
    //     $di = $this->di;
    //
    //     // // Setup the controller
    //     // $this->controller = new IpValidatorJsonController();
    //     // $this->controller->setDI($this->di);
    //
    //     $this->response = new \Anax\Response\Response();
    //     $this->request = new \Anax\Request\Request();
    //     // $this->session = new  \Anax\Session\Session();
    // }

    /**
     * IpValidator::checkIfValidIp()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public static function checkIfValidIp($ipAddress) {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // echo("$ipAddress is a valid IPv4 address");
            // $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
            // $session->set(self::$key, $key);
            return "Ipv4";
        } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // echo("$ipAddress is a valid IPv6 address");
            // $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
            // $session->set(self::$key, $key);
            return "Ipv6";
        } else {
            return false;
        }
    }

    /**
     * IpValidator::checkIfAdressIsPrivOrRes()
     *
     * Check if IP adddress is private or reserved.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - string with "private" or "reserved", or false if neither.
     */
    public static function checkIfAdressIsPrivOrRes($ipAddress) {
        if ($ipAddress === "" || $ipAddress === null) {
            return false;
        }
        elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            // echo("$ipAddress is not a private IPv4 address");
            // $session->set(self::$key, $key);
            return "private";
        } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
            // echo("$ipAddress is not a private IPv6 address");
            // $session->set(self::$key, $key);
            return "reserved";
        } else {
            return false;
        }
    }

    // /**
    //  * IpValidator::getClientIpEnv()
    //  *
    //  * Get the client ip Address from the environment ($_ENV), if available.
    //  *
    //  * @param string $ipAddress - the IP address to check
    //  *
    //  * @return string - the IP-address on success, else "UNKNOWN" is returned.
    //  */
    // public static function getClientIpEnv() {
    //     $ipAddress = '';
    //     if (getenv('HTTP_CLIENT_IP'))
    //         $ipAddress = getenv('HTTP_CLIENT_IP');
    //     else if(getenv('HTTP_X_FORWARDED_FOR'))
    //         $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    //     else if(getenv('HTTP_X_FORWARDED'))
    //         $ipAddress = getenv('HTTP_X_FORWARDED');
    //     else if(getenv('HTTP_FORWARDED_FOR'))
    //         $ipAddress = getenv('HTTP_FORWARDED_FOR');
    //     else if(getenv('HTTP_FORWARDED'))
    //         $ipAddress = getenv('HTTP_FORWARDED');
    //     else if(getenv('REMOTE_ADDR'))
    //         $ipAddress = getenv('REMOTE_ADDR');
    //     else
    //         $ipAddress = 'unknown';
    //
    //     return $ipAddress;
    // }



    /**
     * IpValidator::getClientIpServer()
     *
     * Get the client ip Address from the $_SERVER if available.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return string - the IP-address on success, else "UNKNOWN" is returned.
     */
    public static function getClientIpServer($request) {
            $ipAddress = '';
            if ($request->getServer('HTTP_CLIENT_IP')) {
                $ipAddress = $request->getServer('HTTP_CLIENT_IP');
            } else if($request->getServer('HTTP_X_FORWARDED_FOR')) {
                $ipAddress = $request->getServer('HTTP_X_FORWARDED_FOR');
            } else if($request->getServer('HTTP_X_FORWARDED')) {
                $ipAddress = $request->getServer('HTTP_X_FORWARDED');
            } else if($request->getServer('HTTP_FORWARDED_FOR')) {
                $ipAddress = $request->getServer('HTTP_FORWARDED_FOR');
            } else if($request->getServer('HTTP_FORWARDED')) {
                $ipAddress = $request->getServer('HTTP_FORWARDED');
            } else if($request->getServer('REMOTE_ADDR')) {
                $ipAddress = $request->getServer('REMOTE_ADDR');
            } else {
                $ipAddress = 'unknown';
            }
            return $ipAddress;
        }
    // }


    // /**
    //  * Display the IP-validator form on a rendered page.
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
    //     // $active = $session->get(self::$key, null);
    //
    //     $formVars = \Anna\Form3\Form3::populateFormVars2($form, $this->di, true);
    //     echo "An IP form has been created";
    //     $validNames = ["ipAddress", "submit"];
    //     $formIp = new \Anna\Form3\Form3($formVars, $form, ["Web", "Json"], $validNames, 2);
    //
    //
    //     $page->add("anax/ipvalidator/index", [
    //         "session" => $session
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
    //
    //
    //
    // /**
    //  * Get posted data, analyze it and redirect to the result page.
    //  *
    //  * @return object
    //  */
    // public function webActionPost() : object
    // {
    //     $response = $this->di->get("response");
    //     $request = $this->di->get("request");
    //     $session = $this->di->get("session");
    //     $key = $request->getPost("ipvalidator");
    //
    //     $ipAddress = htmlentities($request->getPost("ipAddress"));
    //
    //     $session->set("flashmessage", "The Ip form was sent with POST.");
    //
    //
    //     function checkIfValidIp($ipAddress) {
    //         if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    //             // echo("$ipAddress is a valid IPv4 address");
    //             // $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
    //             // $session->set(self::$key, $key);
    //             return "Ipv4";
    //         } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
    //             // echo("$ipAddress is a valid IPv6 address");
    //             // $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
    //             // $session->set(self::$key, $key);
    //             return "Ipv6";
    //         } else {
    //             return false;
    //         }
    //     }
    //
    //     function checkIfAdressIsPrivOrRes($ipAddress) {
    //         if (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
    //             // echo("$ipAddress is not a private IPv4 address");
    //             // $session->set(self::$key, $key);
    //             return "private";
    //         } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
    //             // echo("$ipAddress is not a private IPv6 address");
    //             // $session->set(self::$key, $key);
    //             return "reserved";
    //         } else {
    //             return false;
    //         }
    //     }
    //
    //
    //     $ip = checkIfValidIp($ipAddress);
    //     if ($ip) {
    //         $session->set("flashmessage", "$ipAddress is a valid $ip address.");
    //         $isPrivOrRes = checkIfAdressIsPrivOrRes($ipAddress);
    //         if ($isPrivOrRes) {
    //             $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
    //         }
    //         $host = gethostbyaddr($ipAddress);
    //                // echo ("\$host = " );
    //                // var_dump($host);
    //         if (isset($host) && ($host !== false)) {
    //             if ($host !== $ipAddress) {
    //                 $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
    //             }
    //         }
    //     } else {
    //         $session->set("flashmessage", "$ipAddress is NOT a valid IP address");
    //     }
    //     return $response->redirect("ip");
    // }
    //
    //
    //
    //
    // // /**
    // //  * Get posted data, analyze it and redirect to the result page.
    // //  *
    // //  * @return object
    // //  */
    // // public function processActionGet() : object
    // // {
    // //     $response = $this->di->get("response");
    // //     $session = $this->di->get("session");
    // //
    // //     // $key = $this->cssUrl . "/" . $style . ".css";
    // //     // $keyMin = $this->cssUrl . "/" . $style . ".min.css";
    // //
    // //     // if ($style === "none") {
    // //     //     $session->set("flashmessage", "Unsetting the style and using the default style.");
    // //     //     $session->set(self::$key, null);
    // //     // } elseif (array_key_exists($keyMin, $this->styles)) {
    // //     //     $session->set("flashmessage", "Now using the style '$keyMin'.");
    // //     //     $session->set(self::$key, $keyMin);
    // //     // } elseif (array_key_exists($key, $this->styles)) {
    // //     //     $session->set("flashmessage", "Now using the style '$key'.");
    // //     //     $session->set(self::$key, $key);
    // //     // }
    // //
    // //     $session->set("flashmessage", "The Ip form was sent with GET.");
    // //     // if ($ip === "4") {
    // //     //     $session->set("flashmessage", "The Ip form was sent with GET.");
    // //     //     // $session->set(self::$key, null);
    // //     // }
    // //
    // //     // $message = "The Ip form was sent with GET.";
    // //     // echo $message;
    // //     // die();
    // //     // } elseif (array_key_exists($keyMin, $this->styles)) {
    // //     //     $session->set("flashmessage", "Now using the style '$keyMin'.");
    // //     //     $session->set(self::$key, $keyMin);
    // //     // } elseif (array_key_exists($key, $this->styles)) {
    // //     //     $session->set("flashmessage", "Now using the style '$key'.");
    // //     //     $session->set(self::$key, $key);
    // //     // }
    // //
    // //
    // //     // $url = $session->getOnce("redirect", "style");
    // //     $url = $session->getOnce("redirect", "ip");
    // //
    // //     // die("inside processActionGet-routen");
    // //     return $response->redirect($url);
    // // }
    //
    //
    //
    // /**
    //  * Adding an optional catchAll() method will catch all actions sent to the
    //  * router. You can then reply with an actual response or return void to
    //  * allow for the router to move on to next handler.
    //  * A catchAll() handles the following, if a specific action method is not
    //  * created:
    //  * ANY METHOD mountpoint/**
    //  *
    //  * @param array $args as a variadic parameter.
    //  *
    //  * @return mixed
    //  *
    //  * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    //  */
    // public function catchAll(...$args)
    // {
    //     // Deal with the request and send an actual response, or not.
    //     //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
    //     return;
    // }
}
