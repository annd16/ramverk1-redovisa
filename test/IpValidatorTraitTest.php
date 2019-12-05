<?php

namespace Anna\Commons;

use Anax\DI\DIFactoryConfig;
use \Anax\Response\Response;
use \Anna\Request\Request;
use \Anna\Session\Session2;

use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class IpValidatorTraitTest extends TestCase
{

    private $request;
    private $response;
    // Create the di container.
    protected $di;
    // protected $controller;
    protected $validator;

    private $mock;

    /**
     * Set up a ipvalidator object
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


        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anna\Request\Request();
        // $this->session = new  \Anna\Session\Session2();

        $this->response = new Response();
        $this->request = new Request();
        $this->session = new Session2();

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
        $this->mock = $this->getMockForTrait('\Anna\Commons\IpValidatorTrait');
    }


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


        // echo "<br/>dataDescription() = ";
        // var_dump($this->dataDescription("dataName"));

        // $result =  $this->checkIfValidIp($ipAddress);
        $result =  $this->mock->checkIfValidIp($ipAddress);


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

        // $result =  $this->checkIfAdressIsPrivOrRes($ipAddress);
        $result =  $this->mock->checkIfAdressIsPrivOrRes($ipAddress);


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

        // $result =  $this->getClientIpServer($this->request);
        $result =  $this->mock->getClientIpServer($this->request);

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
}
