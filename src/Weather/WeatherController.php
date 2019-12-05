<?php

/**
 * A module for WeatherController class.
 *
 * This is the module containing the WeatherContoller class.
 *
 * @author  Anna
 */

namespace Anna\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
//use Anna\Commons\IpValidatorInterface;
//use Anna\Commons\IpValidatorTrait;
//use Anna\Commons\GeoLocatorInterface;
//use Anna\Commons\GeoLocatorTrait;

use Anna\Commons\ControllerInterface;
use Anna\Commons\ControllerTrait;

use Anna\Weather\Weather;

/**
 * Weather Controller
 *
 */
class WeatherController implements ContainerInjectableInterface, ControllerInterface
//class WeatherController extends \Anna\Weather\Weather implements ContainerInjectableInterface, ControllerInterface
{
    use ContainerInjectableTrait;
    //use IpValidatorTrait;
    //use GeoLocatorTrait;

    use ControllerTrait;

    //use Weather;

    /**
     * @var object $weather - a weather object
     */
    protected $weather;
    //protected $geolocator;

    /**
     * @var string $message - a message to be displayed
     */
    public static $message = "";
    public static $dataValidationError = "";


    /**
     * WeatherController::initialize()
     *
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return object
     */
    public function initialize() : object
    {
        // Use to initialise member variables.

        // Initialize the MODEL class Weather
        //$this->weather = new \Anna\Weather\Weather();
        $this->weather = new Weather();
        // Set the dependency/service container to use.
        // "Injectar $di till objectet weather"
        $this->weather->setDI($this->di);

        // Returning this to be able to unittest this function
        return $this->weather;
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
     * WeatherController::fetchIpAndPos()
     *
     *  Get the IP adress and position from session (or, in case of IP-address from the requesterer, if available).
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return object
     */
    public function fetchIpAndPos($session, $request)
    {
        if (!$session->has('ipAddress')) {
               // Get the IP adress from the requesterer, if available
               $ipAddress = $this->getClientIpServer($request);
               $position= $session->get("position");
        } else {
            $ipAddress = $session->get("ipAddress");
            $position = $session->get("position");
        }
           return [$ipAddress, $position];
    }



    /**
     * WeatherController::indexAction()
     *
     * Display the IP/position form on a rendered page.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return object
     */
    public function indexAction(...$args)
    {
        //$resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args, "Anna\Weather\Weather");

        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }

        $navbarConfig =  require __DIR__ . "/../../config/navbar/navbar_sub.php";         // fungerar!
        $formconf =  require __DIR__ . "/../../config/form_weather2.php";         // fungerar!

        $formForecast = null;
        $game = "weather";
        $class2 = "session";
        $method = "post";
        $title = "Weather";
        $mount = "weather";
        $defaults = [];
        $responseFromIpStack = null;
        $responseFromDarkSky = "";
        $responseObjects = array();
        $indata = "";
        $date = "";
        $result = "";
        $description = "";

        // Create one Form4 class object
        $form4 = $this->di->get("form4");

        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $response = $this->di->get("response");

        // Om destroy finns i GET så avslutas sessionen med header redirect
        $this->checkIfDestroy($request, $session, $response, $mount);

        [$ipAddress, $position] = $this->fetchIpAndPos($session, $request);

        [$defaults, $indata] = $this->weather->setDefaultsAndIndata($ipAddress, $position);

        $form = $form4->createFormArray($formconf);

        $validNames = ["ipOrPos", "submit"];
        $submitValues = $formconf["buttonNames"];
        $noButtons = count($submitValues);
        $formVars = $form4->populateFormVars4($form, $request, true, $defaults);
        $formForecast = $form4->init($formVars, $form, $submitValues, $validNames, $noButtons);

        $responseFromIpStack = $session->getOnce("responseFromIpStack");
        $responseFromDarkSky = $session->getOnce("responseFromDarkSky");
        $dataValidationError = $session->getOnce("dataValidationError");

        $typeOfRequest = $session->getOnce("typeOfRequest");
        echo("typeOfRequest = " . $typeOfRequest);

        if ($responseFromDarkSky !== null) {
            foreach ($responseFromDarkSky as $key => $response) {
                $responseObjects[$key] = json_decode($response["data"], false);    //  false = convert the json string to a php object
            }
        }
         $description = "Indata can be either an Ipv4 address (eg. 198.51.100.1.) or an Ipv6 address (eg. 2001:db8:1f70:999:de8:7648:3a49:6e8) or a geographical position in the format (longitude,latitude) (NO spaces!)";

        if (isset($responseObjects) && count($responseObjects) > 0) {
            $result = $this->weather->resultifyResponse($responseObjects, $typeOfRequest, $responseFromIpStack);
        }

         $data =
         [
            "formForecast" => $formForecast,
            "mount" => $mount,
            "responseFromIpStack" => $responseFromIpStack,
            "responseFromDarkSky" => $responseFromDarkSky,
            "responseObjects" => $responseObjects,
            "session" => $session,
            "navbarConfig" => $navbarConfig,
            "message" => static::$message,
            "message2" => \Anna\Weather\Weather::$message,
            "date" => $date,
            "result" => $result,
         ];
         $page->add("anax/weather/index", $data);
         $page->add("anax/map/map", $data);
         if (isset($formForecast)) {
             $page->add("anax/form/default", [
             "formIp" => $formForecast,
             "mount" => $mount,
             "title" => "Get forecast or observed weather data",
             "formError" => $dataValidationError,
             "description" => $description,
             "id" => "form",
             "formAttrs" => ["game" => $game, "save" => $class2, "method" => $method]
             ]);
         }
         return $page->render([
            "title" => $title,
         ]);
    }



    /**
     * Get posted data, analyze it and redirect to the result page.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return object
     */
    public function oneWeekForecastActionPost(...$args) : object
    {
        $mount = "weather";
        // $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args, "Anna\Weather\Weather");

        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);

        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }
        // echo("this-geolocator i indexAction= ");
        // var_dump($this->geolocator);

        // die();


        //$config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        // $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("geolocator");

        // $this->geolocator->setDI($this->di);

        //$curl3 = $this->di->get("curl3");
        //$curl4 = $this->di->get("curl4");

        // $timestampResult = $this->checkTimestamp($request, $session);
        //$inputFieldsNames = ["ipOrPos" => "ipOrPos"];
        $inputFieldName = "ipOrPos";
        $controllerObj = $this;
        $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldName, $controllerObj);

        // Sanitizing the output
        $ipAddress =  htmlentities($timestampResult[0]);
        //$ipType =  htmlentities($timestampResult[1]);
        $position =  htmlentities($timestampResult[2]);
        static::$message .= htmlentities($timestampResult[3]);

        // echo("ipAddress = ");
        // var_dump($ipAddress);
        // echo("ipType = ");
        // var_dump($ipType);
        // echo("position = ");
        // var_dump($position);
        // echo("staticMessage = ");
        // var_dump(static::$message);
        // die();

        $session->set("flashmessage", "The Ip form was sent with POST.");
        $session->set("flashmessage", $session->get("flashmessage") . "<br/>IP-address: " . $ipAddress);
        $session->set("flashmessage", $session->get("flashmessage") . "<br/>Position: " . $position);

        // $responses = \http_get("https://api.ipstack.com/{$ipAddress}?access_key={$config['accessKey']}");

        $typeOfRequest = "forecast";
        $oneResponse = $this->weather->checkIfIpOrPosThenGetWeather($session, $response, $this->weather, $ipAddress, $position, $typeOfRequest);
        $session->set("typeOfRequest", $typeOfRequest);

        if (is_array($oneResponse)) {
            $responseFromIpStack = $oneResponse[0];
            $responseFromDarkSky = $oneResponse[1];
        }

        $session->set("message", static::$message);

        if (isset($responseFromIpStack)) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from IpStack.");
            $session->set("responseFromIpStack", $responseFromIpStack);
        }
        if (isset($responseFromDarkSky)) {
            // $session->set("flashmessage", $session->get("flashmessage") . "<br/>The response was: $responseFromIpStack.");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from DarkSky.");

            $session->set("responseFromDarkSky", $responseFromDarkSky);
        } else {
            // $session->set("flashmessage", "No response from IpStack!!");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from DarkSky, probably because no valid Ip-address was supplied.");
        }
        return $response->redirect($mount);
    }



    /**
     * Get posted data, analyze it and redirect to the result page.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return object
     */
    public function thirtyDaysHistoryActionPost(...$args) : object
    {
        $mount = "weather";
        $responseFromIpStack = null;        // Test 191126
        // $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);


        //$resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args, "Anna\Weather\Weather");

        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);

        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->weather = $resultArray[1];
        }
        // echo("this-geolocator i indexAction= ");
        // var_dump($this->geolocator);

        // die();


        //$config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        // $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("geolocator");

        // $this->geolocator->setDI($this->di);

        //$curl3 = $this->di->get("curl3");
        //$curl4 = $this->di->get("curl4");

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {

        $inputFieldName = "ipOrPos";
        // $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldsNames, $mount);
        // $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldsNames, $mount, $ipValidator, $geolocator = null);
        $controllerObj = $this;
        $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldName, $controllerObj);
        //public function checkTimestamp2($request, $session, $inputs, $mount)


        // Sanitizing the output
        $ipAddress =  htmlentities($timestampResult[0]);
        //$ipType =  htmlentities($timestampResult[1]);
        $position =  htmlentities($timestampResult[2]);
        static::$message .= htmlentities($timestampResult[3]);

        // echo("ipAddress = ");
        // var_dump($ipAddress);
        // echo("staticMessage = ");
        // var_dump(static::$message);
        // die();

        $session->set("flashmessage", "The Ip form was sent with POST.");
        $session->set("flashmessage", $session->get("flashmessage") . "<br/>IP-address: " . $ipAddress);
        $session->set("flashmessage", $session->get("flashmessage") . "<br/>Position: " . $position);

        // $responses = \http_get("https://api.ipstack.com/{$ipAddress}?access_key={$config['accessKey']}");

        $typeOfRequest = "history";
        $oneResponse = $this->weather->checkIfIpOrPosThenGetWeather($session, $response, $this->weather, $ipAddress, $position, $typeOfRequest);
        $session->set("typeOfRequest", $typeOfRequest);

        if (is_array($oneResponse)) {
            $responseFromIpStack = $oneResponse[0];
            $responseFromDarkSky = $oneResponse[1];
        }

        $session->set("message", static::$message);

        if (isset($responseFromIpStack)) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from IpStack.");
            $session->set("responseFromIpStack", $responseFromIpStack);
        }
        if (isset($responseFromDarkSky)) {
            // $session->set("flashmessage", $session->get("flashmessage") . "<br/>The response was: $responseFromIpStack.");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from DarkSky.");

            $session->set("responseFromDarkSky", $responseFromDarkSky);
        } else {
            // $session->set("flashmessage", "No response from IpStack!!");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from DarkSky, probably because no valid Ip-address was supplied.");
        }
        return $response->redirect($mount);
    }


    /**
     * Get posted data, analyze it and redirect to the result page.
     *
     * @param array $args - as a variadic parameter.
     *
     * @return object
     */
    public function getMyIpActionPost(...$args)
    {
        if (count($args) === 0) {
            $request = $this->di->get("request");
        } else {
            $request = $args[0];
        }
        // $ipValidator =  require __DIR__ . "/../config/form_dice.php";
        $response = $this->di->get("response");
        // $request = $this->di->get("request");                // Om inte denna kommenteras bort får man "unknown" på alla IP-addresser.
        $session = $this->di->get("session");
        // $key = $request->getPost("ipvalidator");

        // $request = $this->di->get("request");                // Om inte denna kommenteras bort får man "unknown" på alla IP-addresser!


        // // Dump request:
        // echo "<br/>\$request inside getMyIpActionPost()";
        // var_dump($request);
        // $this->request->getServer($key);
        //
        $session->set("flashmessage", "The Ip form was sent with POST by pressing the GetMyIp-button<br/>");

        // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer($request);
        $ipAddress = $this->getClientIpServer($request);

        // echo "<br/>ipAddress in getMyIpActionPost = " . $ipAddress;

        // $ipType = IpValidator::checkIfValidIp($ipAddress);
        $ipType = $this->checkIfValidIp($ipAddress);

        // Sanitize the incoming data (not necessary here?):
        $ipAddress = htmlentities($ipAddress);


        if ($ipType) {
            // $session->set("flashmessage", "$ipAddress is a valid $ip address.");
            $session->append("flashmessage", "$ipAddress is a valid $ipType address.");
            $isPrivOrRes = $this->checkIfAdressIsPrivOrRes($ipAddress);
            if ($isPrivOrRes) {
                // $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
                $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
            }
            $host = gethostbyaddr($ipAddress);
                  // echo ("\$host = " );
                  // var_dump($host);
            if (isset($host) && ($host !== false)) {
                if ($host !== $ipAddress) {
                    $host = htmlentities($host);
                    $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
                }
            }
        } else {
            // $session->set("flashmessage", "<br/>$ipAddress is NOT a valid IP address");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is NOT a valid IP address");
        }
        $session->set("ipAddress", $ipAddress);
        return $response->redirect("weather#form");
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
        echo "catchAll";
        // echo "Inside catchAll() in GeoLocatorController!";
        // $output = ob_get_contents();
        // ob_end_clean();
        return;             // If void is returned then it continues to search in '\Anna\GeoloLocatorJson\GeoLocatorJsonController'
    }
}
