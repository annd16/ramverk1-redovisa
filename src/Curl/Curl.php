<?php

/**
* Module caontaining Curl class that deals with cUrl commands.
*/

namespace Anna\Curl;


// use Anax\Commons\ContainerInjectableInterface;
// use Anax\Commons\ContainerInjectableTrait;

// class Curl implements ContainerInjectableInterface

/**
 * Curl class deals with cUrl commands.
 */
class Curl
{
    // use ContainerInjectableTrait;



    /**
     * @var string $cssUrl The baseurl to where the css files are.
     * @var string $cssDir The path to the directory storing css files.
     * @var array  $styles The styles available in the style directory.
     * @var string $key    The session key used to store the active style.
     */
    private static $key = "AnaxIpValidator";



    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";



    /**
     * Curl::curlAnUrl($url)
     *
     * Curl an url.
     *
     * @param string $url - the Url to curl
     *
     * @return array - the the response array.
     */
    public static function curlAnUrl($url) : string
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'User Agent X'
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);

        var_dump($response);

        // Close request to clear up some resources
        curl_close($curl);

        return $response;
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
    public static function curlMultipleUrls($url) : string
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'User Agent X'
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);

        var_dump($response);

        // Close request to clear up some resources
        curl_close($curl);

        return $response;



// create both cURL resources
$ch1 = curl_init();
$ch2 = curl_init();

// set URL and other appropriate options
curl_setopt($ch1, CURLOPT_URL, "http://lxr.php.net/");
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_URL, "http://www.php.net/");
curl_setopt($ch2, CURLOPT_HEADER, 0);

//create the multiple cURL handle
$mh = curl_multi_init();

//add the two handles
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);

$active = null;
//execute the handles
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

