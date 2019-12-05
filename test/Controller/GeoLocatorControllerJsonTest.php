<?php

namespace Anna\GeoLocatorJson;

use Anax\DI\DIFactoryConfig;
use \Anax\Response\Response;
use \Anna\Request\Request;
use \Anna\Session\Session2;
use \Anna\Geolocator\GeoLocator;

use PHPUnit\Framework\TestCase;

/**
 * Test the GeoLocatorController.
 */
class GeoLocatorControllerJsonTest extends TestCase
{

    private $request;
    private $response;
    // Create the di container.
    protected $di;
    protected $controller;

    /**
     * Set up a request object
     *
     * @return void
     */
    public function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new GeoLocatorJsonController();
        $this->controller->setDI($this->di);

        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anna\Request\Request();
        // $this->session = new \Anna\Session\Session2();

        $this->response = new Response();
        $this->request = new Request();
        $this->session = new Session2();

        $this->request->setGlobals(
            [
                'server' => [
                    'REQUEST_SCHEME' => "http",
                    'HTTPS'       => null, //"on",
                    'SERVER_NAME' => "localhost",
                    'SERVER_PORT' => "8081",
                    'REQUEST_URI' => '/dbwebb/ramverk1/me/redovisa/htdocs/ip/getmyip',
                    'SCRIPT_NAME' => "/index.php",
                ],
                'post' => [
                    'ipAddress' => null,
                    'timestamp' => 1544913716,
                    'json' => "Json",
                ],
                'get' => [
                    'ipAddress' => "145.38.5.6",
                    'timestamp' => 1544913716,
                    'json' => "Json",
                ]
            ]
        );

        // Setup the geolocator
        //$this->geolocator = new \Anna\Geolocator\GeoLocator();
        $this->geolocator = new GeoLocator();

        $this->ipvalMock = $this->getMockForTrait('\Anna\Commons\IpValidatorTrait');

        $this->geolocMock = $this->createMock('\Anna\GeoLocator\GeoLocator');

