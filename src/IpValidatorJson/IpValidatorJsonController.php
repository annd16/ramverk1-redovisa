<?php

/**
 * A module for IpValidatorControllerJson class.
 *
* Module with IpValidatorControllerJson class that contains routes for IpValidation - json-implementation.
*/

namespace Anna\IpValidatorJson;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class IpValidatorJsonController implements ContainerInjectableInterface, IpValidatorInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;

    /**
     * Get data sent with post method, analyze it and return it as json.
     *
     * @return array
     */
    public function jsonActionPost(...$args) : array
    {
        if (count($args) === 0) {
            $request = $this->di->get("request");
        } else {
            $request = $args[0];
        }

        // 181229 Commmented away to get rid of validation error:
        // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\IpValidatorJson\IpValidatorJsonController.php:111  Avoid unused local variables such as '$response'.
        // $response = $this->di->get("response");

        // $request = $this->di->get("request");

        // 181229 Commmented away to get rid of validation error:
        // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\IpValidatorJson\IpValidatorJsonController.php:113  Avoid unused local variables such as '$session'
        // $session = $this->di->get("session");

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
            $ipType = $this->checkIfValidIp($ipAddress);
            // echo "ip = ";
            // var_dump($ip);
            // die();
            if ($ipType) {
                // echo "ip!!!";
                $ipJson["ip"] = $ipAddress;
                $ipJson["version"] = $ipType;
                $ipJson["message"] = "$ipAddress is a valid $ipType address";
                $isPrivOrRes =  $this->checkIfAdressIsPrivOrRes($ipAddress);
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


        /**
         * IpValidatorJsonController::jsonActionGet()
         *
         * Get data sent with get method, analyze it and return it as json.
         *
         * @param array $ipAddresses - an array with the incoming parameters
         *
         * @return array
         */
        // public function jsonActionGet(...$ipAddresses) : array
    public function jsonActionGet(...$ipAddresses) : array
    {
        // 181229 Commmented away to get rid of validation error:
        // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\IpValidatorJson\IpValidatorJsonController.php:232  Avoid unused local variables such as '$response'.
        // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\IpValidatorJson\IpValidatorJsonController.php:233  Avoid unused local variables such as '$request'.
        // $response = $this->di->get("response");
        // $request = $this->di->get("request");

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
            $ip = $this->checkIfValidIp($ipAddress);
            // echo "ip = ";
            // var_dump($ip);
            // die();
            if ($ip) {
                // echo "ip!!!";
                $ipJson["ip"] = $ipAddress;
                $ipJson["version"] = $ip;
                $ipJson["message"] = "$ipAddress is a valid $ip address";
                $isPrivOrRes = $this->checkIfAdressIsPrivOrRes($ipAddress);
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
        // ob_start();
        echo "Inside catchAll() in IpValidatorJsonController!";
        // $output = ob_get_contents();
        // ob_end_clean();
        return;
    }
}
