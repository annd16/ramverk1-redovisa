<?php

namespace Anna\Curl;

use Anax\DI\DIFactoryConfig;
use \Anna\GeoLocator\Geolocator;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Curl2.
 */
class Curl2Test extends TestCase
{
    protected $di;
    // protected $form;
    // protected $submitValues;

    protected $curl = null;

    /**
     * Set up a cUrl resource/handle
     *
     * @return void
     */
    public function setUp()
    {
        global $di;

        // Setup di
        //$this->di = new \Anax\DI\DIFactoryConfig();
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $this->di->loadServices([
        // "services" => [
        //     "request" => [
        //         "shared" => true,
        //         "callback" => function () {
        //             $obj = new \Anax\Response\MockRequest();
        //             return $obj;
        //         }
        //     ],
        //     "url" => [
        //         "shared" => true,
        //         "callback" => function () {
        //             $obj = new \Anax\Response\MockUrl();
        //             return $obj;
        //         }
        //     ],
        // ],
    // ]);
        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        $this->curlObj = new Curl2();

        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anax\Request\Request();
        // $this->curl = new \Anna\Curl\Curl2();

        // A test mock is an object that is capable of controlling both indirect input and output,
        // and it has a mechanism for automatic assertion on expectations and results.
        // $curlStub = $this->getMock('CurlInterface');
        // $curlStub = $this->createMock('Anna\Commons\CurlInterface');
        // getMock() has been replaced with createMock()

        // // Configure the stub.
        // $curlStub->method('exec')
        //      ->willReturn('foo');

        // $map = [
        //     ['a', 'b', 'c', 'd'],
        //     ['e', 'f', 'g', 'h']
        // ];

        // $curl = null;
        // $curlStub->method('exec')
        //      ->willReturn($curl);

        // Configure the stub.
        // $stub->method('exec')
        //      ->will($this->returnValueMap($map));
        // // $formVars = [
        //     'ipAddress' => '::1',
        //     'timestamp' => 1545168040,
        //     '' => null,
        //     'else' => '',
        // $this->assertEquals($curl, $curlStub->method('exec'));
    }





    // public function testStubOfCurl2Class()
    // {
    //         // Create a stub for the Curl2 class.
    //         $stub = $this->createMock(\Anna\Curl\Curl2::class);
    //
    //         // Configure the stub.
    //         $stub->method('getInfo')
    //              ->willReturn('foo');
    //
    //         // Calling $stub->doSomething() will now return
    //         // 'foo'.
    //         $this->assertSame('foo', $stub->getInfo());
    // }
    //
    //
    // public function testStubOfCurlInterfaceInterface()
    // {
    //         // Create a stub for the CurlInterface inteface.
    //         $stub = $this->createMock(\Anna\Commons\CurlInterface::class);
    //
    //         // Configure the stub.
    //         $stub->method('getInfo')
    //              ->willReturn('foo');
    //
    //         // Calling $stub->doSomething() will now return
    //         // 'foo'.
    //         $this->assertSame('foo', $stub->getInfo());
    // }


         /**
          * CurlInterface::init()
          *
          * Check if valid IP.
          *
          * @param string $ipAddress - the IP address to check
          *
          * @return mixed - the IP-version as a string if valid, or false otherwise.
          */
    public function testInit()
    {
           //  // Create a stub for the SomeClass class.
           //  $stub = $this->createMock(\Anna\Commons\CurlInterface::class);
           // //  $stub = $this->createMock(\Anna\Curl\Curl2::class);
           //
           //  $chandle = $stub->init();
           //
           //  $stubType = getType($chandle);
           //
           //  $this->assertSame('resource', $stubType);


            // Create a stub for the SomeClass class.
           //  $curl = $this->createMock(\Anna\Commons\CurlInterface::class);
           //  $stub = $this->createMock(\Anna\Curl\Curl2::class);

            $chandle = $this->curlObj->init();

            $type = getType($chandle);

            $this->assertSame('resource', $type);
    }


             /**
              * Provider for the Ip-addresses
              *
              * @return array
              */
    public function providerSetOptions()
    {
        // return [[[
        //     CURLOPT_RETURNTRANSFER => 1,
        //     // CURLOPT_URL
        //     // The URL to fetch. This can also be set when initializing a session with curl_init().
        //     // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
        //     CURLOPT_URL => $url,
        //     // The contents of the "User-Agent: " header to be used in a HTTP request.
        //     // The User-Agent request header contains a characteristic string that allows
        //     // the network protocol peers to identify the application type, operating system,
        //     // software vendor or software version of the requesting software user agent.
        //     // Syntax: User-Agent: <product> / <product-version> <comment>
        //     CURLOPT_USERAGENT => 'User Agent X'
        // ], "145.38.5.6"]];
        $urls = ["145.38.5.6"];
        return [[
            CURLOPT_RETURNTRANSFER => 1,
            // CURLOPT_URL
            // The URL to fetch. This can also be set when initializing a session with curl_init().
            // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
            CURLOPT_URL => $urls[0],
            // The contents of the "User-Agent: " header to be used in a HTTP request.
            // The User-Agent request header contains a characteristic string that allows
            // the network protocol peers to identify the application type, operating system,
            // software vendor or software version of the requesting software user agent.
            // Syntax: User-Agent: <product> / <product-version> <comment>
            CURLOPT_USERAGENT => 'User Agent X'
        ]];
    }


         /**
          * CurlInterface::init()
          *
          * Check if valid IP.
          *
          * @param string $ipAddress - the IP address to check
          *
          * @return mixed - the IP-version as a string if valid, or false otherwise.
          *
          * @dataProvider providerSetOptions
          */
         // public function setOptionsArray($curl, $array, $url)
    public function testSetOptionsArray($array)
    {
        $stub = $this->createMock(\Anna\Commons\CurlInterface::class);
       //  $stub->setMethods(["setOptionsArray"]);
        $stub->method("setOptionsArray")->will($this->returnArgument(1));

        // $array = array(
        //   // CURLOPT_RETURNTRANSFER
        //   // TRUE (or 1) to return the transfer as a string of the return value of curl_exec()
        //   // instead of outputting it directly.
        //   CURLOPT_RETURNTRANSFER => 1,
        //   // CURLOPT_URL
        //   // The URL to fetch. This can also be set when initializing a session with curl_init().
        //   // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
        //   CURLOPT_URL => $url,
        //   // The contents of the "User-Agent: " header to be used in a HTTP request.
        //   // The User-Agent request header contains a characteristic string that allows
        //   // the network protocol peers to identify the application type, operating system,
        //   // software vendor or software version of the requesting software user agent.
        //   // Syntax: User-Agent: <product> / <product-version> <comment>
        //   CURLOPT_USERAGENT => 'User Agent X'
        // );

        $this->assertSame('myUrl', $stub->setOptionsArray($array, "myUrl"));
    }


        /**
         * Curl2Test::testGetInfo()
         *
         * Test getInfo().
         *
         * @return mixed - the IP-version as a string if valid, or false otherwise.
         */
    public function testGetInfo()
    {
           $this->curl = $this->curlObj->init();

            $info = $this->curlObj->getinfo();

            $this->assertArrayHasKey("url", $info);
    }



             /**
              * Curl2Test::testExec()
              *
              * Check if valid IP.
              *
              * @param string $ipAddress - the IP address to check
              *
              * @return mixed - the IP-version as a string if valid, or false otherwise.
              */
    public function testExec()
    {
           //  // Create a stub for the SomeClass class.
            $stub = $this->createMock(\Anna\Commons\CurlInterface::class);
           // //  $stub = $this->createMock(\Anna\Curl\Curl2::class);
            $url = "";
            // $chandle = $stub->init();

            $stub->init();

            $ipAddress = "145.38.5.6";
            $config['accessKeyGeo'] = "12345";
            $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";
            // Set multiple options for a cURL transfer - we are passing in a useragent too here
            $array = array(
               // CURLOPT_RETURNTRANSFER
               // TRUE (or 1) to return the transfer as a string of the return value of curl_exec()
               // instead of outputting it directly.
               CURLOPT_RETURNTRANSFER => 1,
               // CURLOPT_URL
               // The URL to fetch. This can also be set when initializing a session with curl_init().
               // CURLOPT_URL => "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=main&hostname=1",
               CURLOPT_URL => $url,
               // The contents of the "User-Agent: " header to be used in a HTTP request.
               // The User-Agent request header contains a characteristic string that allows
               // the network protocol peers to identify the application type, operating system,
               // software vendor or software version of the requesting software user agent.
               // Syntax: User-Agent: <product> / <product-version> <comment>
               CURLOPT_USERAGENT => 'User Agent X'
            );


            // $stub->setOptionsArray($array, $url);
            $stub->setOptionsArray($array);

            $response = null;
           //  $stub->setMethods(["exec"]);
            $stub->method("exec")->will($this->returnValue($response));

           //  $stubType = getType($chandle);
           //
           //  $this->assertSame('resource', $stubType);


            // Create a stub for the SomeClass class.
           //  $curl = $this->createMock(\Anna\Commons\CurlInterface::class);
           //  $stub = $this->createMock(\Anna\Curl\Curl2::class);


            $this->assertSame($response, $stub->exec());
    }


         /**
          * Provider for the Ip-addresses
          *
          * @return array
          */
    public function providerIpAddress()
    {
        return [
            ["145.38.5.6"],
            ["128.33.2.1"],
        ];
    }


           /**
            * Test the method curlAnUrl().
            *
            * @param string $url
            *
            * @return void
            *
            * @dataProvider providerIpAddress
            */
    public function testClose()
    {
        $chandle = $this->curlObj->init();
      //   $this->assertNotNull($chandle);
        $type = getType($chandle);
        $this->assertSame('resource', $type);
        $this->curlObj->close();
      //   $this->assertNull($chandle);
        $type = getType($chandle);
        $this->assertNotSame('resource', $type);
    }

          /**
           * Provider for the Ip-addresses
           *
           * @return array
           */
    public function providerIpAddress2()
    {
        return [
            ["145.38.5.6"],
        ];
    }

     /**
      * Test the method curlAnUrl().
      *
      * @param string $url
      *
      * @return void
      *
      * @dataProvider providerIpAddress2
      */
    public function testCurlAnUrl($url)
    {

        $stub = $this->createMock(\Anna\Curl\Curl2::class);

        $ipAddress = "145.38.5.6";
        $config['accessKeyGeo'] = "12345";
        $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";



        $response = '{"ip":"145.38.5.6","hostname":"145.38.5.6","type":"ipv4","continent_code":"EU","continent_name":"Europe","country_code":"NL","country_name":"Netherlands","region_code":null,"region_name":null,"city":null,"zip":null,"latitude":52.3824,"longitude":4.8995,"location":{"country_flag":"http:\/\/assets.ipstack.com\/flags\/nl.svg","country_flag_emoji":"\ud83c\uddf3\ud83c\uddf1"}}';
       //  $stub->setMethods(["exec"]);
        // $stub->method("exec")->will($this->returnValue($response));

        $stub->method("curlAnUrl")->with($url)->will($this->returnValue($response));

        $this->assertSame($response, $stub->curlAnUrl($url));
        // $this->assertSame($response, $stub->curlAnUrl("145.38.3.6"));
    }


//     public function testGetThrowsWhenContentTypeIsNotJson() {
//     $http = $this->getMock('HttpRequest');
//     $http->expects($this->any())
//          ->method('getInfo')
//          ->will($this->returnValue('not JSON'));
//     $this->setExpectedException('HttpResponseException');
//     // create class under test using $http instead of a real CurlRequest
//     $fixture = new ClassUnderTest($http);
//     $fixture->get();
// }


// public function testGetThrowsWhenContentTypeIsNotJson() {
//     $http = $this->createMock('Anna\Commons\CurlInterface');
//     $http->expects($this->any())
//          ->method('getInfo')
//          ->will($this->returnValue('not JSON'));
//     $this->setExpectedException('HttpResponseException');
//     // create class under test using $http instead of a real CurlRequest
//     $fixture = new ClassUnderTest($http);
//     $fixture->get();
// }


// public function testStubOfCurl2Class()
//     {
//         // Create a stub for the SomeClass class.
//         // $stub = $this->createMock(\Anna\Commons\CurlInterface::interface);
//         $stub = $this->createMock(\Anna\Curl\Curl2::class);
//
//         // Configure the stub.
//         $stub->method('getInfo')
//              ->willReturn('foo');
//
//         // Calling $stub->doSomething() will now return
//         // 'foo'.
//         $this->assertSame('foo', $stub->getInfo());
//     }


// public function testObserversAreUpdated()
//     {
//         // Create a mock for the Observer class,
//         // only mock the update() method.
//         $observer = $this->getMockBuilder(Observer::class)
//                          ->setMethods(['update'])
//                          ->getMock();
//
//         // Set up the expectation for the update() method
//         // to be called only once and with the string 'something'
//         // as its parameter.
//         $observer->expects($this->once())
//                  ->method('update')
//                  ->with($this->equalTo('something'));
//
//         // Create a Subject object and attach the mocked
//         // Observer object to it.
//         $subject = new Subject('My subject');
//         $subject->attach($observer);
//
//         // Call the doSomething() method on the $subject object
//         // which we expect to call the mocked Observer object's
//         // update() method with the string 'something'.
//         $subject->doSomething();
//     }


    public function testCurl2Methods()
    {
         // Create a mock for the Curl2 class,
         // only mock the getInfo() method.
         $observer = $this->getMockBuilder(Curl2::class)
                          ->setMethods(['curlAnUrl'])
                          ->getMock();

         // Set up the expectation for the curlAnUrl() method
         // to be called only once and with the string 'something'
         // as its parameter.
         $observer->expects($this->once())
                  ->method('curlAnUrl')
                //   ->with($this->equalTo('something'));
                ->with($this->equalTo('http://api.ipstack.com/145.38.5.6?access_key=xxxxx&fields=location.country_flag,location.country_flag_emoji,main&hostname=1'));

        // Create a Subject object and attach the mocked
        // Observer object to it.
          $subject = new Geolocator();

          $ipAddress = "145.38.5.6";
          $config = [
               "accessKeyGeo" => 'xxxxx',
               "accessKeyWeather" => 'xxxx',
          ];
          $curl2 = $observer;

           // $result = $subject->getGeoLocation($ipAddress, $config, $curl2);
           $subject->getGeoLocation($ipAddress, $config, $curl2);
    }
}