        $this->curl2Mock = $this->createMock('\Anna\Curl\Curl2');
    }


        /**
         * Test
         *
         * @param string $ipAddress
         *
         * @return void
         *
         */
    public function testInitialize()
    {
        $myObject = $this->controller->initialize();
        $this->assertInstanceOf("\Anna\Geolocator\Geolocator", $myObject);
    }

    /**
     * Test the route "jsonActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     */
    public function testJsonActionPost()
    {
        //$geolocator = new \Anna\Geolocator\GeoLocator();
        $geolocator = new GeoLocator();

        $ipAddress = "145.38.5.6";
        $expected1 = 'ipType is set ';
        $expected2 = '{"ip":"145.38.5.6","hostname":"145.38.5.6","type":"ipv4","continent_code":"EU","continent_name":"Europe","country_code":"NL","country_name":"Netherlands","region_code":null,"region_name":null,"city":null,"zip":null,"latitude":52.3824,"longitude":4.8995,"location":{"country_flag":"http:\/\/assets.ipstack.com\/flags\/nl.svg","country_flag_emoji":"\ud83c\uddf3\ud83c\uddf1"}}';
        // echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;
        //
        // // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
        // if ($ipAddress && $this->ipvalMock->checkIfValidIp($ipAddress)) {
        //     echo "<br/>the pre-filled IP-address is valid!";
        //     $defaults["ipAddress"] = $ipAddress;
        //     echo "<br/>defaults = ";
        //     var_dump($defaults);
        // }
        // $res = $this->controller->indexAction($this->request, $this->geolocator);
        // $this->assertInstanceOf("\Anax\Response\Response", $res);
        // $body = $res->getBody();
        // $exp = "| ramverk1</title>";
        // $this->assertContains($exp, $body);


        $this->request->setPost("ipAddress", $ipAddress);
        // $this->request->setPost("timestamp", $timestamp);
        // $this->request->setPost("web", $web);



        // $res = $this->controller->jsonActionPost($this->request, $this->geolocator);
        $res = $this->controller->jsonActionPost($this->request, $geolocator);
        $this->assertInternalType("array", $res);
        // $body = $res->getBody();

        // Nytt 190424

        echo("\n\$res i geolocatorjsoncontrollertest = ");
        var_dump($res);
        // die();

        $json = $res[0];
        $this->assertArrayHasKey("message", $json);
        $this->assertContains($expected1, $json["message"]);


        // echo "\nexpected = ";
        // var_dump($expected);

        // $exp = "ip address";
        $json = $res[0];

        echo("\njson-array inside geolocatorjsoncontrollertest = ");
        var_dump($json);

        // Det skapade geolocMock-objektet verkar inte användas, men det får vara så för tillfället...
        $this->geolocMock->method("getGeoLocation")->with("145.38.5.6")->will($this->returnValue($expected2));
        $this->geolocator = $this->geolocMock;
        $res = $this->controller->jsonActionPost($this->request, $this->geolocator);
        $this->assertInternalType("array", $res);

        // $expected = "No response from IpStack!!";
        // $expected3 = "incoming ip";
        $this->assertContains($expected1, $json["message"]);
        // $this->assertArrayHasKey("message", $res);
    }

    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddressNotValid()
    {
        $expectedValues = ['ipType is not set '];
        return [
        //    ["", "NOT a valid"],
        //    ["192.96.76.1", "valid Ipv4"],
        //    ["145.38.5.6", "valid Ipv4"],
           // ["35.158.84.49"],
           // ["23.34"],
           // ["35.158.84.49, 23.34"],
            ["145.38", $expectedValues[0]],
            // ["128.33.2.1", 2002345678, "Web", "valid Ipv4"],
            // ["::", 2002345678, "Web", "reserved"],
            // // ["::ffff:0.0.0.0", 2002345678, "Web", "reserved"],
            // ["145.38", 1544913716, "Web", "NOT a valid"],
        ];
    }


        /**
         * Test the route "jsonActionPost".
         *
         * @param string $ipAddress
         *
         * @return void
         *
         * @dataProvider providerIpAddressNotValid
         */
    public function testJsonActionPostNoValidIp($ipAddress, $expected)
    {
        //$geolocator = new \Anna\Geolocator\GeoLocator();
        $geolocator = new GeoLocator();

        $res = $this->controller->jsonActionPost($ipAddress, $this->request, $geolocator);

        $json = $res[0];

        $this->assertContains($expected, $json["message"]);
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddressPost()
    {
        return [
            [""],
            ["192.96.76.1"],
            ["145.38.5.6"],
            // ["35.158.84.49"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }

    /**
     * Test the route "jsonActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddressPost
     */
    public function testJsonActionPostNoRequest()
    {
        // $controller = new GeoLocatorController();
        // $controller->initialize();
        // $ipAddressPost = $this->di->get("request")->getPost("ipAddress");
        // $timestampPost = $this->di->get("request")->getPost("timestamp");
        // $submitValuePost = $this->di->get("request")->getPost("json");

        $ipAddressPost = $this->request->getPost("ipAddress");
        $timestampPost = $this->request->getPost("timestamp");
        $submitValuePost = $this->request->getPost("json");

        print_r("<br/>ipAddressPost = ", true);
        var_dump($ipAddressPost);
        print_r("<br/>timestampPost = ", true);
        var_dump($timestampPost);
        print_r("<br/>submitValuePost = ", true);
        var_dump($submitValuePost);


        $res = $this->controller->jsonActionPost($this->geolocator);
        $this->assertInternalType("array", $res);
        // $body = $res->getBody();
        $expected = "incoming ip";
        $json = $res[0];
        // $this->assertContains($exp, $body);
        // $this->assertContains("message", $res);
        $this->assertContains($expected, $json["message"]);
        // $this->assertArrayHasKey("message", $res);
    }

    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddressGet()
    {
        $ipAddress0 = "";
        $ipAddress1 = "192.96.76.1";
        $ipAddress2 = "145.38.5.6";
        $ipAddress3 = "/geo/forbidden";
        return [
            [$ipAddress0],
            [$ipAddress1],
            [$ipAddress2],
            [$ipAddress1, $ipAddress2],
            [$ipAddress3],
            // ["35.158.84.49"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }



    /**
     * Test the route "jsonActionGet()".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddressGet
     */
    public function testJsonActionGet(...$ipAddresses)
    {
        // $geolocator = new \Anna\Geolocator\GeoLocator();
        $geolocator = new GeoLocator();

        echo("\nIPADDRESSES in testJsonActionGet = ");
        var_dump($ipAddresses);     // array

        $expected = "ipType is set";
        // echo("\nIPADDRESSES in testJsonActionGet = ");
        // var_dump($ipAddresses);
        // $controller->initialize();
        // $this->geolocator->getGeoLocation($ipAddress, $config, $curl2);
        // $res = $this->controller->jsonActionGet($ipAddresses, $geolocator);
        $parametersArray = $ipAddresses;
        $parametersArray[] = $geolocator;

        // call_user_func_array(array($this->object,'method'), array($arg1, $arg2));
        $res = call_user_func_array(array($this->controller, "jsonActionGet"), $parametersArray);
        $this->assertInternalType("array", $res);
        // $body = $res->getBody();
        // $exp = "valid";
        $json = $res[0];
        // $this->assertContains($exp, $body);
        // $this->assertContains("message", $res);
        foreach ($json as $key => $result) {
            echo "\nresult inside testJsonActionGet() = ";
            var_dump($result);
            // $this->assertContains($exp, $json[$key]["message"]);
            // $result = json_decode($result);      ==> $result ÄR en array
            if (gettype($result) == "array") {
                // $this->assertArrayHasKey("message", $result);
                foreach ($result as $key => $innerResult) {
                    if (gettype($innerResult) == "array") {
                        $this->assertArrayHasKey("message", $innerResult);
                        $this->assertContains($expected, $innerResult["message"]);
                    }
                }
            } else {
                $this->assertArrayHasKey("message", $result);
                $this->assertContains($expected, $result["message"]);
            }
        }
    }


        /**
         * Test the route "jsonActionPost".
         *
         * @param string $ipAddress
         *
         * @return void
         *
         * @dataProvider providerIpAddressNotValid
         */
    public function testJsonActionGetNoValidIp($ipAddress, $expected)
    {
        //$geolocator = new \Anna\Geolocator\GeoLocator();
        $geolocator = new GeoLocator();


        $res = $this->controller->jsonActionGet($geolocator, $ipAddress);

        $json = $res[0];

        $this->assertContains($expected, $json[0]["message"]);
    }
}
