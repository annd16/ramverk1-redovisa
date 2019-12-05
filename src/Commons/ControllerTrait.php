<?php

/**
 * A module for GeoLocatorController class.
 *
 * This is the module containing the GeoLocatorContoller class.
 *
 * @author  Anna
 */

namespace Anna\Commons;

// use Anax\Commons\ContainerInjectableInterface;
// use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;
use Anna\Commons\GeoLocatorInterface;
use Anna\Commons\GeoLocatorTrait;

/**
 * ControllerTrait
 *
 */
trait ControllerTrait
{
    // use ContainerInjectableTrait;
    use IpValidatorTrait;
    use GeoLocatorTrait;


    /**
     * @var string $message - a message to be displayed
     */
    public static $message = "";


    /**
    * ControllerTrait::__construct(). An empty constructor to be able to integrate it in the framework's DI-container?
    *
    * @return void
    */
    public function __construct()
    {
    }


    /**
    * ControllerTrait::checkIfDestroy().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param object $request the request object.
    * @param object $session the session object.
    * @param object $response the response object.
    * @param string $mount the mount point as a string.
    *
    * @return void
    */
    public function checkIfDestroy($request, $session, $response, $mount)
    {
        // Om destroy finns i GET så avslutas sessionen med header redirect
        if (null !== $request->getGet("destroy")) {
            // echo("\$session inside checkIfDestroy");
            // var_dump($session);
            # Delete cookies and kill session
            $session->destroy($session->get("name"));
            echo "Session has been killed!";
            //
            // echo("\$request->getGet('destroy') inside checkIfDestroy");
            // var_dump($request->getGet('destroy'));
            //
            // echo("\$session inside checkIfDestroy");
            // var_dump($session);

            // die();
            // header("Location: " . \Anax\View\url($mount.'/session'));
            $path = \Anax\View\url("$mount");
            $response->redirect($path);
            // Show incoming variables and view helper functions
            //echo showEnvironment(get_defined_vars(), get_defined_functions());
        }
    }

    /**
     * ControllerTrait::setParamsBasedOnArgsCount()
     *
     * Set parameters based on number of arguments
     *
     * @param object $diService - the $di-object.
     * @param array $argsArray - all other incoming arguments.
     * @param object $modelClass - the name of the model class as a string.
     *
     * @return array - with $request, $classObj.
     */
    public function setParamsBasedOnArgsCount($diService, $argsArray, string $modelClass)
    {
        // var_dump($diService);
        // var_dump($args);
        // In BROWSER:
        $request = $diService->get("request");
        //$weather = null;
        $classObj = null;

        // In UNITTESTS:
        foreach ($argsArray as $key => $val) {
            if (gettype($val) == "object") {
                if (get_class($val) == "Anna\Request\Request") {
                    $request = unserialize(serialize($val));
                //} elseif (get_class($val) == "Anna\ControllerTrait\ControllerTrait") {
                } elseif (get_class($val) == $modelClass) {
                    $classObj = unserialize(serialize($val));
                }
            }
        }
        return [$request, $classObj];
    }


    /**
     * ControllerTrait::checkTimestamp2()
     *
     * This method checks if a "timestamp" is saved in either $_POST or $_SESSION
     * and decides what to do next based on this.
     *
     * @param object $request - a $request object.
     * @param object $session - a $session object.
     * @param string $inputFieldName - the name of the input field of the form.
     * @param object $controllerObj - an object of a controller
     *
     * @return array - an array with $ipAddress, $indataType, $position, $message.
     */
    public function checkTimestamp2($request, $session, $inputFieldName, $controllerObj)
    {
        //$inputFieldsNames = ["ipOrPos" => "ipOrPos"];
        //$data = ["ipAddress", "ipType", "position"];
        //$ipType = "";
        $indataType = "";
        $message = "";
        $message .= "checkTimestamp()!!";
        $ipAddress = "";
        $position = "";
        if (null !== $request->getPost('timestamp')) {
            // static::$message .= "<br/>POST is SET!";
            // static::$message .= "<br/>Inside 'if (isset(\$_POST['timestamp'])' i /webActionPost-routen";
            $message .= "<br/>POST['timestamp']  is SET!";
            $message .= "<br/>null !== \$request->getPost('timestamp')";

            if (!$session->has("timestamp")) {
                // static::$message .= "<br/>SESSION['timestamp'] is NOT SET!";
                $message .= "<br/>SESSION['timestamp'] is NOT SET!";

                $session->set('timestamp', $request->getPost('timestamp'));

                /********************* Controller specific ************************/
                $ipOrPos = $request->getPost($inputFieldName);

                // Sanitize incoming data
                if (is_array($ipOrPos)) {
                    $ipOrPos = "";
                } else {
                    $ipOrPos = htmlentities($ipOrPos);
                }

                //$ipAddress = htmlentities($request->getPost("ipAddress"));
                if (method_exists($controllerObj, "checkIfValidIndata")) {
                    [$ipAddress, $indataType, $position, $message] = $controllerObj->checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position);
                }
                /************************************************************/
            } else {
                if ($session->get("timestamp") !== $request->getPost('timestamp')) {
                    // static::$message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
                    $message .= "<br/>SESSION['timestamp'] is SET & SESS !== POST!";
                    // $_SESSION['timestamp'] = $_GET['timestamp'];
                    $session->set('timestamp', $request->getPost('timestamp'));

                    /********************* Controller specific ************************/
                    //$default = "";
                    //$ipOrPos = htmlentities($request->getPost($inputFieldName, $default));
                    //$ipOrPos = htmlentities($request->getPost($inputFieldName));

                    $ipOrPos = $request->getPost($inputFieldName);

                    // Sanitize incoming data
                    if (is_array($ipOrPos)) {
                        $ipOrPos = "";
                    } else {
                        $ipOrPos = htmlentities($ipOrPos);
                    }
                    if (method_exists($controllerObj, "checkIfValidIndata")) {
                        [$ipAddress, $indataType, $position, $message] = $controllerObj->checkIfValidIndata($ipOrPos, $session, $message, $ipAddress, $position);
                    }
                    /************************************************************/
                } elseif ($session->get("timestamp") === $request->getPost('timestamp')) {
                    // static::$message .= "<br/>SESSION is SET & SESS === POST!";
                    $message .= "<br/>SESSION is SET & SESS === POST!";
                    /********************* Controller specific ************************/
                    $ipAddress = $session->get('ipAddress');
                    $position = $session->get('position');
                    /************************************************************/
                }
            }
            // die();
        } else {  // elseif (isset($_GET['timestamp'])) ends
            // static::$message .= "<br/>POST is NOT set!";
            // static::$message .= "...again!";
            $message .= "<br/>POST is NOT set!";
            $message .= "...again!";
            /********************* Controller specific ************************/
            $ipAddress = $session->get('ipAddress');
            $position = $session->get('position');
        }
        return [$ipAddress, $indataType, $position, $message];
        /**********************************************************************/
    }
}
