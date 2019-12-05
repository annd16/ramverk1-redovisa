<?php

/**
 * A module for Weather class.
 *
 * This is the module containing the Weather class, a model class for the WeatherController.
 *
 * @author  Anna
 */

namespace Anna\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anna\Commons\IpValidatorInterface;
use Anna\Commons\IpValidatorTrait;

use Anna\Commons\GeoLocatorInterface;
use Anna\Commons\GeoLocatorTrait;

/**
 * Weather controller for weather services
 *
 */
class Weather implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    use IpValidatorTrait;
    use GeoLocatorTrait;


    /**
     * @var string $message - a message.
     */
    public static $message = "";

    /**
    * Weather::__construct(). An empty constructor to be able to integrate it in the framework's DI-container?
    *
    * @return void
    */
    public function __construct()
    {
    }


    /**
     * Weather::convertPositionStringToArray()
     *
     * Convert geolocation to an array.
     *
     * @param string $position - the position to convert.
     *
     * @return mixed - an array if valid position, or false otherwise.
     */
    public static function convertPositionStringToArray($position)
    {
        if (is_string($position)) {
            $positionArray = explode(",", $position);
            $positionArray = array_slice($positionArray, 0, 2);
            if (is_numeric($positionArray[0]) && is_numeric($positionArray[1])) {
                return $positionArray;
            } else {
                return false;
            }
        }
    }


    /**
     * Weather:: getResultFromIpStack()
     *
     * Get the result from IPStack as an html string.
     *
     * @param array? $responseFromIpStack - the response from IpSTack
     * @param array? $responseObj - the response object from DarkSky.
     * @param string $result - the result so far.
     * @param integer $key - the index of $responseObj in an array.
     *
     * @return string - the result as an html string.
     */
    public function getResultFromIpStack($responseFromIpStack, $responseObj, $result, $key)
    {
        if ($responseFromIpStack !== null) {
            $result .= "<div>Place:<span>{$responseFromIpStack->city}</span><span>{$responseFromIpStack->region_name}</span><span>{$responseFromIpStack->country_name}</span></div>"
            . "<div>Latitude: <span id='lat'>{$responseFromIpStack->latitude}</span><br/>"
            . "Longitude:  <span id='lon'>{$responseFromIpStack->longitude}</span><br/></div></div>";
        } elseif ($responseFromIpStack === null && $key == 0) {
                $result .= "<div>Timezone:<span>{$responseObj->timezone}</span></div>"
                . "<div>Latitude: <span id='lat'>{$responseObj->latitude}</span><br/>"
                . "Longitude:  <span id='lon'>{$responseObj->longitude}</span><br/></div></div>";

                $time = $responseObj->currently->time;
                $date = date("Y-m-d", $time);
                // echo "date: ";
                // var_dump($date);
                $result .= "<div id='lat'><h3>{$key} {$date}</h3></div>";
        }
        return $result;
    }

    /**
     * Weather:: getResultStringForOneDay()
     *
     * Get the result html string for one day..
     *
     * @param string $result - the result so far.
     * @param object $day - a day object (extracted from DarkSky response)
     *
     * @return string - the result as an html string.
     */
    public function getResultStringForOneDay($result, $day)
    {
        $date = date('Y-M-d h:m', $day->time);
        $icons = $this->getIcons($day->icon);

        $result .= "<div id='lat'>{$date}</div>";
        if (is_array($icons)) {
            foreach ($icons as $key => $icon) {
                $result .= "<i class='{$icon}'></i>";
            }
        } else {
            $result .= "<i class='{$icons}'></i>";
        }
        $summary = isset($day->summary) ? $day->summary : "No summary to display!";
        $result .= "<div id='lat'>{$summary}</div>";
        return $result;
    }


    /**
     * Weather::resultifyResponse()
     *
     * Produce the wanted result string.
     *
     * @param array $responseObjects - an array with responseObjects
     * @param array? $responseFromIpStack - an array?
     * @param array $typeOfRequest - type of request (can either be "forecast" or "history").
     *
     * @return string - the result string.
     */
    public function resultifyResponse($responseObjects, $typeOfRequest, $responseFromIpStack = null)
    {
        $result = "";

        // echo "<br/><br/>responseObjects in resultifyResponse() = ";
        // var_dump($responseObjects);

        // If $responseObects is defined and the length of the array is not 0
        //if (isset($responseObjects) && count($responseObjects) > 0) {
            $counter = 0;
        foreach ($responseObjects as $key => $responseObj) {
            $counter++;
            // echo "key = " . $key;
            // echo "counter = " . $counter;
            //echo "<br/><br/>responseObj in foreach in resultifyResponse() = ";
            //var_dump($responseObj);
            if (isset($responseObj->error)) {
                $result .= "<div>An error was returned from the weather service: <span>code: {$responseObj->code}</span> <span>error: {$responseObj->error}</span></div>";
                return $result;
            }
            if ($responseObj !== null && $responseObj !== "") {
                if ($counter === 1) {
                    $result = $this->getResultFromIpStack($responseFromIpStack, $responseObj, $result, $key);
                }
                // echo "<br/><br/>responseObj in resultifyResponse() = ";
                // var_dump($responseObj);
                //
                // echo "<br/><br/>responseObj->daily in resultifyResponse() = ";
                // var_dump($responseObj->daily);

                if ($typeOfRequest === "history") {
                    //$day = $responseFromDarkSky->daily->data[0];
                    $day = $responseObj->daily->data[0];

                    $result = $this->getResultStringForOneDay($result, $day);
                } else {
                    foreach ($responseObj->daily->data as $key => $day) {
                        // var_dump($day);
                        $result = $this->getResultStringForOneDay($result, $day);
                    }
                }
            } else {
                $result .= "<div id='lat'>There is no result to show here!</div>";
            }
        }
        return $result;
    }


    /**
     * Weather::checkIfValidPositionUsingRegexp()
     *
     * Check if valid position using a regular expression.
     *
     * @param string $position - the position to check
     *
     * @return mixed - the position as a string if valid, or false otherwise.
     */
    public function checkIfValidPositionUsingRegexp(string $position)
    {
        static::$message .= "<br/>checkIfValidPositionUsingRegexp()";
        $matches = [];
        // Matches both integers and floats
        // $regex = '/^(?<latitude>[+-]?[0-8]?[0-9]|[+-]?[0-8]?[0-9]\.[0-9]+|[+-]?90|[+-]?90\.0+)(?<delimeter>,)(?<longitude>[+-]?[0-1]?[0-7]?[0-9]|[+-]?[0-1]?[0-7]?[0-9]\.[0-9]+|[+-]?180|[+-]?180\.0+)$/';
        $regex = '/^(?<latitude>[+-]?[0-8]?[0-9]|[+-]?[0-8]?[0-9]\.[0-9]+|[+-]?90|[+-]?90\.0+)(?<delimeter>,)(?<longitude>[+-]?[0-1]?[0-7]?[0-9]|[+-]?[0-1]?[0-7]?[0-9]\.[0-9]+|[+-]?[0-9]?[0-9]|[+-]?[0-9]?[0-9]\.[0-9]+|[+-]?180|[+-]?180\.0+)$/';

        $result = preg_match($regex, $position, $matches);
        // matches[0] => the full match, matches[1] => the first capturing group (i.e latitude in this case) etc
        // echo "result = ";
        // var_dump($result);
        // die();
        if ($result) {
            // $latitude = $matches[1];
            // //echo "latitude = " . $latitude;
            // static::$message .= "<br/>latitude = " . $latitude;
            // $longitude = $matches[3];
            // //echo "longitude = " . $longitude;
            // static::$message .= "<br/>longitude = " . $longitude;
            // return [$latitude, $longitude];
            return $position;
        } else {
            return false;
        }
    }


    /**
    * Weather::getWeatherForecast().
    *
    * Get the weather forecast for the coming 7 days
    *
    * @param mixed $latitude - the latitude coordinate (integer or float).
    * @param mixed $longitude - the longitude coordinate (integer or float).
    * @param array $config - the configuration array.
    * @param resource $curl - a curl resource/object.
    *
    * @return array responseFromDarkSky - the response from DarkSky as an array
    */
    public function getWeatherForecast($latitude, $longitude, $config, $curl)
    {
        //https://api.darksky.net/forecast/[key]/[latitude],[longitude]
        static::$message .= "<br/>getWeatherForecast()!!!";
        // $urls = [];
        // $today = time();
        // static::$message .= "<br/>today = " . $today;

        $baseUrl = "https://api.darksky.net/forecast/{$config['accessKeyWeather']}/{$latitude},{$longitude}";
        //static::$message .= "baseUrl = " . $baseUrl;
        $counter = 0;
        $name = "darksky";
        $responseFromDarkSky = $curl->curlAnUrl($baseUrl, $name, $counter);
        //static::$message .= "<br/>\$responseFromDarkSky = " . json_encode($responseFromDarkSky);

        return $responseFromDarkSky;
    }

    /**
    * Weather::getWeatherHistory30Days().
    *
    * Get weather observation for thirty days in the past (concurrent curls)
    *
    * @param string $ipAddress - the IP address.
    * @param array $config - the configuration array.
    * @param resource $curl3 - a curl handle.
    *
    * @return string responseFromDarkSky - the response from DarkSky as a json string
    */
    public function getWeatherHistory30Days($latitude, $longitude, $config, $curl)
    {
        //https://api.darksky.net/forecast/[key]/[latitude],[longitude],[time]
        static::$message .= "<br/>getWeatherHistory30Days()!!!";
        $urls = [];
        $today = time();
        static::$message .= "<br/>today = " . $today;

        //$url = "https://api.darksky.net/forecast/{$config['accessKeyWeather']}/{$latitude},{$longitude},$time";
        $baseUrl = "https://api.darksky.net/forecast/{$config['accessKeyWeather']}/{$latitude},{$longitude}";

        //static::$message .= "<br/>baseUrl = " . $baseUrl;
        $day = (60 * 60 * 24);
        static::$message .= "<br/>day = " . $day;
        for ($time = $today; $time > ($today-3*$day); $time = $time-$day) {
            static::$message .= "<br/>time = " . $time;
            $url = $baseUrl . "," . $time;
            //static::$message .= "<br/>url = " . $url;
            array_push($urls, $url);
        }

        //$opts = array();

        $responseFromDarkSky = $curl->curlMultipleUrls($urls, "darksky");
        static::$message .= "<br/>\$responseFromDarkSky = " . json_encode($responseFromDarkSky);

        //var_dump($responseFromDarkSky);
        return $responseFromDarkSky;
    }


    /**
    * Weather::getIcons().
    *
    * Get the icons for a datapoint
    *
    * @param string $iconText - the icon text as received from DarkSky.
    *
    * @return string - the icon string (to be used in a xxxx).
    */
    public function getIcons($iconText)
    {
        // static::$message .= "<br/>getIcons()!!!";

        $iconTexts = ["clear-day", "clear-night", "rain", "snow", "sleet", "wind", "fog", "cloudy", "partly-cloudy-day", "partly-cloudy-night"];
        //$icons = ["fas fa-sun", "fas fa-moon", ["fas fa-cloud-rain", "fas fa-umbrella"], "fas fa-snowflake", ["fas fa-snowflake", "fas fa-cloud-rain"], "fas fa-wind", "fas fa-smog", "fas fa-cloud", "fas fa-cloud-sun", "fas fa-cloud-moon"];
        $icons2 = ["wi wi-day-sunny", "wi wi-night-clear", ["wi wi-rain", "wi wi-umbrella"], "wi wi-snow", ["wi wi-snow", "wi wi-rain"], "wi wi-strong-wind", "wi wi-fog", "wi wi-cloud", "wi wi-day-cloudy", "wi wi-night-alt-cloudy"];

        // // Test för att se vilka ikoner som finns
        // foreach ($icons as $key => $value) {
        //     if (is_array($value)) {
        //         foreach ($value as $key => $icon) {
        //             echo "\n{$icon}";
        //             echo "<i class='{$icon}'></i>";
        //         }
        //     } else {
        //         echo "\n{$value}";
        //         echo "<i class='{$value}'></i>";
        //     }
        // }

        // // Test för att se vilka ikoner som finns
        // foreach ($icons2 as $key => $value) {
        //     if (is_array($value)) {
        //         foreach ($value as $key => $icon) {
        //             echo "\n{$icon}";
        //             echo "<i class='{$icon}'></i>";
        //         }
        //     } else {
        //         echo "\n{$value}";
        //         echo "<i class='{$value}'></i>";
        //     }
        // }

        // Return wi icon
        foreach ($iconTexts as $key => $value) {
            if ($iconText == $value) {
                return $icons2[$key];
            }
        }
    }

    /**
     * Weather::getWeather()
     *
     * This method checks if "timestamp" is in either $_POST or $_SESSION
     * and decides what to do next based on this.
     *
     * @param object $request - a $request object.
     * @param object $session - a $session object.
     *
     * @return array with the strings $ipAddress, $ipType and a $message.
     */
    public function getWeather($typeOfRequest, $weather, $latitude, $longitude, $config, $curl4)
    {
        if ($typeOfRequest === "forecast") {
            $responseFromDarkSky = $weather->getWeatherForecast($latitude, $longitude, $config, $curl4);
        } else {
            $responseFromDarkSky = $weather->getWeatherHistory30Days($latitude, $longitude, $config, $curl4);
        }
        return $responseFromDarkSky;
    }

    /**
     * Weather::checkIfIpOrPosThenGetWeather()
     *
     * This method checks if "timestamp" is in either $_POST or $_SESSION
     * and decides what to do next based on this.
     *
     * @param object $request - a $request object.
     * @param object $session - a $session object.
     *
     * @return array with the strings $ipAddress, $ipType and a $message.
     */
    public function checkIfIpOrPosThenGetWeather($session, $response, $weather, $ipAddress, $position, $typeOfRequest)
    {

        $config = require __DIR__ . "/../../config/config_keys.php";
        $curl3 = $this->di->get("curl3");
        $curl4 = $this->di->get("curl4");

        $responseFromIpStack = null;

        if (isset($ipAddress) && $ipAddress) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>This IP-address was sent: " . $ipAddress);
        // ***********************************

            $responseFromIpStack = $this->getGeoLocation($ipAddress, $config, $curl3);

            static::$message .= "responseFromIpStack at line 439 = " . json_encode($responseFromIpStack);

            $responseFromIpStack = json_decode($responseFromIpStack, false);

            // Sanitize the result
            if (isset($responseFromIpStack)) {
                $session->set("flashmessage", $session->get("flashmessage") . "<br/>A response was received from IpStack.");
                $session->set("responseFromIpStack", $responseFromIpStack);
                $latitude = htmlentities($responseFromIpStack->latitude);
                $longitude = htmlentities($responseFromIpStack->longitude);

                echo "responseFromIpStack->latitude = " . $responseFromIpStack->latitude;
                echo "responseFromIpStack->longitude = " . $responseFromIpStack->longitude;

                $session->set("flashmessage", $session->get("flashmessage") . "<br/>Incoming data from IpStack Latitude = " . $responseFromIpStack->latitude . ", Longitude = " . $responseFromIpStack->longitude);

                $responseFromDarkSky = $this->getWeather($typeOfRequest, $weather, $latitude, $longitude, $config, $curl4);

                //$responseObjects = json_decode($responseFromDarkSky["darksky1"]["data"]);
            }
        } elseif (isset($position) && $position) {
            $session->set("flashmessage", $session->get("flashmessage") . "<br/>A position was sent: " . $position);
            //$positionArray = \Anna\Weather\Weather::checkIfValidPositionUsingRegexpConvertToArray($position);
            //$positionArray = $this->weather->checkIfValidPositionUsingRegexpConvertToArray($position);
            $positionArray = $this->convertPositionStringToArray($position);

            if ($positionArray) {
                $latitude = $positionArray[0];
                $longitude = $positionArray[1];
                $session->set("flashmessage", $session->get("flashmessage") . "<br/>Latitude = " . $latitude . ", Longitude = " . $longitude);

                $responseFromDarkSky = $this->getWeather($typeOfRequest, $weather, $latitude, $longitude, $config, $curl4);

                static::$message .= "<br/>responseFromDarkSky = " . json_encode($responseFromDarkSky);
            } else {
                //$dataValidationError = "No valid geographical position was submitted!";
                static::$dataValidationError .= "<br/>No valid geographical position was submitted!";
                $session->set("dataValidationError", static::$dataValidationError);
                return $response->redirect("weather#form");
            }
        }
        return [$responseFromIpStack, $responseFromDarkSky];
    }


    // /**
    //  * WeatherController::checkIfIpOrPosThenGetWeather2()
    //  *
    //  * This method checks if "timestamp" is in either $_POST or $_SESSION
    //  * and decides what to do next based on this.
    //  *
    //  * @param object $request - a $request object.
    //  * @param object $session - a $session object.
    //  *
    //  * @return array with the strings $ipAddress, $ipType and a $message.
    //  */
    // public function checkIfIpOrPosThenGetWeather2($response, $weather, $ipAddress, $position, $typeOfRequest)
    // {
    //
    //     $config = require __DIR__ . "/../../config/config_keys.php";
    //     $curl3 = $this->di->get("curl3");
    //     $curl4 = $this->di->get("curl4");
    //
    //     $responseFromIpStack = null;
    //
    //     if (isset($ipAddress) && $ipAddress) {
    //         //$session->set("flashmessage", $session->get("flashmessage") . "<br/>This IP-address was sent: " . $ipAddress);
    //     // ***********************************
    //
    //         $responseFromIpStack = $this->getGeoLocation($ipAddress, $config, $curl3);
    //
    //         static::$message .= "responseFromIpStack at line 439 = " . json_encode($responseFromIpStack);
    //
    //         $responseFromIpStack = json_decode($responseFromIpStack, false);
    //
    //         // Sanitize the result
    //         if (isset($responseFromIpStack)) {
    //             $latitude = htmlentities($responseFromIpStack->latitude);
    //             $longitude = htmlentities($responseFromIpStack->longitude);
    //
    //             echo "responseFromIpStack->latitude = " . $latitude;
    //             echo "responseFromIpStack->longitude = " . $longitude;
    //
    //             $responseFromDarkSky = $this->getWeather($typeOfRequest, $weather, $latitude, $longitude, $config, $curl4);
    //         }
    //     } elseif (isset($position) && $position) {
    //         $session->set("flashmessage", $session->get("flashmessage") . "<br/>A position was sent: " . $position);
    //         //$positionArray = \Anna\Weather\Weather::checkIfValidPositionUsingRegexpConvertToArray($position);
    //         //$positionArray = $this->weather->checkIfValidPositionUsingRegexpConvertToArray($position);
    //         $positionArray = $this->convertPositionStringToArray($position);
    //
    //         if ($positionArray) {
    //             $latitude = $positionArray[0];
    //             $longitude = $positionArray[1];
    //             //$session->set("flashmessage", $session->get("flashmessage") . "<br/>Latitude = " . $latitude . ", Longitude = " . $longitude);
    //
    //             $responseFromDarkSky = $this->getWeather($typeOfRequest, $weather, $latitude, $longitude, $config, $curl4);
    //
    //             static::$message .= "<br/>responseFromDarkSky = " . json_encode($responseFromDarkSky);
    //         } else {
    //             //$dataValidationError = "No valid geographical position was submitted!";
    //             static::$dataValidationError .= "<br/>No valid geographical position was submitted!";
    //             return $response->redirect("weather#form");
    //         }
    //     }
    //     return [$responseFromIpStack, $responseFromDarkSky];
    // }


    /**
    * Weather::setDefaultsAndIndata().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param string $ipAddress - the IP address.
    * @param array $config - the configuration array.
    * @param resource $curl3 - a curl handle.
    *
    * @return string responseFromDarkSky - the response from DarkSky as a json string
    */
    public function setDefaultsAndIndata($ipAddress, $position)
    {
        if ($ipAddress &&  $this->checkIfValidIp($ipAddress)) {
            // echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipOrPos"] = $ipAddress;
            //$defaults["ipAddress"] = $ipAddress;
            $indata = "ip";
            // echo "<br/>defaults = ";
            // var_dump($defaults);
        } elseif ($position && $this->checkIfValidPosition($position)) {
            //$defaults["ipAddress"] = $position;
            $defaults["ipOrPos"] = $ipAddress;
            $indata = "position";
        }
        return [$defaults, $indata];
    }


    /**
    * Weather::convertToJsonObject().
    *
    * Check if "destroy" is in $_GET, and if so kill session
    *
    * @param object $responseFromIpStack - the response from IpStack as a json string.
    * @param array $weatherJson - the array to be sent in a json response.
    * @param string $ipAddress - the IP address.
    * @param string $ipType - the IP type (IPv4 or IPv6).
    *
    * @return json $responseFromIpStack - (part of) the response from DarkSky as a json string
    */
    public function convertToJsonObject($responseFromIpStack, $responsesFromDarkSky, $weatherJson, $typeOfRequest)
    {
        //$responseFromDarkSky = json_decode($responseFromDarkSky);

        // echo "typeof2 = " . gettype($responseFromDarkSky);               // ==> object

        // Sanitizing the output
        echo "responsesFromDarkSky = ";
        //var_dump($responsesFromDarkSky);

        $keys = array_keys($responsesFromDarkSky);
        $counter = 0;
        foreach ($responsesFromDarkSky as $key => $responseFromDarkSky) {
            $counter++;
            //$responseFromDarkSky->hourly->summary = htmlentities($responseFromDarkSky->hourly->summary);
            $latitude = htmlentities($responseFromDarkSky->latitude);
            $longitude = htmlentities($responseFromDarkSky->longitude);
            $timezone = htmlentities($responseFromDarkSky->timezone);

            // var_dump($responseFromDarkSky->latitude);
            // var_dump($responseFromDarkSky->longitude);
            // var_dump($responseFromDarkSky->timezone);

            // var_dump($responseFromDarkSky);
            // var_dump($responseFromDarkSky->daily);
            // var_dump($responseFromDarkSky->daily->data);

            if ($keys[0] === $key) {
                $weatherJson["latitude"] = $latitude;
                $weatherJson["longitude"] = $longitude;
                $weatherJson["timezone"] = $timezone;
            }

            if ($typeOfRequest === "history") {
                $day = $responseFromDarkSky->daily->data[0];
                $date = date('Y-M-d h:m', $day->time);
                $summary = $day->summary;
                $weatherJson["typeOfRequest"] = $typeOfRequest;
                $weatherJson["day" . $counter] = ["date" => $date, "summary" => $summary];
            } else {
                foreach ($responseFromDarkSky->daily->data as $key => $day) {
                    $date = date('Y-M-d h:m', $day->time);
                    $summary = $day->summary;
                    $weatherJson["typeOfRequest"] = $typeOfRequest;
                    $weatherJson["day" . ($key+1)] = ["date" => $date, "summary" => $summary];
                }
            }


            //$weatherJson["summary"] = htmlentities($responseFromDarkSky->hourly->summary);
            $weatherJson["map"] = "https://www.openstreetmap.org/?mlat={$latitude}&mlon={$longitude}";
        }
        return $weatherJson;
    }
}
