<?php

/**
* Module caontaining Curl class that deals with cUrl commands.
*/

namespace Anna\Curl;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

//use Anna\Commons\CurlInterface;

//use Anna\Commons\CurlMultiInterface;

/**
 * Curl class deals with cUrl commands.
 */
//class Curl3 implements CurlInterface, CurlMultiInterface
//class Curl3 implements CurlInterface
class Curl4
{
    use ContainerInjectableTrait;

    /**
     * @var resource $curl - a resource object initialized to null.
    */
    protected $ach = null;
    protected $chs = [];
    protected $cmh = null;
    protected static $message = "";


    /**
    * Curl3::__construct(). An empty constructor to be able to integrate it in the framework's DI-container.
    *
    * @return void
    */
    public function __construct()
    {
    }

    /**
     * Curl3::init()
     *
     * Initialize a cURL session and store it in a so called cUrl handle.
     *
     * @return resource.
     */
    private function init()
    {
        // Initialize a cURL session and store it in $curl (which is a so called cUrl handle)
        $ach = curl_init();
        return $ach;
    }


    /**
     * Curl3::setOption()
     *
     * Set an option.
     *
     * @param array $array - the array with the options to be set
     *
     * @return void.
     */
    private function setOption($key, $option, $value)
    {
        curl_setopt($this->chs[$key], $option, $value);
    }


    /**
     * Curl3::setOptionsArray()
     *
     * Set options array.
     *
     * @param array $array - the array with the options to be set
     *
     * @return void.
     */
    private function setOptionsArray($key, $options)
    {
        curl_setopt_array($this->chs[$key], $options);
    }

    /**
     * Curl3::exec()
     *
     * Perform a cURL session
     *
     * @param string $key - the id of the curl-handle
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    private function exec($key)
    {
        // Perform a cURL session: Send the request & save response to $response
        // $response = curl_exec($curl);
        $response = curl_exec($this->chs[$key]);
        return $response;
    }

    /**
     * Curl3::getInfo()
     *
     * Get information regarding a specific transfer (for a given cUrl handle).
     *
     * @return string $result - the result as a string.
     */
    private function getInfo($key)
    {
        // Get information regarding a specific transfer
        $result = curl_getinfo($this->chs[$key]);
        $resultUrl = substr($result['url'], 0, 33);
        $resultUrl .= substr($result['url'], 65);
        $result['url'] = $resultUrl;
        return $result;
    }

    /**
    * Curl3::getContent()
    **
   * curl_multi_add_handle
   * (PHP 5, PHP 7)
   * curl_multi_add_handle — Add a normal cURL handle to a cURL multi handle
   * Description ¶
   * curl_multi_add_handle ( resource $mh , resource $ch ) : int
   * Adds the ch handle to the multi handle mh
   *
   * Curl an url.
   *
   * @param string $url - the Url to curl
   *
   * @return string - the the response as a string.
   */
    private function getContent($key)
    {
        $result = curl_multi_getcontent($this->chs[$key]);
        return $result;
    }

/**
 * Curl3::errorNo()
 *
 * Get Curl error number.
 *
 * @return void.
 */
    private function getErrorNo($key)
    {
        static::$message .= "getErrNo(), \$key = " . $key;
        // Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
        $errorNo = curl_errno($this->chs[$key]);
        return $errorNo;
    }


/**
 * Curl3::errorNo()
 *
 * Get Curl error.
 *
 * @return void.
 */
    private function getError($key)
    {
        // Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
        $error = curl_error($this->chs[$key]);
        return $error;
    }

    /**
     * Curl3::close()
     *
     * Close the curlSession and delete cUrl handle.
     *
     * @return void.
     */
    private function close($key)
    {
        // Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
        curl_close($this->chs[$key]);
    }


