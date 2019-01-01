<?php

/**
 * A module for IpValidatorController class.
 *
* Module with IpValidatorController class that contains routes for IpValidation - web-implementation.
*/

namespace Anna\IpValidator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;

/**
 * IpValidator controller contains routes for IpValidation - web-implementation
 */
class IpValidatorController implements ContainerInjectableInterface, IpValidatorInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;

    /**
     * Display the IP-validator form on a rendered page.
     *
     * @param array $args - as a variadic parameter.
     *
     * @return object
     */
    public function indexAction(...$args) : object     // Test om den kan ta ett request-objekt som inparameter (ändrat fr. public function indexAction() : object)
    {
        $form =  require __DIR__ . "/../../config/form_ipvalidation.php";         // fungerar!
        $navbarConfig =  require __DIR__ . "/../../config/navbar/navbar_sub.php";         // fungerar!


        $formIp = null;
        $game = "ipvalidation";
        $class2 = "session";
        $method = "post";
        $title = "Ipvalidator";
        $mount = "ip";
        $defaults = [];
        $conditions = [];

        $form4 = $this->di->get("form4");
        // $form4 = $form4->init();
        if (count($args) === 0) {
            $request = $this->di->get("request");
        } else {
            $request = $args[0];
        }
        $response = $this->di->get("response");
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $session->set("flashmessage", $session->get("flashmessage") . "<br/>Inside indexAction!");

        // Om destroy finns i GET så avslutas sessionen med header redirect
        if (null !== $request->getGet("destroy")) {
            # Delete cookies and kill session
            $session->destroy($session->get("name"));

            echo "Session has been killed!";

            // header("Location: " . \Anax\View\url($mount.'/session'));
            $path = \Anax\View\url("$mount");
            $response->redirect($path);
        }

        if (!$session->has('ipAddress')) {
            // Get the IP adress from the requesterer, if available

            // To use the IpValidatorTrait instead...
            // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer($request);
            $ipAddress = $this->getClientIpServer($request);            // Fungerar??
            // $session->set("ipAddress", $ipAddress);
        } else {
            $ipAddress = $session->get("ipAddress");
        }
        // echo "<br/>ipAddress = " . $ipAddress;

        // $active = $session->get(self::$key, null);

        // if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
        if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
            // echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            // echo "<br/>defaults = ";
            // var_dump($defaults);
        }

        // $formVars = \Anna\Form3\Form3::populateFormVars4($form, $this->di->get("request"), true, $defaults);
        $formVars = $form4->populateFormVars4($form, $this->di->get("request"), true, $defaults);
        // echo "An IP form has been created";
        $validNames = ["ipAddress", "submit"];

        // $formIp = new \Anna\Form3\Form3($formVars, $form, ["Web", "Json", "GetMyIp"], $validNames, 3);
        $formIp = $form4->init($formVars, $form, ["Web", "Json", "GetMyIp"], $validNames, 3);

        $page->add("anax/ipvalidator/index", [
            "session" => $session,
            "navbarConfig" => $navbarConfig,
        ]);
        if (isset($formIp)) {
        // $page->add("anax/form_start/default", $data);
            $page->add("anax/form/default", [
            "formIp" => $formIp,
            // "formAttrs" => $formAttrs
            "mount" => $mount,
            "formAttrs" => [
            // "game" => $game,
            "game" => $game,
            "save" => $class2,
            "method" => $method,
            "conditions" => $conditions,
            ]
            ]);
        }

        return $page->render([
            "title" => $title,
        ]);
    }

    // /**
    //  * Get 'getted' data, analyze it and redirect to the result page.
    //  *
    //  * @return object
    //  */
    // public function webActionGet() : object
    // {
    //     $response = $this->di->get("response");
    //     $request = $this->di->get("request");
    //     $session = $this->di->get("session");
    //     $key = $request->getPost("ipvalidator");
    //
    //     $destroy = htmlentities($request->getGet("destroy"));
    //
    //
    //     echo "<br/>Inside webActionGetDestroy!!!";
    //     die();
    //     // $session->set("flashmessage", "The Ip form was sent with POST.");
    //
    //     return $response->redirect("ip?destroy=true");
    // }

    /**
     * Get posted data, analyze it and redirect to the result page.
     *
     * @param array $args - as a variadic parameter.
     *
     * @return object
     */
    public function webActionPost(...$args)
    {
        if (count($args) === 0) {
            // die("if");
            $request = $this->di->get("request");
        } else {
            // die("else");
            $request = $args[0];
            // echo ("args[0] = ");
            // var_dump($args[0]);
            // die();
        }
        $response = $this->di->get("response");
        // $request = $this->di->get("request");
        $session = $this->di->get("session");
        // $key = $request->getPost("ipvalidator");

        $ipAddress = htmlentities($request->getPost("ipAddress"));

        $session->set("flashmessage", "The Ip form was sent with POST.");

        // // Fetch the client IP address if no IP-address is stored in session
        // // if (!$session->has('ipAddress')) {
        //     // Get the IP adress from the requesterer, if available
        //     $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer();
        //
        // // } else {
        // //     $ipAddress = $session->get("ipAddress");
        // // }
        // echo "<br/>ipAddress = " . $ipAddress;
        // die();

        // if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
        //     echo "<br/>the pre-filled IP-address is valid!";
        //     $defaults["ipAddress"] = $ipAddress;
        //     echo "<br/>defaults = ";
        //     var_dump($defaults);
        // }

        // $ip = Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);
        // $ipType = IpValidator::checkIfValidIp($ipAddress);
        $ipType = $this->checkIfValidIp($ipAddress);
        if ($ipType) {
            $session->set("flashmessage", "$ipAddress is a valid $ipType address.");
            // $isPrivOrRes = IpValidator::checkIfAdressIsPrivOrRes($ipAddress);
            $isPrivOrRes = $this->checkIfAdressIsPrivOrRes($ipAddress);
            if ($isPrivOrRes) {
                $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
            }
            $host = gethostbyaddr($ipAddress);
                   // echo ("\$host = " );
                   // var_dump($host);
            if (isset($host) && ($host !== false)) {
                if ($host !== $ipAddress) {
                    $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
                }
            }
            $session->set("ipAddress", $ipAddress);         // Flyttat 181221: Endast om giltig ipAddress sparas den i session.
        } else {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is NOT a valid IP address");
        }
        // $session->set("ipAddress", $ipAddress);
        return $response->redirect("ip");
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

        // Get the IP adress from the requesterer, if available
        // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer($request);


        // // Test 181220
        //
        // $ipValidator = new IpValidator();
        // $ipValidator->setDi($this->di);
        // $ipValidator->request->server;

        // printf("\$di inside GetMyIpActionPost =  ");
        // var_dump($this->di);
        // die();

        // printf("\$ipValidator->request->server inside GetMyIpActionPost =  ");
        // var_dump($ipValidator->di->get("request")->server);

        // // Show incoming variables and view helper functions
        // echo \Anax\View\showEnvironment(get_defined_vars(), get_defined_functions());

        // echo "<br/>request->server inside getMyIpActionPost()";
        // var_dump($request->server);
        // die();

        // echo "ipValidator = ";
        // var_dump($ipValidator);

        // printf("\$request inside GetMyIpActionPost =  ");
        // var_dump($request);
        //
        // printf("\$request->server inside GetMyIpActionPost =  ");
        // var_dump($request->server);
        //
        // printf("\$session inside GetMyIpActionPost =  ");
        // var_dump($session);

        // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer($request);
        // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpServer($request);
        $ipAddress = $this->getClientIpServer($request);

        // echo "<br/>ipAddress in getMyIpActionPost = " . $ipAddress;

        // $ipType = IpValidator::checkIfValidIp($ipAddress);
        $ipType = $this->checkIfValidIp($ipAddress);
        if ($ipType) {
            // $session->set("flashmessage", "$ipAddress is a valid $ip address.");
            $session->append("flashmessage", "$ipAddress is a valid $ipType address.");
            $isPrivOrRes = $this->checkIfAdressIsPrivOrRes($ipAddress);
            if ($isPrivOrRes) {
                $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is $isPrivOrRes.");
            }
            $host = gethostbyaddr($ipAddress);
                   // echo ("\$host = " );
                   // var_dump($host);
            if (isset($host) && ($host !== false)) {
                if ($host !== $ipAddress) {
                    $session->set("flashmessage", $session->get("flashmessage") . "<br>The domain name (i.e. the host name) is $host");
                }
            }
        } else {
            // $session->set("flashmessage", "<br/>$ipAddress is NOT a valid IP address");
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>$ipAddress is NOT a valid IP address");
        }
        $session->set("ipAddress", $ipAddress);
        return $response->redirect("ip");
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

        // ob_start();
        echo "Inside catchAll() in IpValidatorController!";
        // $output = ob_get_contents();
        // ob_end_clean();
        return;             // If void is returned then it continues to search in '\Anna\IpValidatorJson\IpValidatorJsonController'
    }
}
