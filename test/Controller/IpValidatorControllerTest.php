<?php

namespace Anna\IpValidator;

use Anax\DI\DIFactoryConfig;
use \Anax\Response\Response;
use \Anna\Request\Request;
use \Anna\Session\Session2;

use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorControllerTest extends TestCase
{

    // private $request;
    // private $response;
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

        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anna\Request\Request();
        // $this->session = new \Anna\Session\Session2();

        $this->response = new Response();
        $this->request = new Request();
        $this->session = new Session2();

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
        //     $ipAddress = $this->getClientIpEnv();
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
        $this->mock = $this->getMockForTrait('\Anna\Commons\IpValidatorTrait');
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

     /**
      * Test
      *
      * @param string $ipAddress
      *
      * @return void
      *
      * @dataProvider providerIpAddress
      */
    public function testIndexAction($ipAddress)
    {
        echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

        // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
        if ($ipAddress && $this->mock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction($this->request);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $body = $res->getBody();
        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }


    /**
     * Test
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress
     */
    public function testIndexActionIpInSession($ipAddress)
    {
        $this->session->set("ipAddress", "137.45.67.200");

        echo "<br/>ipAddress in testIndexActionIpInSession() = " . $ipAddress;

        // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
        if ($ipAddress && $this->mock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction($this->request);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $body = $res->getBody();
        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }


    /**
     * Test
     *
     * @param string $ipAddress
     *
     * @return void
     */
    public function testIndexActionKillSession()
    {
        $this->session->set("someKey", "someValue");

        $this->request->setGet("destroy", true);

     //    echo "<br/>\$_GET = ";
     //    var_dump($_GET);              // En tom array!!

     //    print_r("\n\$_GET['destroy'] = ", false);
     //    var_dump($_GET["destroy"]);              // => Undefined index!!!


        print_r("\n\$this->request->getGet('destroy') = ", false);
        var_dump($this->request->getGet("destroy"));             // => boolean(true)

     //    function checkIfObjIsEmpty($obj) {
     //        foreach ( $obj as $prop ) {
     //            return false;
     //        }
     //        return true;
     //    }
        print_r("\nsession in testIndexActionKillSession() = ");
        var_dump($this->session);

     // Do the test and assert it
        ob_start();
        $this->controller->indexAction($this->request);
     // $res = $this->controller->indexAction($this->request->getGet("destroy"));
        $output = ob_get_contents();
        ob_end_clean();
     // $res = $controller->catchAll(...$args);
        $this->assertContains("Session has been killed", $output);


     //    echo "<br/>session in testIndexActionKillSession() = ";
     //    var_dump($this->session);
     // //    $this->assertInstanceOf("\Anax\Response\Response", $res);
     // //    $body = $res->getBody();
     //    $this->assertEmpty($this->session);   // Fungerar inte att kolla om sessionen är tom!
    }


   /**
    * Test
    *
    * @param string $ipAddress
    *
    * @return void
    *
    * @dataProvider providerIpAddress
    */
    public function testIndexActionNoRequest($ipAddress)
    {
        echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

        // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
        if ($ipAddress && $this->mock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $body = $res->getBody();
        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }
}
