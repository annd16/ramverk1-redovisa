<?php

/**
 * A module for IpValidatorControllerJson class.
 *
* Module with IpValidatorControllerJson class that contains routes for IpValidation - json-implementation.
*/

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


    /**
     * Get data sent with post method, analyze it and return it as json.
     *
     * @return array
     */
    public function jsonActionPost() : array
    {
        $response = $this->di->get("response");
        $request = $this->di->get("request");
        $session = $this->di->get("session");

        $json = [];

        // $key = $request->getPost("ipvalidator");
        //
        $ipAddress = htmlentities($request->getPost("ipAddress"));

        // $session->set("flashmessage", "The Ip form was sent with POST.");

        // foreach ($ipAddresses as $key => $ipAddress) {
            $ipJson  =
                [   "ip" => "",
                    "version" => "",
                    "type" => "",
                    "host" => "",
                    "message" => "",
                ];
            $ip = \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);
            // echo "ip = ";
            // var_dump($ip);
            // die();
            if ($ip) {
                // echo "ip!!!";
                $ipJson["ip"] = $ipAddress;
                $ipJson["version"] = $ip;
                $ipJson["message"] = "$ipAddress is a valid $ip address";
                $isPrivOrRes =  \Anna\IpValidator\IpValidator::checkIfAdressIsPrivOrRes($ipAddress);
                if ($isPrivOrRes) {
                    $ipJson["type"] = $isPrivOrRes;
                }
                $host = gethostbyaddr($ipAddress);
                       // echo ("\$host = " );
                       // var_dump($host);
                if (isset($host) && ($host !== false)) {
                    if ($host !== $ipAddress) {
                        $ipJson["host"] = $host;
                    }
                }
            } else {
                $ipJson["message"] = "$ipAddress is NOT a valid IP address";
            }
            // $json[] = $ipJson;
            $json = $ipJson;
        // }
        return [$json];
    }




        // /**
        //  * This sample method action takes a variadic list of arguments:
        //  * GET mountpoint/variadic/
        //  * GET mountpoint/variadic/<value>
        //  * GET mountpoint/variadic/<value>/<value>
        //  * GET mountpoint/variadic/<value>/<value>/<value>
        //  * etc.
        //  *
        //  * @param array $value as a variadic parameter.
        //  *
        //  * @return string
        //  */
        // // public function variadicActionGet(...$value) : string
        // public function jsonActionGet(...$ipAddresses) : array
        // {
        //     $messages = [];
        //     $response = $this->di->get("response");
        //     $request = $this->di->get("request");
        //     $session = $this->di->get("session");
        //
        //     $session->set("flashmessage", "The Ip form was sent with GET.");
        //     $session->set("flashmessage", "__METHOD__ ". "\$db is {$this->db}, got '" . count($ipAddresses) . "' arguments: " . implode(', ', $ipAddresses));
        //
        //     // $key = $request->getPost("ipvalidator");
        //     // $ipAddresses = $request->getGet("ipAddresses");      Fungerar inte
        //
        //     // var_dump($ipAddresses);
        //     // die();
        //     foreach ($ipAddresses as $key => $ipAdress) {
        //         if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        //             $messages[] = "$ipAdress is a valid IPv6 address";
        //         } elseif (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        //             $messages[] = "$ipAdress is a valid IPv4 address";
        //         } else {
        //             $messages[] = "$ipAdress is NOT a valid IPv4 or IPv6 address!";
        //         }
        //     }
        //
        //
        //     $json = ["messages" => $messages,
        //              "ipAddresses" => $ipAddresses];
        //     // die("inside jsonActionGet-routen");
        //     return [$json];
        //     // Deal with the action and return a response.
        //
        //
        // }


        /**
         * IpValidatorJsonController::jsonActionGet()
         *
         *Get data sent with get method, analyze it and return it as json.
         *
         * @param array $ipAddresses - an array with the incoming parameters
         *
         * @return array
         */
        // public function jsonActionGet(...$ipAddresses) : array
        public function jsonActionGet(...$ipAddresses) : array
        {
            $response = $this->di->get("response");
            $request = $this->di->get("request");
            $session = $this->di->get("session");

            $json = [];

            echo "<br/>ipAddresses in jsonActionGet() = ";
            var_dump($ipAddresses);

            // $key = $request->getPost("ipvalidator");
            //
            // $ipAddresses = htmlentities($request->getGet("ipAddresses"));

            $session->set("flashmessage", "The Ip form was sent with GET.");


            foreach ($ipAddresses as $key => $ipAddress) {

                // $ipAddress = htmlentities($request->getGet("ipAddress"));

                $ipJson  =
                    [   "ip" => "",
                        "version" => "",
                        "type" => "",
                        "host" => "",
                        "message" => "",
                    ];
                $ip = \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);
                // echo "ip = ";
                // var_dump($ip);
                // die();
                if ($ip) {
                    // echo "ip!!!";
                    $ipJson["ip"] = $ipAddress;
                    $ipJson["version"] = $ip;
                    $ipJson["message"] = "$ipAddress is a valid $ip address";
                    $isPrivOrRes = \Anna\IpValidator\IpValidator::checkIfAdressIsPrivOrRes($ipAddress);
                    if ($isPrivOrRes) {
                        $ipJson["type"] = $isPrivOrRes;
                    }
                    $host = gethostbyaddr($ipAddress);
                           // echo ("\$host = " );
                           // var_dump($host);
                    if (isset($host) && ($host !== false)) {
                        if ($host !== $ipAddress) {
                            $ipJson["host"] = $host;
                        }
                    }
                } else {
                    $ipJson["message"] = "$ipAddress is NOT a valid IP address";
                }
                $json[] = $ipJson;          // För att den ska kunna returnera flera svar!!!
                // $json = $ipJson;
            }
            return [$json];             // Kan vara en array av FLERA svar.
        }                               // Utan haklamrarna här så skickas endast första svaret!!

        /**
         * Try to access a forbidden resource.
         * ANY mountpoint/forbidden
         *
         * @return array
         */
        public function forbiddenAction() : array
        {
            // Deal with the action and return a response.
            $json = [
                "message" => __METHOD__ . ", forbidden to access.",
            ];
            return [$json, 403];
        }
}
