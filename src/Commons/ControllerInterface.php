<?php

/**
 * A module for ControllerInterface class.
 *
 * This is the module containing the ControllerInterface class for Geolocation.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * ControllerInterface
 *
 * An interface gathering methods that possibly could be useful for more than one Controller.
 */
interface ControllerInterface
{
    /**
    * ControllerInterface::checkIfDestroy().
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
    public function checkIfDestroy($request, $session, $response, $mount);


    /**
     * ControllerInterface::setParamsBasedOnArgsCount().
     *
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @param object $diService - the $di-object.
     * @param array $argsArray - all other incoming arguments.
     * @param object $modelClass - the name of the model class as a string.
     *
     * @return array - with $request, $classObj.
     */
    public function setParamsBasedOnArgsCount($diService, $argsArray, string $modelClass);


    // /**
    //  * ControllerInterface::checkTimestamp()
    //  *
    //  * This method checks if "timestamp" is in either $_POST or $_SESSION
    //  * and decides what to do next based on this.
    //  *
    //  * @param object $request - a $request object.
    //  * @param object $session - a $session object.
    //  *
    //  * @return array with the strings $ipAddress, $ipType and a $message.
    //  */
    // public function checkTimestamp($request, $session);


    /**
     * ControllerInterface::checkTimestamp2()
     *
     * This method checks if "timestamp" is in either $_POST or $_SESSION
     * and decides what to do next based on this.
     *
     * @param object $request - a $request object.
     * @param object $session - a $session object.
     * @param string $inputFieldName - the name of the input field of the form.
     * @param object $controllerObj - an object of a controller
     *
     * @return array - an array with $ipAddress, $indataType, $position, $message.
     */
    public function checkTimestamp2($request, $session, $inputFieldName, $controllerObj);
}
