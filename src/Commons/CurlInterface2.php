<?php

/**
 * A module for CurlInterface2 class.
 *
 * This is the module containing the CurlInterface2 class.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use IpValidatorInterface;

/**
 * CurlInterface2
 *
 * Interface for wrapping up PHP cUrl commands including multi-curls.
 */
interface CurlInterface2
{
    // use ContainerInjectableTrait;

    /**
     * CurlInterface::init()
     *
     * Initialize a cURL session and store it in $curl (which is a so called cUrl handle)
     *
     * @return mixed - returns a cURL handle on success, FALSE on errors.
     */
    public function init();


    /**
     * CurlInterface::setOptionsArray()
     *
     * Set options array.
     *
     * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
     * @param array $array - the array with the options to be set
     *
     * @return void.
     */
    public function setOptionsArray($key, $array);


    /**
     * CurlInterface::exec()
     *
     * Execute a given curl session.
     *
     * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
     *
     * @return mixed - if the CURLOPT_RETURNTRANSFER option is set, it will return the result on success, FALSE on failure, otherwise it will return TRUE on success and FALSE on failure.
     */
    public function exec($key);


    /**
     * CurlInterface::getInfo()
     *
     * Get information regarding a specific transfer (for a given cUrl handle).
     *
     * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public function getInfo($key);

    // /**
    //  * Curl2::errorNo()
    //  *
    //  * Get Curl error number.
    //  *
    //  * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
    //  *
    //  * @return void.
    //  */
    // public function getErrorNo($key);
    //
    //
    // /**
    //  * Curl2::getError()
    //  *
    //  * Get Curl error message.
    //  *
    //  * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
    //  *
    //  * @return void.
    //  */
    // public function getError($key);


    /**
     * CurlInterface::close()
     *
     * Close a cUrl-handle.
     *
     * @param integer $key - a number referring the index of the position in the array in which this indiviudal curl-handle is stored.
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public function close($key);
}
