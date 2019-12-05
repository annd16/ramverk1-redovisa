<?php

/**
* A module for CurlController class.
*
* This is the module containing the CurlController class.
*
* @author  Anna
*/

namespace Anna\Curl;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
// use Anna\Commons\IpValidatorInterface;
// use Anna\Commons\IpValidatorTrait;
// use Anna\Commons\GeoLocatorInterface;
// use Anna\Commons\GeoLocatorTrait;
use Anna\Commons\ControllerInterface;
use Anna\Commons\ControllerTrait;

use \Anna\Curl\Curl4;

/**
* Style chooser controller loads available stylesheets from a directory and
* lets the user choose the stylesheet to use.
*/
//class CurlController extends \Anna\Curl\curl4 implements ContainerInjectableInterface
class CurlController implements ContainerInjectableInterface, ControllerInterface
{
    use ContainerInjectableTrait;
    // use IpValidatorTrait;
    // use GeoLocatorTrait;

    use ControllerTrait;

    //use curl4;

    /**
    * @var object $geolocator - a geolocator object
    */
    protected $curl;

    /**
    * @var string $message - a message to be displayed
    */
    public static $message = "";



    /**
    * The initialize method is optional and will always be called before the
    * target method/action. This is a convienient method where you could
    * setup internal properties that are commonly used by several methods.
    *
    * @return object
    */
    public function initialize() : object
    {
        // Use to initialise member variables.

        // Initialize the MODEL class curl4
        // $this->curl = new curl4();
        $this->curl = $this->di->get("curl4");

        // Set the dependency/service container to use.
        // "Injectar $di till objectet Curl"
        //$this->curl->setDI($this->di);

        // echo "this->Curl = ";
        // var_dump($this->Curl);
        // die();

        // Returning this to be able to unittest this function
        return $this->curl;
    }

    /**
    * CurlController::indexAction()
    *
    * Display the IP/position form on a rendered page.
    *
    * @param array $args as a variadic to catch all arguments.
    *
    * @return object
    */
    public function indexAction(...$args)
    {
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

        $request = isset($resultArray[0]) ? $resultArray[0] : null;

        if ($resultArray[1] !== null) {
            $this->curl = $resultArray[1];
        }

        $form =  require __DIR__ . "/../../config/form_curl.php";         // fungerar!
        $navbarConfig =  require __DIR__ . "/../../config/navbar/navbar_sub.php";         // fungerar!

        $formIp = null;
        $game = "curl";
        $class2 = "session";
        $method = "post";
        $title = "Curl";
        $mount = "curl";
        $defaults = [];
        $responsesFromApi = null;
        $responseObjects = array();

        $form4 = $this->di->get("form4");

        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $response = $this->di->get("response");

        // Om destroy finns i GET så avslutas sessionen med header redirect
        $this->checkIfDestroy($request, $session, $response, $mount);

        $formconf =  require __DIR__ . "/../../config/form_curl2.php";         // fungerar!

        $form = [];

        // Create form array:
        foreach ($formconf["inputFields"] as $key => $field) {
            for ($i = 0; $i < $field[2]; $i++) {
                array_push(
                    $form,
                    [
                    "type" => $field[0],
                    "name" => $field[1],
                    "value" => null,
                    "else"  => "",
                    "label" => isset($field[3]) ? $field[3] : "",
                    ]
                );
            }
        }

        $validNames = ["submit"];
        $submitValues = $formconf["buttonNames"];
        $noButtons = count($submitValues);

        $formVars = $form4->populateFormVars4($form, $request, true, $defaults);
        //$formIp = $form4->init($formVars, $form, ["One_Day", "Three_Days_serial", "Three_Days_parallel"], $validNames, 3);
        $formIp = $form4->init($formVars, $form, $submitValues, $validNames, $noButtons);

        $responsesFromApi = $session->getOnce("responsesFromApi");
        // var_dump($responsesFromApi);         // An array with keys: "sr1", "sr2", "sr3" etc containing as many arrays as requests. Each request's array has 3 keys: "data" (a jsonstring), "info" (an array)  & "error" (null or an array).

        if ($responsesFromApi !== null) {
            static::$message .= "<br>\$responsesFromApi not null!";
            foreach ($responsesFromApi as $key => $response) {
                $responseObjects[$key] = $response;
            }
        }

        echo "message = " . static::$message;

        $data =
        [
            "formIp" => $formIp,
            "mount" => $mount,
            "responseObjects" => $responseObjects,
            "session" => $session,
            "navbarConfig" => $navbarConfig,
            "message" => static::$message,
        ];
        $page->add("anax/Curl/index", $data);
        if (isset($formIp)) {
            $page->add("anax/form/default", [
                "formIp" => $formIp,
                "mount" => $mount,
                "title" => $title,
                "formAttrs" => [
                    "game" => $game,
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
    * Get posted data, analyze it and redirect to the result page.
    *
    * @param array $args as a variadic to catch all arguments.
    *
    * @return object
    */
    public function oneDayActionPost(...$args) : object
    {
        //$mount = "curl";
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

        // echo("resultArray i one_dayActionPost = ");
        // var_dump($resultArray);

        //$request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i one_dayActionPost = ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->curl = $resultArray[1];
        }

        $response = $this->di->get("response");
        $session = $this->di->get("session");

        //$curl4 = $this->di->get("curl4");

        $session->set("flashmessage", "The curl form was sent with POST.");
        $session->set("flashmessage", "One single request was sent to receive data for one day.");

        $baseUrl = "http://api.sr.se/api/v2/scheduledepisodes?format=json&indent=true&channelid=164&date=";

        $date = date("Y-m-d");
        $url = $baseUrl . "$date";
        $name = "sr";
        $counter = 0;

        $responsesFromApi = $this->curl->curlAnUrl($url, $name, $counter);

        $session->set("message", static::$message);
        if (isset($responsesFromApi)) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from the API.");
            $session->set("responsesFromApi", $responsesFromApi);
        } else {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from the API.");
        }
        return $response->redirect("curl");
    }


    /**
    * Get posted data, analyze it and redirect to the result page.
    *
    * @param array $args as a variadic to catch all arguments.
    *
    * @return object
    */
    public function threeDaysSerialActionPost(...$args) : object
    {
        //$mount = "curl";
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);

        //$request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->curl = $resultArray[1];
        }
        // die();

        //$config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        $session = $this->di->get("session");

        //$curl4 = $this->di->get("curl4");

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {

        //$timestampResult = $this->checkTimestamp($request, $session);

        // $inputFieldName = "";
        // $controllerObj = $this;
        // $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldName, $mount, $controllerObj);
        //
        // // Sanitizing the output
        // $ipAddress =  htmlentities($timestampResult[0]);
        // $position =  htmlentities($timestampResult[1]);
        // static::$message .= htmlentities($timestampResult[2]);

        $session->set("flashmessage", "The curl form was sent with POST.");
        $session->set("flashmessage", "Three requests were sent in serial to receive data for three days.");

        //$urls = array();
        $baseUrl = "http://api.sr.se/api/v2/scheduledepisodes?format=json&indent=true&channelid=164&date=";

        $dates[0] = date("Y-m-d");
        $dates[1] = date("Y-m-d", strtotime("1 day", strtotime($dates[0])));
        $dates[2] = date("Y-m-d", strtotime("1 day", strtotime($dates[1])));
        //static::$message .= "<br/>date = " . $dates;

        $name = "sr";
        $counter = 0;

        $responsesFromApi = array();
        foreach ($dates as $key => $date) {
            //static::$message .= "<br/>date = " . $date;
            $url = $baseUrl . "$date";
            $responseFromApi = $this->curl->curlAnUrl($url, $name, $counter);
            //array_push($responsesFromApi, $responseFromApi);
            //array_merge($responsesFromApi, $responseFromApi);
            $responsesFromApi += $responseFromApi;
            //static::$message .= "<br/>url = " . $url;
            //array_push($urls, $url);
        }

        $session->set("message", static::$message);
        if (isset($responsesFromApi)) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from the API.");
            $session->set("responsesFromApi", $responsesFromApi);
        } else {
            // $session->set("flashmessage", "No response from IpStack!!");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from the API.");
        }
        return $response->redirect("curl");
    }


    /**
    * Get posted data, analyze it and redirect to the result page.
    *
    * @param array $args - as a variadic parameter.
    *
    * @return object
    */
    public function threeDaysParallelActionPost(...$args)
    {
        //$mount = "curl";
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

         //static::$message = "";

        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);

        //$request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->curl = $resultArray[1];
        }
        // die();