//close the handles
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);



    }


    /**
     * Curl::curlAnUrl($url)
     *
     * Curl an url.
     *
     * @param array  $urlArray - array containing the Urls to curl
     * @param array $options    - rray containing any options like url, header etc, defaults to an empty array
     *
     * @return array - $result - the result array.
     */
    public static function multiRequest($urlArray, $options = array()) {

      // array of curl handles
      $curlHandles = array();
      // data to be returned
      $result = array();

      // multi handle
      $mh = curl_multi_init();

      // loop through $urlArray and create curl handles
      // then add them to the multi-handle
      foreach ($urlArray as $id => $val) {

        $curlHandles[$id] = curl_init();

        $url = (is_array($val) && !empty($val['url'])) ? $val['url'] : $val;
        curl_setopt($curlHandles[$id], CURLOPT_URL,            $url);
        curl_setopt($curlHandles[$id], CURLOPT_HEADER,         0);
        curl_setopt($curlHandles[$id], CURLOPT_RETURNTRANSFER, 1);

        // post?
        if (is_array($val)) {
          if (!empty($val['post'])) {
            curl_setopt($curlHandles[$id], CURLOPT_POST,       1);
            curl_setopt($curlHandles[$id], CURLOPT_POSTFIELDS, $val['post']);
          }
        }

        // extra options?
        if (!empty($options)) {
          curl_setopt_array($curlHandles[$id], $options);
        }

        curl_multi_add_handle($mh, $curlHandles[$id]);
      }

      // execute the handles
      $running = null;
      do {
        curl_multi_exec($mh, $running);
      } while($running > 0);


      // get content and remove handles
      foreach($curlHandles as $id => $val) {
        $result[$id] = curl_multi_getcontent($val);
        curl_multi_remove_handle($mh, $val);
      }

      // all done
      curl_multi_close($mh);

      return $result;
    }



    /**
     * Get the session key to use to retrieve the active stylesheet.
     *
     * @return string
     */
    public static function getSessionKey() : string
    {
        return self::$key;
    }



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     foreach (glob("{$this->cssDir}/*.css") as $file) {
    //         $filename = basename($file);
    //         $url = "{$this->cssUrl}/$filename";
    //         $content = file_get_contents($file);
    //         $comment = strstr($content, "*/", true);
    //         $comment = preg_replace(["#\/\*!#", "#\*#"], "", $comment);
    //         $comment = preg_replace("#@#", "<br>@", $comment);
    //         $first = strpos($comment, ".");
    //         $short = substr($comment, 0, $first + 1);
    //         $long = substr($comment, $first + 1);
    //         $this->styles[$url] = [
    //             "shortDescription" => $short,
    //             "longDescription" => $long,
    //         ];
    //     }
    //
    //     foreach ($this->styles as $key => $value) {
    //         $isMinified = strstr($key, ".min.css", true);
    //         if ($isMinified) {
    //             unset($this->styles["$isMinified.css"]);
    //         }
    //     }
    // }


    // /**
    //  * Get data sent with post method, analyze it and return it as json.
    //  *
    //  * @return array
    //  */
    // public function jsonActionPost() : array
    // {
    //     $response = $this->di->get("response");
    //     $request = $this->di->get("request");
    //     $session = $this->di->get("session");
    //
    //     $json = [];
    //
    //     // $key = $request->getPost("ipvalidator");
    //     //
    //     $ipAddress = htmlentities($request->getPost("ipAddress"));
    //
    //     // $session->set("flashmessage", "The Ip form was sent with POST.");
    //
    //
    //     function checkIfValidIp($ipAddress) {
    //         if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    //             // echo("$ipAddress is a valid IPv4 address");
    //             // $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
    //             // $session->set(self::$key, $key);
    //             return "IPv4";
    //         } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
    //             // echo("$ipAddress is a valid IPv6 address");
    //             // $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
    //             // $session->set(self::$key, $key);
    //             return "IPv6";
    //         } else {
    //             return false;
    //         }
    //     }
    //
    //     function checkIfAdressIsPrivOrRes($ipAddress) {
    //         if (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
    //             // echo("$ipAddress is not a private IPv4 address");
    //             // $session->set(self::$key, $key);
    //             return "private";
    //         } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
    //             // echo("$ipAddress is not a private IPv6 address");
    //             // $session->set(self::$key, $key);
    //             return "reserved";
    //         } else {
    //             return false;
    //         }
    //     }
    //
    //     // foreach ($ipAddresses as $key => $ipAddress) {
    //         $ipJson  =
    //             [   "ip" => "",
    //                 "version" => "",
    //                 "type" => "",
    //                 "host" => "",
    //                 "message" => "",
    //             ];
    //         $ip = checkIfValidIp($ipAddress);
    //         // echo "ip = ";
    //         // var_dump($ip);
    //         // die();
    //         if ($ip) {
    //             // echo "ip!!!";
    //             $ipJson["ip"] = $ipAddress;
    //             $ipJson["version"] = $ip;
    //             $ipJson["message"] = "$ipAddress is a valid $ip address";
    //             $isPrivOrRes = checkIfAdressIsPrivOrRes($ipAddress);
    //             if ($isPrivOrRes) {
    //                 $ipJson["type"] = $isPrivOrRes;
    //             }
    //             $host = gethostbyaddr($ipAddress);
    //                    // echo ("\$host = " );
    //                    // var_dump($host);
    //             if (isset($host) && ($host !== false)) {
    //                 if ($host !== $ipAddress) {
    //                     $ipJson["host"] = $host;
    //                 }
    //             }
    //         } else {
    //             $ipJson["message"] = "$ipAddress is NOT a valid IP address";
    //         }
    //         $json[] = $ipJson;
    //     // }
    //     return [$json];
    // }




        // /**
        //  * This sample method action takes a variadic list of arguments:
        //  * GET mountpoint/variadic/
        //  * GET mountpoint/variadic/<value>
        //  * GET mountpoint/variadic/<value>/<value>
        //  * GET mountpoint/variadic/<value>/<value>/<value>
        //  * etc.
        //  *
        //  * @param array $value as a variadic parameter.
        //  *
        //  * @return string
        //  */
        // // public function variadicActionGet(...$value) : string
        // public function jsonActionGet(...$ipAddresses) : array
        // {
        //     $messages = [];
        //     $response = $this->di->get("response");
        //     $request = $this->di->get("request");
        //     $session = $this->di->get("session");
        //
        //     $session->set("flashmessage", "The Ip form was sent with GET.");
        //     $session->set("flashmessage", "__METHOD__ ". "\$db is {$this->db}, got '" . count($ipAddresses) . "' arguments: " . implode(', ', $ipAddresses));
        //
        //     // $key = $request->getPost("ipvalidator");
        //     // $ipAddresses = $request->getGet("ipAddresses");      Fungerar inte
        //
        //     // var_dump($ipAddresses);
        //     // die();
        //     foreach ($ipAddresses as $key => $ipAdress) {
        //         if (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        //             $messages[] = "$ipAdress is a valid IPv6 address";
        //         } elseif (filter_var($ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        //             $messages[] = "$ipAdress is a valid IPv4 address";
        //         } else {
        //             $messages[] = "$ipAdress is NOT a valid IPv4 or IPv6 address!";
        //         }
        //     }
        //
        //
        //     $json = ["messages" => $messages,
        //              "ipAddresses" => $ipAddresses];
        //     // die("inside jsonActionGet-routen");
        //     return [$json];
        //     // Deal with the action and return a response.
        //
        //
        // }


        // /**
        //  * Get data sent with get method, analyze it and return it as json.
        //  *
        //  * @return array
        //  */
        // public function jsonActionGet(...$ipAddresses) : array
        // {
        //     $response = $this->di->get("response");
        //     $request = $this->di->get("request");
        //     $session = $this->di->get("session");
        //
        //     $json = [];
        //
        //
        //
        //     // $key = $request->getPost("ipvalidator");
        //     //
        //     // $ipAddress = htmlentities($request->getPost("ipAddress"));
        //
        //     $session->set("flashmessage", "The Ip form was sent with GET.");
        //
        //
        //     function checkIfValidIp($ipAddress) {
        //         if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        //             // echo("$ipAddress is a valid IPv4 address");
        //             // $session->set("flashmessage", "$ipAddress is a valid IPv4 address");
        //             // $session->set(self::$key, $key);
        //             return "IPv4";
        //         } elseif (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        //             // echo("$ipAddress is a valid IPv6 address");
        //             // $session->set("flashmessage", "$ipAddress is a valid IPv6 address");
        //             // $session->set(self::$key, $key);
        //             return "IPv6";
        //         } else {
        //             return false;
        //         }
        //     }
        //
        //     function checkIfAdressIsPrivOrRes($ipAddress) {
        //         if (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
        //             // echo("$ipAddress is not a private IPv4 address");
        //             // $session->set(self::$key, $key);
        //             return "private";
        //         } elseif (!filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)) {
        //             // echo("$ipAddress is not a private IPv6 address");
        //             // $session->set(self::$key, $key);
        //             return "reserved";
        //         } else {
        //             return false;
        //         }
        //     }
        //
        //     foreach ($ipAddresses as $key => $ipAddress) {
        //         $ipJson  =
        //             [   "ip" => "",
        //                 "version" => "",
        //                 "type" => "",
        //                 "host" => "",
        //                 "message" => "",
        //             ];
        //         $ip = checkIfValidIp($ipAddress);
        //         // echo "ip = ";
        //         // var_dump($ip);
        //         // die();
        //         if ($ip) {
        //             // echo "ip!!!";
        //             $ipJson["ip"] = $ipAddress;
        //             $ipJson["version"] = $ip;
        //             $ipJson["message"] = "$ipAddress is a valid $ip address";
        //             $isPrivOrRes = checkIfAdressIsPrivOrRes($ipAddress);
        //             if ($isPrivOrRes) {
        //                 $ipJson["type"] = $isPrivOrRes;
        //             }
        //             $host = gethostbyaddr($ipAddress);
        //                    // echo ("\$host = " );
        //                    // var_dump($host);
        //             if (isset($host) && ($host !== false)) {
        //                 if ($host !== $ipAddress) {
        //                     $ipJson["host"] = $host;
        //                 }
        //             }
        //         } else {
        //             $ipJson["message"] = "$ipAddress is NOT a valid IP address";
        //         }
        //         $json[] = $ipJson;
        //     }
        //     return [$json];
        // }
}
