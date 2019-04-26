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

    private $curl = null;

    // /**
    //  * @var string $cssUrl The baseurl to where the css files are.
    //  * @var string $cssDir The path to the directory storing css files.
    //  * @var array  $styles The styles available in the style directory.
    //  * @var string $key    The session key used to store the active style.
    //  */
    // private static $key = "AnaxIpValidator";

    /**
    * Curl2::__construct(). An empty constructor to be able to integrate it in the framework's DI-container.
    *
    * @return self
    */
    public function __construct()
    {
    }

    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    public function init()
    {
        // Initialize a cURL session and store it in $curl (which is a so called cUrl handle)
        $this->curl = curl_init();
        return $this->curl;
    }

    // /**
    //  * CurlInterface::init()
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
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function setOptionsArray($curl, $array, $url)
    public function setOptionsArray($array)
    {
        curl_setopt_array($this->curl, $array);
    }

    /**
     * CurlInterface::init()
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
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function getInfo($curl)
    public function getInfo()
    {
        // Get information regarding a specific transfer
        $result = curl_getinfo($this->curl);
        return $result;
    }

    /**
     * CurlInterface::init()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    // public function close($curl)
    public function close()
    {
        curl_close($this->curl);
    }



    /**
     * Curl::curlAnUrl($url)
     *
     * Curl an url.
     *
     * @param string $url - the Url to curl
     *
     * @return array - the the response array.
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



    // /**
    //  * Curl::curlMultipleUrls($url)
    //  *
    //  * Curl multiple  urls.
    //  *
    //  * @param string $url - the Url to curl
    //  *
    //  * @return array - the the response array.
    //  */
    // public static function curlMultipleUrls($url) : string
    // {
    //     // Get cURL resource
    //     $curl = curl_init();
    //     // Set some options - we are passing in a useragent too here
    //     curl_setopt_array($curl, array(
    //         // Set returntransfer to 1 for curl_exec() to return the result on success (not just True)
    //         CURLOPT_RETURNTRANSFER => 1,
    //         // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
    //         CURLOPT_URL => $url,
    //         CURLOPT_USERAGENT => 'User Agent X'
    //     ));
    //     // Send the request & save response to $response
    //     $response = curl_exec($curl);
    //
    //     var_dump($response);
    //
    //     // Close request to clear up some resources
    //     curl_close($curl);
    //
    //     return $response;
    //
    //
    //
    //     // create both cURL resources
    //     $ch1 = curl_init();
    //     $ch2 = curl_init();
    //
    //     // set URL and other appropriate options
    //     curl_setopt($ch1, CURLOPT_URL, "http://lxr.php.net/");
    //     curl_setopt($ch1, CURLOPT_HEADER, 0);
    //     curl_setopt($ch2, CURLOPT_URL, "http://www.php.net/");
    //     curl_setopt($ch2, CURLOPT_HEADER, 0);
    //
    //     //create the multiple cURL handle
    //     $multihandle = curl_multi_init();
    //
    //     //add the two handles
    //     curl_multi_add_handle($multihandle, $ch1);
    //     curl_multi_add_handle($multihandle, $ch2);
    //
    //     $active = null;
    //     //execute the handles
    //     do {
    //         $mrc = curl_multi_exec($multihandle, $active);
    //     } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    //
    //     while ($active && $mrc == CURLM_OK) {
    //         if (curl_multi_select($multihandle) != -1) {
    //             do {
    //                 $mrc = curl_multi_exec($multihandle, $active);
    //             } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    //         }
    //     }
    //
    //     //close the handles
    //     curl_multi_remove_handle($multihandle, $ch1);
    //     curl_multi_remove_handle($multihandle, $ch2);
    //     curl_multi_close($multihandle);
    // }
    //
    //
    // /**
    //  * Curl::multiRequest($urlArray, $options = array())
    //  *
    //  * Curl multiple  urls.
    //  *
    //  * @param array  $urlArray - array containing the Urls to curl
    //  * @param array $options    - array containing any options like url, header etc, defaults to an empty array
    //  *
    //  * @return array - $result - the result array.
    //  */
    // public static function multiRequest($urlArray, $options = array())
    // {
    //     // array of curl handles
    //     $curlHandles = array();
    //     // data to be returned
    //     $result = array();
    //
    //     // multi handle
    //     $multihandle = curl_multi_init();
    //
    //     // loop through $urlArray and create curl handles
    //     // then add them to the multi-handle
    //
    //     foreach ($urlArray as $id => $val) {
    //         $curlHandles[$id] = curl_init();
    //
    //         $url = (is_array($val) && !empty($val['url'])) ? $val['url'] : $val;
    //         curl_setopt($curlHandles[$id], CURLOPT_URL, $url);
    //         curl_setopt($curlHandles[$id], CURLOPT_HEADER, 0);
    //         curl_setopt($curlHandles[$id], CURLOPT_RETURNTRANSFER, 1);
    //
    //         // post?
    //         if (is_array($val)) {
    //             if (!empty($val['post'])) {
    //                 curl_setopt($curlHandles[$id], CURLOPT_POST, 1);
    //                 curl_setopt($curlHandles[$id], CURLOPT_POSTFIELDS, $val['post']);
    //             }
    //         }
    //
    //         // extra options?
    //         if (!empty($options)) {
    //             curl_setopt_array($curlHandles[$id], $options);
    //         }
    //
    //         curl_multi_add_handle($multihandle, $curlHandles[$id]);
    //     }
    //
    //   // execute the handles
    //     $running = null;
    //     do {
    //         curl_multi_exec($multihandle, $running);
    //     } while ($running > 0);
    //
    //
    //   // get content and remove handles
    //     foreach ($curlHandles as $id => $val) {
    //         $result[$id] = curl_multi_getcontent($val);
    //         curl_multi_remove_handle($multihandle, $val);
    //     }
    //
    //   // all done
    //     curl_multi_close($multihandle);
    //
    //     return $result;
    // }
}