    /**
     * Curl::curlAnUrl($url)
     *
     * Curl an url.
     *
     * @param string $url - the Url to curl
     *
     * @return string - the response as a string.
     */
    public function curlAnUrl(string $url, string $name, int &$counter) : array
    {
        $counter++;

        $key = $name . $counter;

        $this->chs = [];
        // Initialize a cURL session and store it in $ach (which is a so called cUrl handle)
        $ach = $this->init();


        // array_push($this->chs, $ach);
        // $key = array_search($ach, $this->chs);

        $this->chs[$key] = $ach;
        // $key = array_search($ach, $this->chs);

        // Set multiple options for a cURL transfer
        $array = array(
           // CURLOPT_RETURNTRANSFER
           // TRUE (or 1) to return the transfer as a string of the return value of curl_exec()
           // instead of outputting it directly.
           CURLOPT_RETURNTRANSFER => 1,
           // CURLOPT_URL = The URL to fetch. This can also be set when initializing a session with curl_init().
           CURLOPT_URL => $url,
           // The contents of the "User-Agent: " header to be used in a HTTP request.
           // The User-Agent request header contains a characteristic string that allows
           // the network protocol peers to identify the application type, operating system,
           // software vendor or software version of the requesting software user agent.
           // Syntax: User-Agent: <product> / <product-version> <comment>
           CURLOPT_USERAGENT => 'User Agent X'
        );

        // $this->setOptionsArray($array, $url);
        $this->setOptionsArray($key, $array);

        // Perform a cURL session: Send the request & save response to $response
        //$response = $this->exec($key);
        $this->exec($key);

        // Handle error
        if ($this->getErrorNo($key)) {
            $responses[$key] = ['data' => null, 'info' => null, 'error' => $this->getError($key)];
        } else {
        // Save successful response
            $responses[$key] = ['data' => $this->getContent($key), 'info' => $this->getInfo($key), 'error' => null];
        }

        // var_dump($response);
        // die();

        // Close a cUrl session/request to clear up some resources
        $this->close($key);

        return $responses;
    }


    /************************************************/
    /***                                          ***/
    /***      Methods related to multicurling     ***/
    /***                                          ***/
    /************************************************/

    /**
     * Curl3::initM()
     *
     * Initialize a multi cURL session and store it in a so called cUrl handle.
     *
     * @return resource.
     */
    private function initM()
    {
        // Initialize a cURL session and store it in $cmh (which is a so called cUrl multi handle)
        $cmh = curl_multi_init();
        return $cmh;
    }


    // /**
    //  * Curl3::setOptionsArrayM()
    //  *
    //  * Set options array.
    //  *
    //  * @param array $array - the array with the options to be set
    //  *
    //  * @return void.
    //  */
    // private function setOptionsArrayM($options)
    // {
    //     curl_multi_setopt($this->cmh, $options);
    // }

    // /**
    //  * Curl3::execM()
    //  *
    //  * Check if valid IP.
    //  *
    //  * @param string $ipAddress - the IP address to check
    //  *
    //  * @return mixed - the IP-version as a string if valid, or false otherwise.
    //  */
    // private function execM($cmh, $active)
    // {
    //     // Perform a cURL session: Send the request & save response to $response
    //     // $response = curl_exec($curl);
    //     $response = curl_multi_exec($cmh, $active);
    //     static::$message .= "\$active = " . $active;  // => Resource id #73
    //     return $response;
    // }


    /**
     * Curl3::selectM()
     *
     * Check if valid IP.
     *
     * @param string $ipAddress - the IP address to check
     *
     * @return mixed - the IP-version as a string if valid, or false otherwise.
     */
    private function selectM()
    {
        // Perform a cURL session: Send the request & save response to $response
        curl_multi_select($this->cmh);
        //static::$message .= "\$active = " . $active;  // => Resource id #73
    }

    // /**
    //  * Curl3::getInfo()
    //  *
    //  * Get information regarding a specific transfer (for a given cUrl handle).
    //  *
    //  * @return string $result - the result as a string.
    //  */
    // private function getInfoM($noOfMessagesLeft)
    // {
    //     // Get information regarding a specific transfer
    //     $result = curl_multi_info_read($this->cmh, $noOfMessagesLeft);
    //     static::$message .= "\$noOfMessagesLeft = " . $noOfMessagesLeft;  // => Resource id #73
    //     return $result;     // [msg: 1, result:3, handle:Resource id #74]
    // }


    /**
     * Curl3::addHandleM()
     *
     * Add a normal cURL handle to a cURL multi handle
     *
     * curl_multi_add_handle ( resource $mh , resource $ch ) : int
     * Adds the ch handle to the multi handle mh
     *
     * Curl an url.
     *
     * @param string $url - the Url to curl
     *
     * @return string - the response as a string.
     */
    private function addHandleM($key)
    {
        $errorCode = curl_multi_add_handle($this->cmh, $this->chs[$key]);
        return $errorCode;
    }

