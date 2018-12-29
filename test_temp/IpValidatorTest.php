<?php

namespace Anna\IpValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorTest extends TestCase
{

    private $request;
    private $response;
    // Create the di container.
    protected $di;
    // protected $controller;
    protected $validator;

    /**
     * Set up a request object
     *
     * @return void
     */
    public function setUp()
    {
        // global $di;
        //
        // // Setup di
        // $this->di = new DIFactoryConfig();
        // $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        //
        // // View helpers uses the global $di so it needs its value
        // $di = $this->di;

        // Setup the class
        // $this->controller = new IpValidatorController();
        // $this->validator = new IpValidator();
        // // $this->controller->setDI($this->di);
        // $this->validator->setDI($this->di);


        $this->response = new \Anax\Response\Response();
        // $this->request = new \Anax\Request\Request();
        $this->request = new \Anna\Request\Request();
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
                // 'server' => [
                //     'HTTP_CLIENT_IP' => "127.45.6.7",
                //     'HTTP_X_FORWARDED_FOR' => "192.96.76.1",
                //     'HTTP_X_FORWARDED' => "145.38.5.6",
                //     'HTTP_FORWARDED_FOR' => "0.2.1.8",
                //     'HTTP_FORWARDED' => "fd12:3456:789a:1::1",
                //     'REMOTE_ADDR' => "fd12:3456:789a:1::1",
                // ]

                'server' => [

                    'HTTP_CLIENT_IP' => null,
                    'HTTP_X_FORWARDED_FOR' => null,
                    'HTTP_X_FORWARDED' => null,
                    'HTTP_FORWARDED_FOR' => null,
                    'HTTP_FORWARDED' => null,
                    'REMOTE_ADDR' => null,
                ]
            ]
        );
    }



    // /**
    //  * Set up a request object
    //  *
    //  * @return void
    //  */
    // public function setUp()
    // {
    //     global $di;
    //
    //     // Setup di
    //     $this->di = new DIFactoryConfig();
    //     $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
    //
    //     // View helpers uses the global $di so it needs its value
    //     $di = $this->di;
    //
    //     // Setup the controller
    //     $this->controller = new IpValidatorJsonController();
    //     $this->controller->setDI($this->di);
    //
    //     $this->response = new \Anax\Response\Response();
    //     $this->request = new \Anax\Request\Request();
    //     $this->session = new  \Anax\Session\Session();
    //     $this->request->setGlobals(
    //         [
    //             // 'server' => [
    //             //     'REQUEST_SCHEME' => "http",
    //             //     'HTTPS'       => null, //"on",
    //             //     'SERVER_NAME' => "dbwebb.se",
    //             //     'SERVER_PORT' => "80",
    //             //     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
    //             //     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
    //             // ]
    //             'env' => [
    //                 ['HTTP_CLIENT_IP' => "127.45.6.7"],
    //                 ['HTTP_X_FORWARDED_FOR' => "192.96.76.1"],
    //                 ['HTTP_X_FORWARDED' => "145.38.5.6"],
    //                 ['HTTP_FORWARDED_FOR' => "0.2.1.8"],
    //                 ['HTTP_FORWARDED' => "fd12:3456:789a:1::1"],
    //                 ['REMOTE_ADDR' => "fd12:3456:789a:1::1"],
    //             ]
    //             'post' => [
    //                 'ipAddress' => "145.38.5.6",
    //                 'timestamp' => 1544913716,
    //                 'json' => "Json",
    //             ],
    //             'get' => [
    //                 'ipAddress' => "145.38.5.6",
    //                 'timestamp' => 1544913716,
    //                 'json' => "Json",
    //             ]
    //         ]
    //     );


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress1()
    {
        // return [
        //     [""],
        //     ["192.96.76.1"],
        //     ["145.38.5.6"],
        //     // ["35.158.84.49"],
        //     // ["23.34"],
        //     // ["35.158.84.49, 23.34"],
        // ];
        return [
            [false, ""],
            ["Ipv4", "192.96.76.1"],
            ["Ipv4", "145.38.5.6"],
            ["Ipv6", "fd12:3456:789a:1::1"],
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
      * @dataProvider providerIpAddress1
      */
    public function testCheckIfValidIp($expected, $ipAddress)
    {
        echo "<br/>ipAddress in testCheckIfvalidIp() = ";
        var_dump($ipAddress);

        echo "<br/>expected in testCheckIfvalidIp() = ";
        var_dump($expected);

        // echo "<br/>dataProvider = ";
        // // var_dump($dataProvider);
        // var_dump($this->providerIpAddress1());

        //
        // echo "<br/>dataDescription() = ";
        // var_dump($this->dataDescription("dataName"));
        $result =  \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);


        echo "<br/>result = ";
        // var_dump($dataProvider);
        var_dump($result);
        // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
        $this->assertEquals($expected, $result);
        // }
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress2()
    {
        // return [
        //     [""],
        //     ["192.96.76.1"],
        //     ["145.38.5.6"],
        //     // ["35.158.84.49"],
        //     // ["23.34"],
        //     // ["35.158.84.49, 23.34"],
        // ];
        return [
            [false, ""],
            [false, "192.96.76.1"],
            [false, "145.38.5.6"],
            ["reserved", "0.2.1.8"],
            ["private", "fd12:3456:789a:1::1"],
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
     * @dataProvider providerIpAddress2
     */
    public function testCheckIfAdressIsPrivOrRes($expected, $ipAddress)
    {
        echo "<br/>ipAddress in testCheckIfAdressIsPrivOrRes() = ";
        var_dump($ipAddress);

        echo "<br/>expected in testCheckIfAdressIsPrivOrRes() = ";
        var_dump($expected);

    //    echo "<br/>dataProvider = ";
    //    // var_dump($dataProvider);
    //    var_dump($this->providerIpAddress2());


    //    echo "<br/>dataDescription() = ";
    //    var_dump($this->dataDescription("dataName"));
        $result =  \Anna\IpValidator\IpValidator::checkIfAdressIsPrivOrRes($ipAddress);


        echo "<br/>result = ";
        // var_dump($dataProvider);
        var_dump($result);
        // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
        $this->assertEquals($expected, $result);
       // }
    }

   /**
    * Provider for the Ip-addresses
    *
    * @return array
    */
    public function providerIpAddress3()
    {
        return [
            ['HTTP_CLIENT_IP', "127.45.6.7"],
            ['HTTP_X_FORWARDED_FOR',  "192.96.76.1"],
            ['HTTP_X_FORWARDED', "145.38.5.6"],
            ['HTTP_FORWARDED_FOR', "0.2.1.8"],
            ['HTTP_FORWARDED', "fd12:3456:789a:1::1"],
            ['REMOTE_ADDR', "fd12:3456:789a:1::1"],
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
    * @dataProvider providerIpAddress3
    */
    public function testGetClientIpServer($key, $ipAddress)
    {


        // Set the current key-value combination:
        $this->request->setServer($key, $ipAddress);

        echo "<br/>ipAddress in testgetClientIpServer() = ";
        var_dump($ipAddress);

      //   echo "<br/>dataProvider = ";
      //   // var_dump($dataProvider);
      //   var_dump($this->providerIpAddress3());

        echo "<br/>\$this->request->getServer('HTTP_CLIENT_IP') = ";
        echo $this->request->getServer('HTTP_CLIENT_IP');

        echo "<br/>\$this->request->getServer('HTTP_X_FORWARDED_FOR') = ";
        echo $this->request->getServer('HTTP_X_FORWARDED_FOR');


      //   echo "<br/>\$_Server = ";
      //   var_dump($_SERVER);
        //
        // echo "<br/>dataDescription() = ";
        // var_dump($this->dataDescription("dataName"));
        $result =  \Anna\IpValidator\IpValidator::getClientIpServer($this->request);

        $expected = $ipAddress;

        echo "<br/>result = ";
        // var_dump($dataProvider);
        var_dump($result);
        // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
        $this->assertEquals($expected, $result);

        // Reset the current key-value to null
        $this->request->setServer($key, null);
        // }
    }

    // /**
    //  * Test the route "webActionPost".
    //  *
    //  * @param string $ipAddress
    //  *
    //  * @return void
    //  *
    //  * @dataProvider providerIpAddress
    //  */
    // public function testCheckIfAdressIsPrivOrRes($ipAddress)
    //     $di = $this->di;
    //     // $controller = new IpValidatorController();
    //     // $controller->initialize();
    //     $res = $this->controller->webActionPost();
    //     $this->assertInstanceOf("\Anax\Response\Response", $res);
    //
    //     // $res visar sig vara ett objekt av klassen
    //     // Anax\Response\ResponseUtility (men denna klass ärver ifrån Respinseklassen)!
    //
    //     echo "<br/>res in testWebActionPost = ";
    //     var_dump($res);
    //
    //
    //     echo "<br/>di->response in testWebActionPost = ";
    //     var_dump($di->get("response"));
    //
    //
    //     echo "<br/>this->session = ";
    //     var_dump($this->session);
    //
    //     // echo "<br/>di->response->response in testWebActionPost = ";
    //     // var_dump($di->get("response"));
    //
    //     // echo "<br/>di in testWebActionPost = ";
    //     // var_dump($di);
    //
    //     // $body = $res->getBody();
    //     // $exp = "| ramverk1</title>";
    //     // $this->assertContains($exp, $body);
    //     $this->assertContains("is", $this->session->get("flashmessage"));
    // }



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
