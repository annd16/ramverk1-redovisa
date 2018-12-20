<?php

/**
 * A module for WeatherController class.
 *
 * This is the module containing the StyleChooser class.
 *
 * @author  Anna
 */

namespace Anna\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Style chooser controller loads available stylesheets from a directory and
 * lets the user choose the stylesheet to use.
 */
class WeatherController implements ContainerInjectableInterface
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
        $game = "weather";
        $class2 = "session";
        $method = "post";
        $title = "Weather";
        $mount = "weather";
        $defaults = [];
        $responseFromDarkSky = "";
        // $message = "";

        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $request = $this->di->get("request");
        $response = $this->di->get("response");

        // Om destroy finns i GET så avslutas sessionen med header redirect
        if (null !== $request->getGet("destroy")) {
            # Delete cookies and kill session
            $session->destroy($session->get("name"));

            // header("Location: " . \Anax\View\url($mount.'/session'));
            $path = \Anax\View\url("$mount");
            $response->redirect($path);

            // Show incoming variables and view helper functions
            //echo showEnvironment(get_defined_vars(), get_defined_functions());
        }





        // $active = $session->get(self::$key, null);

        // $request = $this->di->get("request");
        //
        // echo "request = ";
        // var_dump($request);

        // Fetch the client IP address if no IP-address is stored in session
        if (!$session->has('ipAddress')) {
            // Get the IP adress from the requesterer, if available
            $ipAddress = \Anna\IpValidator\IpValidator::getClientIpEnv();

        } else {
            $ipAddress = $session->get("ipAddress");
        }
        echo "<br/>ipAddress = " . $ipAddress;

        if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }


        $formVars = \Anna\Form3\Form3::populateFormVars3($form, $this->di, true, $defaults);
        echo "An IP form has been created";
        $validNames = ["ipAddress", "submit"];
        $formIp = new \Anna\Form3\Form3($formVars, $form, ["Web", "Json"], $validNames, 2);

        $responseFromDarksky = $session->getOnce("responseFromDarkSky");

        var_dump($responseFromDarksky);         // En sträng??

        if (is_array($responseFromDarksky)) {
            foreach ($responseFromDarksky as $key => $val) {
                $responseObjects[] = json_decode($val, false);
            }
        } else {
            $responseObjects[] = json_decode($responseFromDarksky, false);
        }


        // if (isset($responseObject)) {
        //     $result = "IP-address: {$responseObject->ip}<br/>"
        //     . "Country: {$responseObject->country_name}<br/>"
        //     . "Latitude: {$responseObject->latitude}<br/>"
        //     . "Longitude: {$responseObject->longitude}<br/>";
        //
        //     $index = 0;
        //     $title = "Geographical information";
        //
        //     echo \Anna\Result\Result::displayResult($result, $index, $title);
        //     // echo "<br/>\$responseFromIpStack = " . $responseFromIpStack;
        // }


        static::$message .= "<br/>Just checking...!";

        echo "message = " . static::$message;

        $data =
        [
            "formIp" => $formIp,
            // "formAttrs" => $formAttrs
            "mount" => $mount,
            "responseFromDarkSky" => $responseFromDarkSky,
            "responseObjects" => $responseObjects,
            "session" => $session,
            // "formAttrs" => [
            // // "game" => $game,
            // "game" => "Tjoho!",
            // "save" => $class2,
            // "method" => $method,
            // ]
            "navbarConfig" => $navbarConfig,
            "message" => static::$message,
        ];
        $page->add("anax/weather/index", $data);
        $page->add("anax/map/map", $data);
        if (isset($formIp)) {
        // $page->add("anax/form_start/default", $data);

        // $page->add("anax/form/default", $data);
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
     * Get posted data, analyze it and redirect to the result page.
     *
     * @return object
     */
    public function webActionPost() : object
    {
        $config = require __DIR__ . "/../../config/config_keys.php";

        $response = $this->di->get("response");
        $request = $this->di->get("request");
        $session = $this->di->get("session");
        $key = $request->getPost("weather");

        function checkIfCoordinates($data) {
            $regex = "/^(-?[1-8]?\d(?:\.\d{1,6})?|90(?:\.0{1,6})?),(-?(?:1[0-7]|[1-9])?\d(?:\.\d{1,6})?|180(?:\.0{1,6})?)$/";
            if(preg_match($regex, $data) === 1) {
                return true;
            } else {
                return false;
            }
        }

        function checkIncomingData($data) {
            if (\Anna\IpValidator\IpValidator::checkIfValidIp($data)) {
                    return "ip";
            } elseif (checkIfCoordinates($data)) {
                    return "coordinates";
            } elseif (is_string($data)) {
                return "name";
            }
        }


        $ipAddress = $request->getPost("ipAddress");
        static::$message .= "<br/>incomingData = " . checkIncomingData($ipAddress);

        $incomingData = checkIncomingData($ipAddress);
        if ($incomingData === "ip") {
            $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1";
            $responseFromIpStack = \Anna\Curl\Curl::curlAnUrl($url);
            $responseObject = json_decode($responseFromIpStack, false);
            $coordinates = [$responseObject->latitude, $responseObject->longitude];
            static::$message .= "<br/>input was an ip!!!";
        } elseif ($incomingData === "name") {
            static::$message .= "<br/>input was a name!!!";
            // "https://neutrinoapi.com/geocode-address"
            $coordinates = [55, 55];
        } elseif ($incomingData === "coordinates") {
            static::$message .= "<br/>input was coordinates!!!";
            $coordinates = explode(",", $ipAddress);
        }

        static::$message .= "<br/>coordinates =" . htmlentities(implode($coordinates, ", "));

        // $timestamp =$request->getPost("timestamp");
        // $ipAddress = htmlentities($request->getPost("ipAddress"));
        //
        // if (isset($timestamp)) {
        //     if ($session->has("timestamp")) {
        //         if ($timestamp !== $session->get("timestamp")) {
        //
        //             $session->set("timestamp", $timestamp);
        //             $session->set("ipAddress", $ipAddress);
        //             $ip = checkIfValidIp($ipAddress);
        //         } else {
        //             echo "Just a reload?!";
        //             $ipAddress = $session->get("ipAddress");
        //         }
        //     }
        // } else {
        //     echo "timestamp is not set!!";
        // }

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {
        // if (null !== $request->getPost('timestamp')) {
        // // } elseif (null !== $this->app->request->getGet('timestamp')) {       // Fungerar inte att skicka in $app? ville ej starta spelet då
        // // if (isset($_GET['noRolls']) || $playerId === 0) {
        //     static::$message .= "<br/>POST is SET!";
        //     static::$message .= "<br/>Inside 'if (isset(\$_POST['timestamp'])' i /webActionPost-routen";
        //     // echo "noRolls = ";
        //     // var_dump($_GET['noRolls']);
        //
        //     // Check if $_SESSION["timestamp"] is not set i.e the previous action was not hold.
        //     // If $noRollsInThisRound > above 0 i.e the current active player has rolled at least once then it is OK to hold:
        //     // if (!isset($_SESSION["timestamp"])) {
        //     if (!$session->has("timestamp")) {
        //         static::$message .= "<br/>SESSION['timestamp'] is NOT SET!";
        //         // $_SESSION['timestamp'] = $_GET['timestamp'];
        //         $session->set('timestamp', $request->getPost('timestamp'));
        //         // $resCode = $resCode;
        //         // if ($criterium) {
        //         //     // $resCode = $this->hold($resCode);
        //         //     // $this->message .= "<br/>After player {$playerId} has been through the $action-method#2";
        //         //     $isOk = true;
        //         //     // $this->message .= "<br/>Possible to {$action} for player {$playerId}: {$isOK}";
        //         // } else {
        //         //     $resCode = "notPossibleTo{$action}Yet";
        //         //     $isOk = false;
        //         // }
        //         // $resCode = "notPossibleTo$action";
        //         // $isOkAndResCode = $this->checkIfCriteriumIsFullfilled($action, $criterium, $resCode);
        //
        //         $ipAddress = htmlentities($request->getPost("ipAddress"));
        //         $ip = checkIfValidIp($ipAddress);
        //         $session->set('ipAddress', $ipAddress);
        //         // return [$isOkAndResCode[0], $isOkAndResCode[1]];
        //         // return [$isOk, $resCode];
        //     // } elseif (isset($_SESSION["timestamp"])) {
        //     } else {
        //         // if ($_SESSION["timestamp"] !== $_GET['timestamp']) {
        //         if ($session->get("timestamp") !== $request->getPost('timestamp')) {
        //             static::$message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
        //             // $_SESSION['timestamp'] = $_GET['timestamp'];
        //             $session->set('timestamp', $request->getPost('timestamp'));
        //
        //             $ipAddress = htmlentities($request->getPost("ipAddress"));
        //             $ip = checkIfValidIp($ipAddress);
        //             $session->set('ipAddress', $ipAddress);
        //             // $isOkAndResCode = $this->checkIfCriteriumIsFullfilled($action, $criterium, $resCode);
        //             // return [$isOkAndResCode[0], $isOkAndResCode[1]];
        //             // return [$isOk, $resCode];
        //         // } elseif ($_SESSION["timestamp"] === $_GET['timestamp']) {
        //         } elseif ($session->get("timestamp") === $request->getPost('timestamp')) {
        //             static::$message .= "<br/>SESSION is SET & SESS === POST!";
        //             $session->get('ipAddress');
        //             // $resCode = "setAndEqual_$action";
        //             // unset($_GET["resCode"]);
        //             // $app->request->setGet('resCode', null);
        //             // $isOk = false;
        //             // return $isOk;
        //             // return $app->response->redirect("tärning/session?resCode=$resCode&message=$message");
        //         }
        //     }
        //     // die();
        // }  else {  // elseif (isset($_GET['timestamp'])) ends
        //     static::$message .= "...again!";
        // }


        // $timestamp =$request->getPost("timestamp");
        // $ipAddress = htmlentities($request->getPost("ipAddress"));
        //
        // if (isset($timestamp)) {
        //     if ($session->has("timestamp")) {
        //         if ($timestamp !== $session->get("timestamp")) {
        //
        //             $session->set("timestamp", $timestamp);
        //             $session->set("ipAddress", $ipAddress);
        //             $ip = checkIfValidIp($ipAddress);
        //         } else {
        //             echo "Just a reload?!";
        //             $ipAddress = $session->get("ipAddress");
        //         }
        //     }
        // } else {
        //     echo "timestamp is not set!!";
        // }

        // If $_GET['timestamp'] has been set i.e. the HOLD (or ROLL?) button has been clicked
        // } elseif (isset($_GET['timestamp'])) {
        if (null !== $request->getPost('timestamp')) {
        // } elseif (null !== $this->app->request->getGet('timestamp')) {       // Fungerar inte att skicka in $app? ville ej starta spelet då
        // if (isset($_GET['noRolls']) || $playerId === 0) {
            static::$message .= "<br/>POST is SET!";
            static::$message .= "<br/>Inside 'if (isset(\$_POST['timestamp'])' i /webActionPost-routen";
            // echo "noRolls = ";
            // var_dump($_GET['noRolls']);

            // Check if $_SESSION["timestamp"] is not set i.e the previous action was not hold.
            // If $noRollsInThisRound > above 0 i.e the current active player has rolled at least once then it is OK to hold:
            // if (!isset($_SESSION["timestamp"])) {
            if (!$session->has("timestamp")) {
                static::$message .= "<br/>SESSION['timestamp'] is NOT SET!";
                // $_SESSION['timestamp'] = $_GET['timestamp'];
                $session->set('timestamp', $request->getPost('timestamp'));
                // $resCode = $resCode;
                // if ($criterium) {
                //     // $resCode = $this->hold($resCode);
                //     // $this->message .= "<br/>After player {$playerId} has been through the $action-method#2";
                //     $isOk = true;
                //     // $this->message .= "<br/>Possible to {$action} for player {$playerId}: {$isOK}";
                // } else {
                //     $resCode = "notPossibleTo{$action}Yet";
                //     $isOk = false;
                // }
                // $resCode = "notPossibleTo$action";
                // $isOkAndResCode = $this->checkIfCriteriumIsFullfilled($action, $criterium, $resCode);

                $ipAddress = htmlentities($request->getPost("ipAddress"));
                $ip = \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);
                $session->set('ipAddress', $ipAddress);
                // return [$isOkAndResCode[0], $isOkAndResCode[1]];
                // return [$isOk, $resCode];
            // } elseif (isset($_SESSION["timestamp"])) {
            } else {
                // if ($_SESSION["timestamp"] !== $_GET['timestamp']) {
                if ($session->get("timestamp") !== $request->getPost('timestamp')) {
                    static::$message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
                    // $_SESSION['timestamp'] = $_GET['timestamp'];
                    $session->set('timestamp', $request->getPost('timestamp'));

                    $ipAddress = htmlentities($request->getPost("ipAddress"));
                    $ip = \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);
                    $session->set('ipAddress', $ipAddress);
                    // $isOkAndResCode = $this->checkIfCriteriumIsFullfilled($action, $criterium, $resCode);
                    // return [$isOkAndResCode[0], $isOkAndResCode[1]];
                    // return [$isOk, $resCode];
                // } elseif ($_SESSION["timestamp"] === $_GET['timestamp']) {
                } elseif ($session->get("timestamp") === $request->getPost('timestamp')) {
                    static::$message .= "<br/>SESSION is SET & SESS === POST!";
                    $ipAddress = $session->get('ipAddress');
                    // $resCode = "setAndEqual_$action";
                    // unset($_GET["resCode"]);
                    // $app->request->setGet('resCode', null);
                    // $isOk = false;
                    // return $isOk;
                    // return $app->response->redirect("tärning/session?resCode=$resCode&message=$message");
                }
            }
            // die();
        }  else {  // elseif (isset($_GET['timestamp'])) ends
            static::$message .= "<br/>POST is NOT set!";
            static::$message .= "...again!";
            $ipAddress = $session->get('ipAddress');
        }


        // $ipAddress = htmlentities($request->getPost("ipAddress"));

        $session->set("flashmessage", "The Ip form was sent with POST.");



        //
        // function checkIdAdressIsPrivOrRes($ipAddress) {
        //     if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
        //         // echo("$ipAddress is not a private IPv4 Address");
        //         // $session->set(static::$key, $key);
        //         return "not private";
        //     } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
        //         // echo("$ipAddress is not a private IPv6 Address");
        //         // $session->set(self::$key, $key);
        //         return "not Reserved";
        //     } else {
        //         return false;
        //     }
        // }

        // $responses = \http_get("https://api.ipstack.com/{$ipAddress}?access_key={$config['accessKey']}");

        // $ip = checkIfValidIp($ipAddress);

        // if (isset($ip) && $ip) {
        if (isset($coordinates) && $coordinates) {
        // ***********************************

            // $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1";
            $service = "forecast";
            $time = time();
            // $url = "https://api.darksky.net/$service/{$config['accessKeyWeather']}/$lat,$lon,$time";
            $url = "https://api.darksky.net/$service/{$config['accessKeyWeather']}/{$coordinates[0]},{$coordinates[1]},$time";

            // https://api.darksky.net/forecast/6bb8de6851b21e48795f3d8fd996640b/37.8267,-122.4233
            $responseFromDarkSky = \Anna\Curl\Curl::curlAnUrl($url);
            // echo "<br/>responseFromDarkSky = " . $responseFromDarkSky;
            // *************************************************
        }


        // if (isset($ip) && $ip) {
        if (isset($coordinates) && $coordinates) {
        // ***********************************

            // $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1";
            $service = "forecast";
            $now = time();

            for ($i=0; $i<=3; $i++) {
                // $url = "https://api.darksky.net/$service/{$config['accessKeyWeather']}/$lat,$lon,$time";

                $time = $now-$i*86400;
                $urlArray[] = "https://api.darksky.net/$service/{$config['accessKeyWeather']}/{$coordinates[0]},{$coordinates[1]},$time ";

                $responseFromDarkSky = \Anna\Curl\Curl::multiRequest($urlArray, $options = array());
                // https://api.darksky.net/forecast/6bb8de6851b21e48795f3d8fd996640b/37.8267,-122.4233
                // $responseFromDarkSky = \Anna\Curl\Curl::curlAnUrl($url);
                // echo "<br/>responseFromDarkSky = " . $responseFromDarkSky;
                // *************************************************
            }

        }


        // multiRequest($urlArray, $options = array())





        $session->set("message", static::$message);
        if ($responseFromDarkSky && !is_array($responseFromDarkSky)) {
            $session->set("flashmessage", "The response was: $responseFromDarkSky.");
            $session->set("responseFromDarkSky", $responseFromDarkSky);
        //     $isPrivOrRes = checkIdAdressIsPrivOrRes($ipAddress);
        //     if ($isPrivOrRes) {
        //         $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
        //     }
        //     $host = gethostbyaddr($ipAddress);
        //            // echo ("\$host = " );
        //            // var_dump($host);
        //     if (isset($host) && ($host !== false)) {
        //         if ($host !== $ipAddress) {
        //             $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
        //         }
        //     }
        // } else {
        //     $session->set("flashmessage", "$ipAddress is NOT a valid IP Address");
        // self::$responseFromIpStack = $responseFromIpStack;              // Fungerar inte!!!
    } elseif ($responseFromDarkSky && is_array($responseFromDarkSky)) {
        foreach ($responseFromDarkSky as $key => $val) {
            $session->set("flashmessage", "The response was: $val.");
        }
        $session->set("responseFromDarkSky", $responseFromDarkSky);
    //     $isPrivOrRes = checkIdAdressIsPrivOrRes($ipAddress);
    //     if ($isPrivOrRes) {
    //         $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
    //     }
    //     $host = gethostbyaddr($ipAddress);
    //            // echo ("\$host = " );
    //            // var_dump($host);
    //     if (isset($host) && ($host !== false)) {
    //         if ($host !== $ipAddress) {
    //             $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
    //         }
    //     }
    // } else {
    //     $session->set("flashmessage", "$ipAddress is NOT a valid IP Address");
    // self::$responseFromIpStack = $responseFromIpStack;              // Fungerar inte!!!
    }
        return $response->redirect("weather");
    }




    // /**
    //  * Get posted data, analyze it and redirect to the result page.
    //  *
    //  * @return object
    //  */
    // public function processActionGet() : object
    // {
    //     $response = $this->di->get("response");
    //     $session = $this->di->get("session");
    //
    //     // $key = $this->cssUrl . "/" . $style . ".css";
    //     // $keyMin = $this->cssUrl . "/" . $style . ".min.css";
    //
    //     // if ($style === "none") {
    //     //     $session->set("flashmessage", "Unsetting the style and using the default style.");
    //     //     $session->set(self::$key, null);
    //     // } elseif (array_key_exists($keyMin, $this->styles)) {
    //     //     $session->set("flashmessage", "Now using the style '$keyMin'.");
    //     //     $session->set(self::$key, $keyMin);
    //     // } elseif (array_key_exists($key, $this->styles)) {
    //     //     $session->set("flashmessage", "Now using the style '$key'.");
    //     //     $session->set(self::$key, $key);
    //     // }
    //
    //     $session->set("flashmessage", "The Ip form was sent with GET.");
    //     // if ($ip === "4") {
    //     //     $session->set("flashmessage", "The Ip form was sent with GET.");
    //     //     // $session->set(self::$key, null);
    //     // }
    //
    //     // $message = "The Ip form was sent with GET.";
    //     // echo $message;
    //     // die();
    //     // } elseif (array_key_exists($keyMin, $this->styles)) {
    //     //     $session->set("flashmessage", "Now using the style '$keyMin'.");
    //     //     $session->set(self::$key, $keyMin);
    //     // } elseif (array_key_exists($key, $this->styles)) {
    //     //     $session->set("flashmessage", "Now using the style '$key'.");
    //     //     $session->set(self::$key, $key);
    //     // }
    //
    //
    //     // $url = $session->getOnce("redirect", "style");
    //     $url = $session->getOnce("redirect", "ip");
    //
    //     // die("inside processActionGet-routen");
    //     return $response->redirect($url);
    // }



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