      /**
      * Curl3::removeHandleM()
      *
      * Remove a normal cURL handle to a cURL multi handle
      *
      * curl_multi_add_handle ( resource $mh , resource $ch ) : int
      * Adds the ch handle to the multi handle mh
      *
      * @param string $url - the Url to curl
      *
      * @return string - the the response as a string.
      */
    private function removeHandleM($key)
    {
        $errorCode = curl_multi_remove_handle($this->cmh, $this->chs[$key]);
        return $errorCode;
    }


    /**
     * Curl3::closeM()
     *
     * Close the curlSession and delete cUrl handle for each cUrl-handle in cUrl multi handle.
     *
     * @return void.
     */
    private function closeM()
    {
        // Closes a cURL session and frees all resources. The cURL handle, ch, is also deleted.
        curl_multi_close($this->cmh);
    }

    /**
     * Curl::curlMultipleUrls($urls)
     *
     * Curl multiple urls.
     *
     * @param array $urls - array of the Urls to curl
     * @param string $name - the id/name of the request
     *
     * @return array - the the response as strings in an array.
     */
    //public function curlMultipleUrls($urls, $name, $opts) : array
    public function curlMultipleUrls(array $urls, string $name) : array
    {
        static::$message .= "curlMultipleUrls!!!";

        $requests = array();
        $responses = array();

        $this->chs = [];

        // Initialize a multi cURL session and store it in $cmh (which is a so called cUrl multi handle)
        $this->cmh = $this->initM();
        static::$message .= "cmh = " . $this->cmh;  // => Resource id #73

        // $fileHandle = fopen('curl_errors2.rtf', 'a');

        $opts = [
            CURLOPT_RETURNTRANSFER => 1,
            // USERAGENT = A header sent with http-request with information to the server about the user application etc.
            CURLOPT_USERAGENT => 'User Agent X',
            CURLOPT_VERBOSE => 1,
            //CURLOPT_STDERR => $fileHandle,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 3
         ];

         // Create a request for each url, store it in an array
        foreach ($urls as $key => $url) {
            $id = $name . ($key+1);
               //static::$message .= "key////url = " . $key . "///" . $url;
                $requests[$key] =
                    [
                        'id' => $id,
                        'url' => $url,
                        'opts' => $opts
                    ];
        }

        // For each request
        foreach ($requests as $index => $request) {
            //static::$message .= "key///url = " . $key . "///" . $url;     // Fungerar att kopiera in url:en i webbläsaren, och få svar från DarkSky så det är inget fel med den.
            static::$message .= "key for a request = " . $key;
            $key = $name . ($index+1);

            // Create an individual handle for current request
            $cHandle = $this->init();

            // Store individual cHandle in an array:
            $this->chs[$key] =  $cHandle;

            static::$message .= "request['url'] = " . $request["url"];

            // Set the URL to fetch for this cHandle. This can also be set when initializing a session with curl_init().
            $this->setOption($key, CURLOPT_URL, $request['url']);

            $options = (isset($request["opts"]) ? $request["opts"] + $opts : $opts);

            // Set options
            $this->setOptionsArray($key, $options);

            static::$message .= "cHandle = " . $cHandle; // => Resource id #74/75/76

            // Add individual cHandle to multi handle:
            $this->addHandleM($key);
            //static::$message .= "curl_multi_errno() 1 = " . curl_multi_errno($cmh); // => Resource id #74/75/76
        }

        // Create and initialize active flag
        $active = null;
        do {
            //$response = $this->execM($this->cmh, $active);
            curl_multi_exec($this->cmh, $active);

            // Wait a short time too see if for more activity
            $this->selectM();
        } while ($active > 0);

    // Loop through Curl handles
        foreach ($this->chs as $key => $ch) {
            if ($this->getErrorNo($key)) {
                // Handle error
                static::$message .= "error!!";
                $responses[$key] = ['data' => null, 'info' => null, 'error' => $this->getError($key)];
            } else {
                // Save successful response
                static::$message .= "success!!";
                $responses[$key] = ['data' => $this->getContent($key), 'info' => $this->getInfo($key), 'error' => null];
            }
            // Remove individual handle
            $this->removeHandleM($key);
        }
        // Close a cUrl session/request to clear up some resources
        $this->closeM();

        // fclose($fileHandle);
        return $responses;
    }
}
