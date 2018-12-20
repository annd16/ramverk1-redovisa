<?php

namespace Anna\IpValidatorJson;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorControllerJsonTest extends TestCase
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
        $this->controller = new IpValidatorJsonController();
        $this->controller->setDI($this->di);

        $this->response = new \Anax\Response\Response();
        $this->request = new \Anax\Request\Request();
        $this->session = new  \Anax\Session\Session();
        $this->request->setGlobals(
            [
                // 'server' => [
                //     'REQUEST_SCHEME' => "http",
                //     'HTTPS'       => null, //"on",
                //     'SERVER_NAME' => "dbwebb.se",
                //     'SERVER_PORT' => "80",
                //     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
                //     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
                // ]
                // 'env' => [
                //     ['HTTP_CLIENT_IP' => "127.45.6.7"],
                //     ['HTTP_X_FORWARDED_FOR' => "192.96.76.1"],
                //     ['HTTP_X_FORWARDED' => "145.38.5.6"],
                //     ['HTTP_FORWARDED_FOR' => "0.2.1.8"],
                //     ['HTTP_FORWARDED' => "fd12:3456:789a:1::1"],
                //     ['REMOTE_ADDR' => "fd12:3456:789a:1::1"],
                // ],
                'post' => [
                    'ipAddress' => "145.38.5.6",
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

        // $this->defaults = [];
        // if (!$this->session->has('ipAddress')) {
        //     // Get the IP adress from the requesterer, if available
        //     $ipAddress = \Anna\IpValidator\IpValidator::getClientIpEnv();
        //
        // } else {
        //     $ipAddress = $this->session->get("ipAddress");
        // }
        // echo "<br/>ipAddress = " . $ipAddress;
        //
        // // $active = $session->get(self::$key, null);
        //
        // if ($ipAddress &&  \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
        //     echo "<br/>the pre-filled IP-address is valid!";
        //     $this->defaults["ipAddress"] = $ipAddress;
        //     echo "<br/>this->defaults = ";
        //     var_dump($this->defaults);
        // }
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress()
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

    // /**
    //  * Test the route "index".
    //  */
    // public function testIndexAction()
    // {
    //     $controller = new IpValidatorController();
    //     $controller->initialize();
    //     $res = $controller->indexAction();
    //     $this->assertContains("is a valid IPv4", $res);
    // }


    // /**
    //  * Test the route "WebActionPost".
    //  */
    // public function testIndexAction()
    // {
    //     $defaults = [];
    //     if (!$this->session->has('ipAddress')) {
    //         // Get the IP adress from the requesterer, if available
    //         // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpEnv();
    //         $ipAddress = "127.0.0.1";
    //
    //     } else {
    //         $ipAddress = $this->session->get("ipAddress");
    //     }
    //     echo "<br/>ipAddress = " . $ipAddress;
    //
    //     // $active = $session->get(self::$key, null);
    //
    //     if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
    //         echo "<br/>the pre-filled IP-address is valid!";
    //         $defaults["ipAddress"] = $ipAddress;
    //         echo "<br/>defaults = ";
    //         var_dump($defaults);
    //     }
    //     // $controller = new IpValidatorController();
    //     // $controller->initialize();
    //     $defaults["ipAddress"] = "127.0.0.1";
    //     $res = $this->controller->indexAction();
    //     $this->assertInstanceOf("\Anax\Response\Response", $res);
    //     $body = $res->getBody();
    //     $exp = "| ramverk1</title>";
    //     $this->assertContains($exp, $body);
    // }

    /**
     * Test the route "jsonActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress
     */
    public function testJsonActionPost($ipAddress)
    {
        // $controller = new IpValidatorController();
        // $controller->initialize();
        $res = $this->controller->jsonActionPost();
        $this->assertInternalType("array", $res);
        // $body = $res->getBody();
        $exp = "valid";
        $json = $res[0];
        // $this->assertContains($exp, $body);
        // $this->assertContains("message", $res);
        $this->assertContains($exp, $json["message"]);
        // $this->assertArrayHasKey("message", $res);
    }



    /**
     * Test the route "jsonActionGet()".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress
     */
    public function testJsonActionGet($ipAddress)
    {
        // $controller = new IpValidatorController();
        // $controller->initialize();
        $res = $this->controller->jsonActionGet($ipAddress);
        $this->assertInternalType("array", $res);
        // $body = $res->getBody();
        $exp = "valid";
        $json = $res[0];
        // $this->assertContains($exp, $body);
        // $this->assertContains("message", $res);
        foreach ($json as $key => $result) {
            // $this->assertContains($exp, $json[$key]["message"]);
            // $result = json_decode($result);      ==> $result ÄR en array
            echo "result inside testJsonActionGet() = ";
            var_dump($result);
            $this->assertArrayHasKey("message", $result);
            // $this->assertContains($exp, $json[$key]);
        }
        // $this->assertArrayHasKey("message", $res);
    }


    /**
     * Test the route "forbidden".
     */
    public function testForbiddenAction()
    {
        $res = $this->controller->forbiddenAction();
        $this->assertInternalType("array", $res);

        $json = $res[0];
        $status = $res[1];

        $exp = "forbidden to access";
        $this->assertContains($exp, $json["message"]);
        $this->assertEquals(403, $status);
    }



//     /**
//      * Test the route "dump-di".
//      */
//     public function testDumpDiActionGet()
//     {
//         // Setup di
//         $di = new DIFactoryConfig();
//         $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
//
//         // Setup the controller
//         $controller = new IpValidatorController();
//         $controller->setDI($di);
//         $controller->initialize();
//
//         // Do the test and assert it
//         $res = $controller->dumpDiActionGet();
//         $this->assertContains("di contains", $res);
//         $this->assertContains("configuration", $res);
//         $this->assertContains("request", $res);
//         $this->assertContains("response", $res);
//     }
}