        //$config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        // $request = $this->di->get("request");
        $session = $this->di->get("session");

        //$curl4 = $this->di->get("curl4");

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {

        //$timestampResult = $this->checkTimestamp($request, $session);

        // $inputFieldName = "";
        // $controllerObj = $this;
        // $timestampResult = $this->checkTimestamp2($request, $session, $inputFieldName, $mount, $controllerObj);
        //
        // // Sanitizing the output
        // $ipAddress =  htmlentities($timestampResult[0]);
        // $position =  htmlentities($timestampResult[1]);
        // static::$message .= htmlentities($timestampResult[2]);

        $session->set("flashmessage", "The curl form was sent with POST.");
        $session->set("flashmessage", "Three requests were sent in parallel to receive data for three days.");

        $urls = array();
        $baseUrl = "http://api.sr.se/api/v2/scheduledepisodes?format=json&indent=true&channelid=164&date=";

        $dates[0] = date("Y-m-d");
        $dates[1] = date("Y-m-d", strtotime("1 day", strtotime($dates[0])));
        $dates[2] = date("Y-m-d", strtotime("1 day", strtotime($dates[1])));
        //static::$message .= "<br/>date = " . $dates;
        foreach ($dates as $key => $date) {
            //static::$message .= "<br/>date = " . $date;
            $url = $baseUrl . "$date";
            //static::$message .= "<br/>url = " . $url;
            array_push($urls, $url);
        }

        //$opts = [CURLOPT_TIMEOUT => 2];
        $responsesFromApi = $this->curl->curlMultipleUrls($urls, 'sr');

        $responsesAsString = json_encode($responsesFromApi);

        static::$message = $responsesAsString;

        $session->set("message", static::$message);
        if (isset($responsesFromApi)) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from the API.");
            $session->set("responsesFromApi", $responsesFromApi);
        } else {
            // $session->set("flashmessage", "No response from IpStack!!");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from the API.");
        }
        return $response->redirect("curl");
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
