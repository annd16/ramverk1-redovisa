<?php

namespace Anna\IpValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorController2Test extends TestCase
{

    private $request;
    private $response;
    // Create the di container.
    public $di;
    protected $controller;

    /**
     * Set up a request object
     *
     * @return void
     */
    public function setUp()
    {
        global $di;


   //      /**
   //  * Setup before each testcase
   //  */
   // public function setUp()
   // {
   //     $this->di = new DIFactoryConfig();
   //     $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
   //     $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");
   // }

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new IpValidatorController();
        $this->controller->setDI($this->di);

        $this->response = new \Anax\Response\Response();
        // $this->request = new \Anax\Request\Request();
        // $this->request = new \Anna\Request\RequestUnit();
        $this->request = new \Anna\Request\Request();
        // $this->session = new  \Anax\Session\Session();
        $this->session = new \Anna\Session\Session2();
        $this->request->setGlobals(
            [
                'server' => [
                    // ['HTTP_CLIENT_IP' => "127.45.6.7"],
                    // ['HTTP_X_FORWARDED_FOR' => "192.96.76.1"],
                    // ['HTTP_X_FORWARDED' => "145.38.5.6"],
                    // ['HTTP_FORWARDED_FOR' => "0.2.1.8"],
                    // ['HTTP_FORWARDED' => "fd12:3456:789a:1::1"],
                    // ['REMOTE_ADDR' => "fd12:3456:789a:1::1"],

                    // 'REQUEST_SCHEME' => "http",
                    // 'HTTPS'       => null, //"on",
                    // 'SERVER_NAME' => "localhost",
                    // 'SERVER_PORT' => "8081",
                    // 'REQUEST_URI' => '/dbwebb/ramverk1/me/redovisa/htdocs/ip/getmyip',
                    // 'SCRIPT_NAME' => '/dbwebb/ramverk1/me/redovisa/htdocs/index.php',
                    'REQUEST_METHOD' => "POST",
                    'HTTP_CLIENT_IP' => null,
                    'HTTP_X_FORWARDED_FOR' => null,
                    'HTTP_X_FORWARDED' => null,
                    'HTTP_FORWARDED_FOR' => null,
                    'HTTP_FORWARDED' => null,
                    'REMOTE_ADDR' => null,
                ],
                'post' => [
                    // 'ipAddress' => "145.38.5.6",
                    // 'timestamp' => 1544913716,
                    // 'web' => "Web",
                    'ipAddress' => null,
                    'timestamp' => null,
                    'web' => null,
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
    public function providerIpAddress2()
    {
        return [
        //    ["", "NOT a valid"],
        //    ["192.96.76.1", "valid Ipv4"],
        //    ["145.38.5.6", "valid Ipv4"],
           // ["35.158.84.49"],
           // ["23.34"],
           // ["35.158.84.49, 23.34"],
            ["145.38.5.6", 1544913716, "Web", "valid Ipv4"],
            ["128.33.2.1", 2002345678, "Web", "valid Ipv4"],
            ["::", 2002345678, "Web", "reserved"],
            // ["::ffff:0.0.0.0", 2002345678, "Web", "reserved"],
            ["145.38", 1544913716, "Web", "NOT a valid"],
        ];
    }

    /**
     * Test the route "webActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress2
     */
    public function testWebActionPost($ipAddress, $timestamp, $web, $expected)
    {
        // $di = $this->di;
        // $controller = new IpValidatorController();
        // $controller->initialize();
        $this->request->setPost("ipAddress", $ipAddress);
        $this->request->setPost("timestamp", $timestamp);
        $this->request->setPost("web", $web);


        // $request_method = $this->request->getServer("REQUEST_METHOD");
        // $res = $this->controller->webActionPost();

        // The request object must be provided to the route:
        $res = $this->controller->webActionPost($this->request);
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        // $res visar sig vara ett objekt av klassen
        // Anax\Response\ResponseUtility (men denna klass ärver ifrån Respinseklassen)!

        // echo "<br/>res in testWebActionPost = ";
        // var_dump($res);


        // echo "<br/>di->response in testWebActionPost = ";
        // var_dump($di->get("response"));
        // echo "<br/>this->session in testWebActionPost() = ";
        // var_dump($this->session);

        // echo "<br/>di->response->response in testWebActionPost = ";
        // var_dump($di->get("response"));

        // echo "<br/>di in testWebActionPost = ";
        // var_dump($di);
        $this->assertContains($expected, $this->session->get("flashmessage"));
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress25()
    {
        return [
         //    ["", "NOT a valid"],
         //    ["192.96.76.1", "valid Ipv4"],
         //    ["145.38.5.6", "valid Ipv4"],
            // ["35.158.84.49"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
             ["145.38.5.6", 1544913716, "Web", "NOT a valid"],
             ["128.33.2.1", 2002345678, "Web", "NOT a valid"],
            //  ["::", 2002345678, "Web", "reserved"],
            //  ["::ffff:0.0.0.0", 2002345678, "Web", "reserved"],
            //  ["145.38", 1544913716, "Web", "NOT a valid"],
        ];
    }


    /**
     * Test the route "webActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress25
     */
    public function testWebActionPostNoRequest($ipAddress, $timestamp, $web, $expected)
    {
        // $di = $this->di;
        // $controller = new IpValidatorController();
        // $controller->initialize();
        $this->request->setPost("ipAddress", $ipAddress);
        $this->request->setPost("timestamp", $timestamp);
        $this->request->setPost("web", $web);


        // $request_method = $this->request->getServer("REQUEST_METHOD");
        // $res = $this->controller->webActionPost();

        // The request object must be provided to the route:
        $res = $this->controller->webActionPost();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        // $res visar sig vara ett objekt av klassen
        // Anax\Response\ResponseUtility (men denna klass ärver ifrån Respinseklassen)!

        // echo "<br/>res in testWebActionPost = ";
        // var_dump($res);


        // echo "<br/>di->response in testWebActionPost = ";
        // var_dump($di->get("response"));
        // echo "<br/>this->session in testWebActionPost() = ";
        // var_dump($this->session);

        // echo "<br/>di->response->response in testWebActionPost = ";
        // var_dump($di->get("response"));

        // echo "<br/>di in testWebActionPost = ";
        // var_dump($di);

        // $body = $res->getBody();
        // $exp = "| ramverk1</title>";
        // $this->assertContains($exp, $body);
        $this->assertContains($expected, $this->session->get("flashmessage"));
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress3()
    {
        return [
            ['HTTP_CLIENT_IP', "127.45.6.7", "valid Ipv4"],
            ['HTTP_X_FORWARDED_FOR',  "192.96.76.1", "valid Ipv4"],
            ['HTTP_X_FORWARDED', "145.38.5.6", "valid Ipv4"],
            ['HTTP_FORWARDED_FOR', "0.2.1.8", "valid Ipv4"],
            ['HTTP_FORWARDED', "fd12:3456:789a:1::1", "valid Ipv6"],
            ['REMOTE_ADDR', "fd12:3456:789a:1::1", "valid Ipv6"],
            ['HTTP_CLIENT_IP', "127.45", "is NOT a valid"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }


    /**
     * Test the route "testGetMyIpActionPost"
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress3
     */
    public function testGetMyIpActionPost($key, $ipAddress, $expected)
    {
        // $di = $this->di;
        // $controller = new IpValidatorController();
        // $controller->initialize();

        // Set the current key-value combination:
        $this->request->setServer($key, $ipAddress);
        // $this->request->setPost("getmyip", "GetMyIp");

        print_r("\ngetServer($key) = ", false);
        echo $this->request->getServer($key);        // Visar korrekt

        print_r("\ngetServer('REQUEST_METHOD') = ", false);
        echo($this->request->getServer("REQUEST_METHOD"));
        // $res = $this->controller->getMyIpActionPost($this->di->get("request"));
        // $res = $this->controller->getMyIpActionPost($this->request);

        // The request object must be provided to the route:
        $res = $this->controller->getMyIpActionPost($this->request);
        // $res = $this->controller->getMyIpActionPost();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        // echo "<br/>\$res in testGetMyIpActionPost = ";
        // var_dump($res);


        // echo "<br/>this->session in testGetMyIpActionPost() = ";
        // var_dump($this->session);

        // echo "<br/>di->response->response in testWebActionPost = ";
        // var_dump($di->get("response"));

        // echo "<br/>di in testWebActionPost = ";
        // var_dump($di);

        // $body = $res->getBody();
        // $exp = "| ramverk1</title>";
        // $this->assertContains($exp, $body);
        $this->assertContains($expected, $this->session->get("flashmessage"));



        // $expected = $ipAddress;
        //
        // echo "<br/>result = ";
        // // var_dump($dataProvider);
        // var_dump($result);
        // // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
        // $this->assertEquals($expected, $result);

        // Reset the current key-value to null
        $this->request->setServer($key, null);
    }

    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress4()
    {
        return [
            ['HTTP_CLIENT_IP', "127.45.6.7", "a valid Ipv4"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }


    /**
     * Test the route "testGetMyIpActionPost"
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress4
     */
    public function testGetMyIpActionPostNoRequest($key, $ipAddress, $expected)
    {
        // $di = $this->di;
        // $controller = new IpValidatorController();
        // $controller->initialize();

        // Set the current key-value combination:
        $this->request->setServer($key, $ipAddress);
        // $this->request->setPost("getmyip", "GetMyIp");

        print_r("\ngetServer($key) = ", false);
        echo $this->request->getServer($key);        // Visar korrekt

        print_r("\ngetServer('REQUEST_METHOD') = ", false);
        echo($this->request->getServer("REQUEST_METHOD"));
        // $res = $this->controller->getMyIpActionPost($this->di->get("request"));
        // $res = $this->controller->getMyIpActionPost($this->request);

        // The request object must be provided to the route:
        $res = $this->controller->getMyIpActionPost();
        // $res = $this->controller->getMyIpActionPost();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        // echo "<br/>\$res in testGetMyIpActionPost = ";
        // var_dump($res);

        $expected = "NOT a valid";


        // echo "<br/>this->session in testGetMyIpActionPost() = ";
        // var_dump($this->session);

        // echo "<br/>di->response->response in testWebActionPost = ";
        // var_dump($di->get("response"));

        // echo "<br/>di in testWebActionPost = ";
        // var_dump($di);

        // $body = $res->getBody();
        // $exp = "| ramverk1</title>";
        // $this->assertContains($exp, $body);
        $this->assertContains($expected, $this->session->get("flashmessage"));



        // $expected = $ipAddress;
        //
        // echo "<br/>result = ";
        // // var_dump($dataProvider);
        // var_dump($result);
        // // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
        // $this->assertEquals($expected, $result);

        // Reset the current key-value to null
        $this->request->setServer($key, null);
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress5()
    {
        return [
            ['/ip/knorr'],
            // ['HTTP_X_FORWARDED_FOR',  "192.96.76.1", "valid Ipv4"],
            // ['HTTP_X_FORWARDED', "145.38.5.6", "valid Ipv4"],
            // ['HTTP_FORWARDED_FOR', "0.2.1.8", "valid Ipv4"],
            // ['HTTP_FORWARDED', "fd12:3456:789a:1::1", "valid Ipv4"],
            // ['REMOTE_ADDR', "fd12:3456:789a:1::1", "valid Ipv6"],
            // ['HTTP_CLIENT_IP', "127.45.6.7", "valid Ipv4"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }


     /**
       * Test the route catchAll().
      *
      * @param string $ipAddress
      *
      * @return void
      *
      * @dataProvider providerIpAddress5
      */
    public function testCatchAll($route)
    {
        // Do the test and assert it
        ob_start();
        $this->controller->catchAll($route);
        $output = ob_get_contents();
        ob_end_clean();
        // $res = $controller->catchAll(...$args);
        $this->assertContains("catchAll", $output);
        // $this->assertContains("configuration", $res);
        // $this->assertContains("request", $res);
        // $this->assertContains("response", $res);
    }
}
