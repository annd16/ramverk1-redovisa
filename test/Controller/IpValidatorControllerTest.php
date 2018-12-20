<?php

namespace Anna\IpValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorControllerTest extends TestCase
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
        $this->controller = new IpValidatorController();
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
                'server' => [
                    // ['HTTP_CLIENT_IP' => "127.45.6.7"],
                    // ['HTTP_X_FORWARDED_FOR' => "192.96.76.1"],
                    // ['HTTP_X_FORWARDED' => "145.38.5.6"],
                    // ['HTTP_FORWARDED_FOR' => "0.2.1.8"],
                    // ['HTTP_FORWARDED' => "fd12:3456:789a:1::1"],
                    // ['REMOTE_ADDR' => "fd12:3456:789a:1::1"],

                    'HTTP_CLIENT_IP' => "34.56.67.8",
                    'HTTP_X_FORWARDED_FOR' => "268.67.45.3",
                    'HTTP_X_FORWARDED' => null,
                    'HTTP_FORWARDED_FOR' => null,
                    'HTTP_FORWARDED' => null,
                    'REMOTE_ADDR' => null,
                ],
                'post' => [
                    'ipAddress' => "145.38.5.6",
                    'timestamp' => 1544913716,
                    'web' => "Web",
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
        // $defaults = [];
        // if (!$this->session->has('ipAddress')) {
        //     // Get the IP adress from the requesterer, if available
        //     // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpEnv();
        //     $ipAddress = "127.0.0.1";
        //
        // } else {
        //     $ipAddress = $this->session->get("ipAddress");
        // }
        echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

        // $active = $session->get(self::$key, null);

        if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        // $controller = new IpValidatorController();
        // $controller->initialize();
        $defaults["ipAddress"] = "127.0.0.1";
        $res = $this->controller->indexAction();
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
       // $defaults = [];
       // if (!$this->session->has('ipAddress')) {
       //     // Get the IP adress from the requesterer, if available
       //     // $ipAddress = \Anna\IpValidator\IpValidator::getClientIpEnv();
       //     $ipAddress = "127.0.0.1";
       //
       // } else {
       //     $ipAddress = $this->session->get("ipAddress");
       // }
    //    echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

       // $active = $session->get(self::$key, null);

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
    echo "<br/>session in testIndexActionKillSession() = ";
    var_dump($this->session);
    //    if ($ipAddress && \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress)) {
    //        echo "<br/>the pre-filled IP-address is valid!";
    //        $defaults["ipAddress"] = $ipAddress;
    //        echo "<br/>defaults = ";
    //        var_dump($defaults);
    //    }
    //    // $controller = new IpValidatorController();
    //    // $controller->initialize();
    //    $defaults["ipAddress"] = "127.0.0.1";

       $this->response->redirect("?destroy=true");
    //    $res = $this->controller->indexAction();

       echo "<br/>session in testIndexActionKillSession() = ";
       var_dump($this->session);
    //    $this->assertInstanceOf("\Anax\Response\Response", $res);
    //    $body = $res->getBody();
       $this->assertEmpty( $this->session);
   }

    /**
     * Test the route "webActionPost".
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerIpAddress
     */
    public function testWebActionPost()
    {
        $di = $this->di;
        // $controller = new IpValidatorController();
        // $controller->initialize();
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
        $this->assertContains("is", $this->session->get("flashmessage"));
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
            ['HTTP_FORWARDED', "fd12:3456:789a:1::1", "valid Ipv4"],
            ['REMOTE_ADDR', "fd12:3456:789a:1::1", "valid Ipv6"],
            ['HTTP_CLIENT_IP', "127.45.6.7", "valid Ipv4"],
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

        print_r("\ngetServer($key) = ", false);
        echo $this->request->getServer($key);        // Visar korrekt


        $res = $this->controller->getMyIpActionPost($this->di->get("request"));
        // $res = $this->controller->getMyIpActionPost();
        $this->assertInstanceOf("\Anax\Response\Response", $res);

        // echo "<br/>di->response in testWebActionPost = ";
        // var_dump($di->get("response"));


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
