<?php

/**
* Module caontaining Curl class that deals with cUrl commands.
*/

namespace Anna\Curl;

// use Anax\Commons\ContainerInjectableInterface;
// use Anax\Commons\ContainerInjectableTrait;

use Anna\Commons\CurlInterface;

// class Curl implements ContainerInjectableInterface

/**
 * Curl class deals with cUrl commands.
 */
class Curl2 implements CurlInterface
{
    // use ContainerInjectableTrait;

    /**
     * @var resource $curl - a resource object initialized to null.
    */
    private $curl = null;

    /**
    * Curl2::__construct(). An empty constructor to be able to integrate it in the framework's DI-container.
    *
    * @return void
    */
    public function __construct()
    {
    }

    /**
     * Curl2::init()
     *
     * Initialize a cURL session and store it in a so called cUrl handle.
     *
     * @return resource.
     */
    public function init()
    {
        // Initialize a cURL session and store it in $curl (which is a so called cUrl handle)
        $this->curl = curl_init();
        return $this->curl;
    }

    // /**
    //  * Curl2::init()
    //  *
    //  * Check if valid IP.
    //  *
    //  * @param string $ipAddress - the IP address to check
    //  *
    //  * @return mixed - the IP-version as a string if valid, or false otherwise.
    //  */
    // // public function setOptionsArray($curl, $array, $url)
    // public function setOptionsArray($array, $url)
    // {
    //     curl_setopt_array($this->curl, $array);
    // }

    /**
     * Curl2::setOptionsArray()
     *
     * Set options array.
     *
     * @param array $array - the array with the options to be set
     *
     * @return void.
     */
    public function setOptionsArray($array)
    {
        curl_setopt_array($this->curl, $array);
    }

    /**
     * Curl2::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public function exec()
    {
        // Perform a cURL session: Send the request & save response to $response
        // $response = curl_exec($curl);
        $response = curl_exec($this->curl);
        return $response;
    }

    /**
     * Curl2::getInfo()
     *
     * Get information regarding a specific transfer (for a given cUrl handle).
     *
     * @return string $result - the result as a string.
     */
    public function getInfo()
    {
        // Get information regarding a specific transfer
        $result = curl_getinfo($this->curl);
        return $result;
    }

    /**
     * Curl2::close()
     *
     * Close the curlSession and delete cUrl handle.
     *
     * @return void.
     */
    public function close()
    {
        // Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
        curl_close($this->curl);
    }



    /**
     * Curl::curlAnUrl($url)
     *
     * Curl an url.
     *
     * @param string $url - the Url to curl
     *
     * @return string - the the response as a string.
     */
    public function curlAnUrl($url) : string
    {

        // $curlObj = new Curl2();
        // Initialize a cURL session and store it in $curl (which is a so called cUrl handle)
        $curl = $this->init();

        $array = array(
           // CURLOPT_RETURNTRANSFER
           // TRUE (or 1) to return the transfer as a string of the return value of curl_exec()
           // instead of outputting it directly.
           CURLOPT_RETURNTRANSFER => 1,
           // CURLOPT_URL
           // The URL to fetch. This can also be set when initializing a session with curl_init().
           // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
           CURLOPT_URL => $url,
           // The contents of the "User-Agent: " header to be used in a HTTP request.
           // The User-Agent request header contains a characteristic string that allows
           // the network protocol peers to identify the application type, operating system,
           // software vendor or software version of the requesting software user agent.
           // Syntax: User-Agent: <product> / <product-version> <comment>
           CURLOPT_USERAGENT => 'User Agent X'
        );
        // Set multiple options for a cURL transfer - we are passing in a useragent too here
        // $this->setOptionsArray($array, $url);
        $this->setOptionsArray($array);

        // Perform a cURL session: Send the request & save response to $response
        $response = $this->exec($curl);

        // var_dump($response);
        // die();

        // Get information regarding a specific transfer
        $this->getinfo($curl);

        // Close a cUrl session/request to clear up some resources
        $this->close($curl);

        return $response;
    }
}
