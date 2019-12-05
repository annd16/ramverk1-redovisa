<?php

/**
 * A module for weatherControllerJson class.
 *
 * This is the module containing the weatherContollerJson class.
 *
 * @author  Anna
 */

namespace Anna\WeatherJson;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;

use Anna\Geolocator\Geolocator;
use Anna\Weather\Weather;

/**
 * weather Json controller converts an IP-address or geographical position into weather infromation in json fomrat
 */
class WeatherJsonController implements ContainerInjectableInterface, IpValidatorInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;


    /**
     * @var object $weather - a member variable
     */
    protected $weather;

    /**
     * @var string $message - a message to be displayed
     * @var string $dataValidationError - a message to display if validationError
     */
    public static $message = "";
    public static $dataValidationError = "";


    /**
     * WeatherController::initialize()
     *
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method to
     * setup internal properties that are commonly used by several methods.
     *
     * @return object
     */
    public function initialize() : object
    {
        // Use to initialise member variables.

        // Initialize the MODEL class Weather
        $this->weather = new Weather();
        // Set the dependency/service container to use.
        // "Injectar $di till objectet weather"
        $this->weather->setDI($this->di);

        // Returning this to be able to unittest this function
        return $this->weather;
    }


    /**
     * WeatherController::setParamsBasedOnArgsCount()
     *
     * Set parameters based on number of arguments
     *
     * @param object $diService - the $di-object.
     * @param array $argsArray - all other incoming arguments.
     * @param object $modelClass - a model class object.
     *
     * @return array with $request, $classObj and an array with the indata ($indataArray).
     */
    public function setParamsBasedOnArgsCount($diService, $argsArray, string $modelClass)
    {
        // var_dump($diService);
        // var_dump($args);
        // In BROWSER:
        $request = $diService->get("request");
        $classObj = null;
        $indataArray = [];

        // In UNITTESTS:
        foreach ($argsArray as $key => $val) {
            if (gettype($val) == "object") {
                if (get_class($val) == "Anna\Request\Request") {
                    $request = unserialize(serialize($val));
                //} elseif (get_class($val) == "Anna\Weather\Weather") {
                } elseif (get_class($val) == $modelClass) {
                    $classObj = unserialize(serialize($val));
                }
            } elseif (gettype($val) == "string") {
                // echo("A string!");
                // die();
                $indataArray[] = $val;
            }
        }
        // echo "request = ";
        // var_dump($request);
        // echo "weather = ";
        // var_dump($weather);
        // echo "ipAddresses = ";
        // var_dump($ipAddresses);
        // die();
        return [$request, $classObj, $indataArray];
    }

    /**
     * WeatherController::checkIfValidInData()
     *
     * Check if valid IP OR valid position.
     *
     * @param string $ipOrPos - the indata to check.
     * @param object $session - a session object.
     * @param string $message - an information text.
     * @param string $ipAddress - a var to hold an IP address.
     * @param string $position - a var to hold a position.
     *
     * @return array - $ipAddress, $indataType, $position, $message as an array.
     */
    public function checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position)
    {
        // Check if valid Ip
        $ipType = $this->checkIfValidIp($ipOrPos);
        static::$message .= "<br/>ipType in checkIfValidIndata = " . $ipType;
        //echo("ipType = " . $ipType);
        //die();
        if ($ipType == "Ipv4" || $ipType == "Ipv6") {
            echo "IP!!!!";
            static::$message .= "<br/>IP!!!!";
            $indataType = $ipType;
            $message .= "<br/>indataType is 'ip'";
            $ipAddress = $ipOrPos;
            $session->set('ipAddress', $ipOrPos);
            $session->delete('position');
            $position = "";

            return [$ipAddress, $indataType, $position, $message];
        }
        $position = $this->weather->checkIfValidPositionUsingRegexp($ipOrPos);
        if ($position) {
            echo "POSITION!!!!";
            static::$message .= "<br/>POSITION!!!!";
            $indataType = "position";
            $message .= "<br/>indataType is 'position'";
            $position = $ipOrPos;
            $session->set('position', $ipOrPos);
            $session->delete('ipAddress');
            $ipAddress = "";

            return [$ipAddress, $indataType, $position, $message];
        }
    }

    /**
     * WeatherController::extractIndividualParamsFromIncomingData()
     *
     * Check if valid IP OR valid position.
     *
     * @param array $resultArray - the array with the indata.
     *
     * @return array - $request, $this->weather, $indata, $typeOfRequest, $ipOrPos as an array.
     */
    public function extractIndividualParamsFromIncomingData($resultArray)
    {
        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }

        if ($resultArray[2] !== null) {
            $indata = $resultArray[2];
        }
        /******************************************/

        if (count($indata) == 2) {
            $typeOfRequest = $indata[0];
            $ipOrPos = $indata[1];
        } else {
            $typeOfRequest = "forecast";
            $ipOrPos = $indata[0];
        }
        // if (isset($indata[0]) && $indata !== null) {
        //     $typeOfRequest = $indata[0];
        // }
        //
        // if (isset($indata[1]) && $indata !== null) {
        //     $ipOrPos = $indata[1];
        // }
        return [$request, $this->weather, $indata, $typeOfRequest, $ipOrPos];
    }


    /**
     * WeatherController::jsonOneWeekForecastActionPost()
     *
     * Get data sent with post method, analyze it and return it as json.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return array
     */
    public function jsonOneWeekForecastActionPost(...$args) : array
    {
        $ipAddress = "";
        $position = "";
        $weatherJson =
            [   "ip" => "",
                "version" => "",
                "latitude" => "",
                "longitude" => "",
                "timezone" => "",
                "date" => "",
                "typeOfRequest" => "",
                "map" => "",
                "message" => "",
            ];
        $message = "";
        $json = [];

        // if (count($args) === 0) {
        //     $request = $this->di->get("request");
        // } else {
        //     $request = $args[0];
        // }
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args, "Anna\Weather\Weather");
        // echo("resultArray i jsonOneWeekForecastActionPost = ");
        // var_dump($resultArray);
        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }

        $response = $this->di->get("response");
        $session = $this->di->get("session");

        $inputFieldName = "ipOrPos";
        $controllerObj = $this;

        $ipOrPos = $request->getPost($inputFieldName);
        // Sanitize incoming data
        // if (is_array($ipOrPos)) {
        //     $ipOrPos = "";
        // } else {
        //     $ipOrPos = htmlentities($ipOrPos);
        // }
        // Sanitize incoming data
        $ipOrPos = htmlentities($ipOrPos);
        // echo "ipOrPos = ";
        // var_dump($ipOrPos);
        if (method_exists($controllerObj, "checkIfValidIndata")) {
            [$ipAddress, $indataType, $position, $message] = $controllerObj->checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position);
        }

        $typeOfRequest = "forecast";
        $oneResponse = $this->weather->checkIfIpOrPosThenGetWeather($session, $response, $this->weather, $ipAddress, $position, $typeOfRequest);
        //if (is_array($oneResponse)) {
            $responseFromIpStack = $oneResponse[0];
            $responseFromDarkSky = $oneResponse[1];
        //}

        if (isset($responseFromIpStack)) {
            $weatherJson["message"] .=  "<br/>A response was received from IpStack.";
        } else {
            $weatherJson["message"] .=  "<br/>NO response from IpStack.";
        }

        if (isset($responseFromDarkSky) && $responseFromDarkSky !== null) {
            foreach ($responseFromDarkSky as $key => $response) {
                $response["data"] = json_decode($response["data"], false);
                if (isset($response["data"]->error)) {          // Händer om
                    $weatherJson["message"] .= "An error was returned from the weather service.";
                    $weatherJson["code"] .= "{$response['data']->code}";
                    $weatherJson["error"] .= "{$response['data']->error}";
                    $json = $weatherJson;
                    return [$json];
                } else {
                    //var_dump($response["data"]);
                    $responseObjects[$key] = $response["data"];    //  false = convert the json string to a php object
                }
            }
            $weatherJson = $this->weather->convertToJsonObject($responseFromIpStack, $responseObjects, $weatherJson, $ipAddress, $position, $typeOfRequest);
            $weatherJson["message"] .=  "<br/>A response was received from DarkSky.";
        } else {
            $weatherJson["message"] .= "<br/>No response from DarkSky - please check your indata.";
        }

        //$json = unserialize(serialize($weatherJson));
        //$json = json_encode($weatherJson, JSON_PRETTY_PRINT);
        $json = $weatherJson;
        return [$json];
    }


    /**
     * WeatherController::jsonThirtyDaysHistoryActionPost()
     *
     * Get data sent with post method, analyze it and return it as json.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return array
     */
    public function jsonThirtyDaysHistoryActionPost(...$args) : array
    {
        $ipAddress = "";
        $position = "";
        $weatherJson =
            [   "ip" => "",
                "version" => "",
                "latitude" => "",
                "longitude" => "",
                "timezone" => "",
                "date" => "",
                "typeOfRequest" => "",
                "map" => "",
                "message" => "",
            ];
        $message = "";
        $json = [];
        // if (count($args) === 0) {
        //     $request = $this->di->get("request");
        // } else {
        //     $request = $args[0];
        // }
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args, "Anna\Weather\Weather");
        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);
        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }

        $response = $this->di->get("response");
        $session = $this->di->get("session");

        $inputFieldName = "ipOrPos";
        $controllerObj = $this;

        $ipOrPos = $request->getPost($inputFieldName);
        // Sanitize incoming data
        // if (is_array($ipOrPos)) {
        //     $ipOrPos = "";
        // } else {
        //     $ipOrPos = htmlentities($ipOrPos);
        // }
        // Sanitize incoming data
        $ipOrPos = htmlentities($ipOrPos);

        if (method_exists($controllerObj, "checkIfValidIndata")) {
            [$ipAddress, $indataType, $position, $message] = $controllerObj->checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position);
        }

        $typeOfRequest = "history";
        $oneResponse = $this->weather->checkIfIpOrPosThenGetWeather($session, $response, $this->weather, $ipAddress, $position, $typeOfRequest);

        // if (is_array($oneResponse)) {
        $responseFromIpStack = $oneResponse[0];
        $responseFromDarkSky = $oneResponse[1];
        // }

        if (isset($responseFromIpStack)) {
            $weatherJson["message"] .=  "<br/>A response was received from IpStack.";
        } else {
            $weatherJson["message"] .=  "<br/>NO response from IpStack.";
        }

        if (isset($responseFromDarkSky) && $responseFromDarkSky !== null) {
            foreach ($responseFromDarkSky as $key => $response) {
                $response["data"] = json_decode($response["data"], false);
                if (isset($response["data"]->error)) {          // Händer om
                    $weatherJson["message"] .= "An error was returned from the weather service.";
                    $weatherJson["code"] .= "{$response['data']->code}";
                    $weatherJson["error"] .= "{$response['data']->error}";
                    $json = $weatherJson;
                    return [$json];
                } else {
                    //var_dump($response["data"]);
                    $responseObjects[$key] = $response["data"];    //  false = convert the json string to a php object
                }
            }
            $weatherJson = $this->weather->convertToJsonObject($responseFromIpStack, $responseObjects, $weatherJson, $typeOfRequest);
            $weatherJson["message"] .=  "<br/>A response was received from DarkSky.";
        } else {
            $weatherJson["message"] .= "<br/>No response from DarkSky - please check your indata.";
        }

        //$json = unserialize(serialize($weatherJson));
        //$json = json_encode($weatherJson, JSON_PRETTY_PRINT);

        $json = $weatherJson;
        return [$json];
    }


    /**
     * WeatherController::jsonActionGet()
     *
     * Get data sent with get method, analyze it and return it as json.
     *
     * @param array $indata as a variadic to catch all arguments.
     *
     * @return array
     */
    public function jsonActionGet(...$indata) : array
    {
        // echo "indata = ";
        // var_dump($indata);

        $ipAddress = "";
        $position = "";
        $weatherJson =
            [   "ip" => "",
                "version" => "",
                "latitude" => "",
                "longitude" => "",
                "timezone" => "",
                "date" => "",
                "typeOfRequest" => "",
                "map" => "",
                "message" => "",
            ];
        $message = "";
        $json = [];

        /********************/
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $indata, "Anna\Weather\Weather");

        [$request, $this->weather, $indata, $typeOfRequest, $ipOrPos] = $this->extractIndividualParamsFromIncomingData($resultArray);

        // echo "indata = ";
        // var_dump($indata);

        $response = $this->di->get("response");
        $session = $this->di->get("session");

        //foreach ($ipsOrPositions as $key => $ipOrPos) {

        // Sanitize incoming data
        $ipOrPos = htmlentities($ipOrPos);
        $weatherJson["message"] .= "incoming indata is {$ipOrPos}. ";

        //$inputFieldName = "ipOrPos";
        $controllerObj = $this;
        // Sanitize incoming data
        // if (is_array($ipOrPos)) {
        //     $ipOrPos = "";
        // } else {
        //     $ipOrPos = htmlentities($ipOrPos);
        // }
        // echo "ipOrPos = ";
        // var_dump($ipOrPos);
        if (method_exists($controllerObj, "checkIfValidIndata")) {
            [$ipAddress, $indataType, $position, $message] = $controllerObj->checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position);
        }
        $weatherJson["ip"] = $ipAddress;

        //$typeOfRequest = "forecast";
        $oneResponse = $this->weather->checkIfIpOrPosThenGetWeather($session, $response, $this->weather, $ipAddress, $position, $typeOfRequest);

        if (is_array($oneResponse)) {
            $responseFromIpStack = $oneResponse[0];
            $responseFromDarkSky = $oneResponse[1];
        }
        if (isset($responseFromIpStack)) {
            $weatherJson["message"] .=  "<br/>A response was received from IpStack.";
        } else {
            $weatherJson["message"] .=  "<br/>NO response from IpStack.";
        }
        if (isset($responseFromDarkSky) && $responseFromDarkSky !== null) {
            foreach ($responseFromDarkSky as $key => $response) {
                // echo "response['data'] = ";
                // var_dump($response["data"]);
                $response["data"] = json_decode($response["data"], false);
                if (isset($response["data"]->error)) {          // Händer om
                    $weatherJson["message"] .= "An error was returned from the weather service.";
                    $weatherJson["code"] .= "{$response['data']->code}";
                    $weatherJson["error"] .= "{$response['data']->error}";
                    $json = $weatherJson;
                    return [$json];
                } else {
                    //var_dump($response["data"]);
                    $responseObjects[$key] = $response["data"];    //  false = convert the json string to a php object
                }
            }
            $weatherJson = $this->weather->convertToJsonObject($responseFromIpStack, $responseObjects, $weatherJson, $typeOfRequest);
            $weatherJson["message"] .=  "<br/>A response was received from DarkSky.";
        } else {
            $weatherJson["message"] .= "<br/>No response from DarkSky.";
        }

        //$json = unserialize(serialize($weatherJson));
        //$json = json_encode($weatherJson, JSON_PRETTY_PRINT);
        $json = $weatherJson;
        return [$json];
    }


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
        echo "Inside catchAll in weatherJsonController!";
        $json = [
            "message" => __METHOD__ . ", route not found.",
        ];
        // $output = ob_get_contents();
        // ob_end_clean();
        // echo "args = ";
        // var_dump($args);
        // return;
        return [$json, 404];
    }
}
