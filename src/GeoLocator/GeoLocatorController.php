<?php

/**
 * A module for GeoLocatorController class.
 *
 * This is the module containing the GeoLocatorContoller class.
 *
 * @author  Anna
 */

namespace Anna\GeoLocator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;
//use \Anna\Geolocator\Geolocator;
use Anna\GeoLocator\GeoLocator;

//use Geolocator;

/**
 * GeolocatorController to deal with geolocalization
 *
 */
class GeoLocatorController extends GeoLocator implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;

    //use Geolocator;

    /**
     * @var object $geolocator - a geolocator object
     */
    protected $geolocator;

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

        // Initialize the MODEL class GeoLocator

        //$this->geolocator = new \Anna\GeoLocator\GeoLocator();
        $this->geolocator = new GeoLocator();

        // Set the dependency/service container to use.
        // "Injectar $di till objectet geolocator"
        $this->geolocator->setDI($this->di);
        // echo "this->geolocator = ";
        // var_dump($this->geolocator);
        // die();

        // Returning this to be able to unittest this function
        return $this->geolocator;
    }


    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @param object $diService the $di-object.
     * @param array $argsArray all otehr incoming arguments.
     *
     * @return array with $request, $geolocator object and an array with the $ipAddresses
     */
    public function setParamsBasedOnArgsCount($diService, $argsArray)
    {
        // var_dump($diService);
        // var_dump($args);
        // In BROWSER:
        $request = $diService->get("request");
        $geolocator = null;

        // In UNITTESTS:
        foreach ($argsArray as $key => $val) {
            if (gettype($val) == "object") {
                if (get_class($val) == "Anna\Request\Request") {
                    $request = unserialize(serialize($val));
                } elseif (get_class($val) == "Anna\GeoLocator\GeoLocator") {
                    $geolocator = unserialize(serialize($val));
                }
            }
        }
        return [$request, $geolocator];
    }

    /**
     * GeolocatorController::checkTimestamp()
     *
     * This method checks if "timestamp" is in either $_POST or $_SESSION
     * and dewecides what to do next based on this.
     *
     * @param object $request - a $request object.
     * @param object $session - a $session object.
     *
     * @return array with the strings $ipAddress, $ipType and a $message.
     */
    public function checkTimestamp($request, $session)
    {
        $ipType = "";
        $message = "";
        $message .= "checkTimestamp()!!";
        if (null !== $request->getPost('timestamp')) {
            //static::$message .= "<br/>POST is SET!";
            //static::$message .= "<br/>null !== \$request->getPost('timestamp')";
            $message .= "<br/>>POST['timestamp'] is SET!";
            $message .= "<br/>null !== \$request->getPost('timestamp')";

            if (!$session->has("timestamp")) {
                // static::$message .= "<br/>SESSION['timestamp'] is NOT SET!";
                $message .= "<br/>SESSION['timestamp'] is NOT SET!";

                $session->set('timestamp', $request->getPost('timestamp'));

                $ipAddress = htmlentities($request->getPost("ipAddress"));
                $message .= "\$ipAddress = $ipAddress";
                $ipType =  $this->checkIfValidIp($ipAddress);
                $session->set('ipAddress', $ipAddress);
            } else {
                if ($session->get("timestamp") !== $request->getPost('timestamp')) {
                    // static::$message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
                    $message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
                    // $_SESSION['timestamp'] = $_GET['timestamp'];
                    $session->set('timestamp', $request->getPost('timestamp'));

                    $ipAddress = htmlentities($request->getPost("ipAddress"));
                    $ipType = $this->checkIfValidIp($ipAddress);
                    $session->set('ipAddress', $ipAddress);
                } elseif ($session->get("timestamp") === $request->getPost('timestamp')) {
                    // static::$message .= "<br/>SESSION is SET & SESS === POST!";
                    $message .= "<br/>SESSION is SET & SESS === POST!";
                    $ipAddress = $session->get('ipAddress');
                }
            }
            // die();
        } else {  // elseif (isset($_GET['timestamp'])) ends
            //static::$message .= "<br/>POST is NOT set!";
            //static::$message .= "...again!";
            $message .= "<br/>POST is NOT set!";
            $message .= "...again!";
            $ipAddress = $session->get('ipAddress');
        }
        return [$ipAddress, $ipType, $message];
    }


    /**
     * GeolocatorController::indexAction()
     *
     * Display the Geolocator form on a rendered page.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return object
     */
    public function indexAction(...$args) : object              // input args to enable unittesting
    {
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

        // echo("\nresultArray i indexAction= ");
        // // var_dump($resultArray);
        // foreach ($resultArray as $key => $val) {
        //     echo(gettype($val));
        // }

        $request = isset($resultArray[0]) ? $resultArray[0] : null;

        if ($resultArray[1] !== null) {
            $this->geolocator = $resultArray[1];
        }

        $form =  require __DIR__ . "/../../config/form_ipvalidation.php";         // fungerar!
        $navbarConfig =  require __DIR__ . "/../../config/navbar/navbar_sub.php";         // fungerar!

        $formIp = null;
        $game = "geolocation";
        $class2 = "session";
        $method = "post";
        $title = "Geolocator";
        $mount = "geo";
        $defaults = [];
        $responseFromIpStack = "";

        $form4 = $this->di->get("form4");

        $page = $this->di->get("page");
        $session = $this->di->get("session");
        // $request = $this->di->get("request");
        $response = $this->di->get("response");

        // Om destroy finns i GET så avslutas sessionen med header redirect
        $this->geolocator->checkIfDestroy($request, $session, $response, $mount);

        if (!$session->has('ipAddress')) {
            // Get the IP adress from the requesterer, if available
            $ipAddress = $this->getClientIpServer($request);
        } else {
            $ipAddress = $session->get("ipAddress");
        }
        // echo "<br/>ipAddress = " . $ipAddress;

        if ($ipAddress &&  $this->checkIfValidIp($ipAddress)) {
            // echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            // echo "<br/>defaults = ";
            // var_dump($defaults);
        }

        $formVars = $form4->populateFormVars4($form, $request, true, $defaults);
        // echo "An IP form has been created";
        $validNames = ["ipAddress", "submit"];
        $formIp = $form4->init($formVars, $form, ["Web", "Json", "GetMyIp"], $validNames, 3);

        $responseFromIpStack = $session->getOnce("responseFromIpStack");
        var_dump($responseFromIpStack);         // Borde vara en sträng??

        $responseObject = json_decode($responseFromIpStack, false);

        static::$message .= "<br/>Just checking...!";

        // echo "message = " . static::$message;

        $data =
        [
            "formIp" => $formIp,
            "mount" => $mount,
            "responseFromIpStack" => $responseFromIpStack,
            "responseObject" => $responseObject,
            "session" => $session,
            "navbarConfig" => $navbarConfig,
            "message" => static::$message,
        ];
        $page->add("anax/geolocator/index", $data);
        $page->add("anax/map/map", $data);
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
    public function webActionPost(...$args) : object
    {
        // $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);
        $resultArray = $this->setParamsBasedOnArgsCount($this->di, $args);

        // echo("resultArray i webActionPost = ");
        // var_dump($resultArray);

        $request = isset($resultArray[0]) ? $resultArray[0] : null;
        // echo("request i indexAction= ");
        // var_dump($request);
        if ($resultArray[1] !== null) {
            $this->geolocator = $resultArray[1];
        }
        // echo("this-geolocator i indexAction= ");
        // var_dump($this->geolocator);

        // die();


        $config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        // $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("geolocator");

        // $this->geolocator->setDI($this->di);

        $curl2 = $this->di->get("curl2");

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {

        $timestampResult = $this->checkTimestamp($request, $session);

        // Sanitizing the output
        $ipAddress =  htmlentities($timestampResult[0]);
        $ipType =  htmlentities($timestampResult[1]);
        static::$message .= htmlentities($timestampResult[2]);

        // echo("ipAddress = ");
        // var_dump($ipAddress);
        // echo("staticMessage = ");
        // var_dump(static::$message);
        // die();

        $session->set("flashmessage", "The Ip form was sent with POST.");

        // $responses = \http_get("https://api.ipstack.com/{$ipAddress}?access_key={$config['accessKey']}");

        if (isset($ipType) && $ipType) {
        // ***********************************
            // // $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1";
            // $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";
            // // $responseFromIpStack = \Anna\Curl\Curl::curlAnUrl($url);
            // $responseFromIpStack = $curl2->curlAnUrl($url);

            $responseFromIpStack = $this->geolocator->getGeoLocation($ipAddress, $config, $curl2);
        }
        $session->set("message", static::$message);
        // if ($responseFromIpStack) {
        if (isset($responseFromIpStack)) {
            // $session->set("flashmessage", $session->get("flashmessage") . "<br/>The response was: $responseFromIpStack.");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from ipStack.");
            $session->set("responseFromIpStack", $responseFromIpStack);
        } else {
            // $session->set("flashmessage", "No response from IpStack!!");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>No response from IpStack, probably because no valid Ip-address was supplied.");
        }
        return $response->redirect("geo");
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
        return $response->redirect("geo");
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
