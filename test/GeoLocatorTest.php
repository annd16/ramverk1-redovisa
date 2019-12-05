<?php

namespace Anna\GeoLocator;

use Anax\DI\DIFactoryConfig;
use \Anax\Response\Response;
use \Anna\Request\Request;
use \Anna\Session\Session2;

use PHPUnit\Framework\TestCase;

/**
 * Test the IpValidatorController.
 */
class GeoLocatorTest extends TestCase
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
        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // View helpers uses the global $di so it needs its value
        // $di = $this->di;



        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anna\Request\Request();
        // $this->session = new  \Anna\Session\Session2();

        $this->response = new Response();
        $this->request = new Request();
        $this->session = new  Session2();

        $this->request->setGlobals(
            [
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

        // $geoStub = $this->createMock('Anna\Commons\GeoLocatorInterface');
        // $this->mock = $this->createMock('\Anna\Commons\GeoLocatorInterface');
        $this->mock = $this->createMock('\Anna\GeoLocator\GeoLocator');
        // echo("\$this->request = ");
        // var_dump($this->request);
        // die();
    }
    // $this->geolocatorObj = new GeoLocator();


   /**
    * Provider for the Ip-addresses
    *
    * @return array
    */
    public function providerCheckIfDestroy()
    {
        $response = new Response();
        $request = new Request();
        $session = new Session2();

        // $request = $this->request;
        // $session = $this->session;
        // $response = $this->response;
   //    // $this->request = new \Anax\Request\Request();
   //    $this->request = new \Anna\Request\Request();
   //    $this->session = new  \Anna\Session\Session2();
        return [
           [$request, $session, $response, "geo"]
        ];
    }


   /**
    * Test
    *
    * @param string $ipAddress
    *
    * @return void
    *
    * @dataProvider providerCheckIfDestroy
    */
    public function testCheckIfDestroy($request, $session, $response, $mount)
    {
        $geolocatorObj = new GeoLocator();

        $session->set("rop", "Hurraaa!");
        $session->set("pip", "Piiip!");

        var_dump($session->get("rop"));
        var_dump($session->get("pip"));

        // echo("\$this->request = ");
        // var_dump($this->request);
        // echo("\$request = ");
        // var_dump($request);

        // die();
        // $this->request->setGet("destroy", true);
        $request->setGet("destroy", true);

  //    echo "<br/>\$_GET = ";
  //    var_dump($_GET);              // En tom array!!

        // print_r("\n\$_GET['destroy'] = ", false);
        // var_dump($_GET["destroy"]);              // => Undefined index!!!

        //  $this->request->getGet("destroy");
        // echo("session property_exists('rop') = ");
        // var_dump(property_exists($session, "rop"));     // Blir false!
        //
        $this->assertTrue($session->has("rop"));

        // print_r("\n\$this->request->getGet('destroy') = ", false);
        // var_dump($this->request->getGet("destroy"));             // => boolean(true)

        print_r("\n\$request->getGet('destroy') = ", false);
        var_dump($request->getGet("destroy"));             // => boolean(true)

  //    function checkIfObjIsEmpty($obj) {
  //        foreach ( $obj as $prop ) {
  //            return false;
  //        }
  //        return true;
  //    }

        print_r("\nsession in testCheckIfDestroy() = ");
        // var_dump($this->session);
        var_dump($session);

        // // Cast the session object into an array to be able to test if it is empty or not:
        // $sessionArray = (array)$session;
        // echo("Iterating through \$sessionsArray: ");
        // foreach ($sessionArray as $key => $val) {
        //     echo("\$key = ");
        //     var_dump($key);
        //     echo("\$val = ");
        //     var_dump($val);
        // }

        // Nedanstående fungerar inte - den skapade arrayen verkar vara tom
        // $this->assertFalse(empty($sessionArray));

        // $this->assertAttributeNotEmpty("rop", $session);
        // Fungerar inte, säger att objektet inte har något attribut "rop"!

        // $this->assertContains("rop", $session);
        // Fungerar inte, säger att rgument #2 (No Value) of
        // PHPUnit\Framework\Assert::assertContains() must be a array,
        // traversable or string
        // die();

        // Do the test and assert it
        ob_start();
        $geolocatorObj->checkIfDestroy($request, $session, $response, $mount);
        // $res = $this->controller->indexAction($this->request->getGet("destroy"));

        $output = ob_get_contents();
        ob_end_clean();
        // $res = $controller->catchAll(...$args);
        $this->assertContains("Session has been killed", $output);


        print_r("\nsession in testCheckIfDestroy() after calling checkIfDestroy = ");
        var_dump($session);

        // Cast the session object into an array to be able to test if it is ampty or not:
        // $sessionArray = (array)$session;
        // $this->assertTrue(empty($sessionArray));

        $this->assertFalse($session->has("rop"));
  //    echo "<br/>session in testIndexActionKillSession() = ";
  //    var_dump($this->session);
  // //    $this->assertInstanceOf("\Anax\Response\Response", $res);
  // //    $body = $res->getBody();

        // $this->assertAttributeEmpty("rop", $session);
        // $this->assertEmpty($session);   // Fungerar inte att kolla om sessionen är tom!
     //    }
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
        $curl2Stub = $this->createMock(\Anna\Curl\Curl2::class);
        $config = [
             "accessKeyGeo" => 'xxxxx',
             "accessKeyWeather" => 'xxxx',
        ];

        // ];
        return [
            [false, "145.38.5.6", $config, $curl2Stub],
            // [false, "192.96.76.1"],
            // [false, "145.38.5.6"],
            // ["reserved", "0.2.1.8"],
            // ["private", "fd12:3456:789a:1::1"],
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
    public function testGetGeoLocation($expected, $ipAddress, $config, $curl2Stub)
    {

        $geolocatorObj = new GeoLocator();

        // public function getGeoLocation($ipAddress, $config, $curl2)
        // {
        //     $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";
        //     // $responseFromIpStack  = \Anna\Curl\Curl::curlAnUrl($url);
        //     $responseFromIpStack  = $curl2->curlAnUrl($url);
        //         // Show incoming variables and view helper functions
        //         //echo showEnvironment(get_defined_vars(), get_defined_functions());
        //     return $responseFromIpStack ;
        // }

        $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";

        $response = '{"ip":"145.38.5.6","hostname":"145.38.5.6","type":"ipv4","continent_code":"EU","continent_name":"Europe","country_code":"NL","country_name":"Netherlands","region_code":null,"region_name":null,"city":null,"zip":null,"latitude":52.3824,"longitude":4.8995,"location":{"country_flag":"http:\/\/assets.ipstack.com\/flags\/nl.svg","country_flag_emoji":"\ud83c\uddf3\ud83c\uddf1"}}';
       //  $stub->setMethods(["exec"]);
        // $stub->method("exec")->will($this->returnValue($response));

        $curl2Stub->method("curlAnUrl")->with($url)->will($this->returnValue($response));


        echo "<br/>ipAddress in testGetGeoLocation() = ";
        var_dump($ipAddress);

        echo "<br/>expected in testGetGeoLocation() = ";
        var_dump($expected);

        echo "<br/>config in testGetGeoLocation() = ";
        var_dump($config);

        echo "<br/>curl2 in testGetGeoLocation = ";
        var_dump($curl2Stub);


        // $responseFromIpStack  = $geolocator->getGeoLocation($ipAddress, $config, $curl2);
        $result = $geolocatorObj->getGeoLocation($ipAddress, $config, $curl2Stub);

        $expected = '{"ip":"145.38.5.6","hostname":"145.38.5.6","type":"ipv4","continent_code":"EU","continent_name":"Europe","country_code":"NL","country_name":"Netherlands","region_code":null,"region_name":null,"city":null,"zip":null,"latitude":52.3824,"longitude":4.8995,"location":{"country_flag":"http:\/\/assets.ipstack.com\/flags\/nl.svg","country_flag_emoji":"\ud83c\uddf3\ud83c\uddf1"}}';
    //    echo "<br/>dataProvider = ";
    //    // var_dump($dataProvider);
    //    var_dump($this->providerIpAddress2());


    //    echo "<br/>dataDescription() = ";
    //    var_dump($this->dataDescription("dataName"));

        // $result =  $this->checkIfAdressIsPrivOrRes($ipAddress);

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
    public function providerConvertToJson()
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
            ['{"ip":"145.38.5.6","hostname":"145.38.5.6","type":"ipv4","continent_code":"EU","continent_name":"Europe","country_code":"NL","country_name":"Netherlands","region_code":null,"region_name":null,"city":null,"zip":null,"latitude":52.3824,"longitude":4.8995,"location":{"country_flag":"http:\/\/assets.ipstack.com\/flags\/nl.svg","country_flag_emoji":"\ud83c\uddf3\ud83c\uddf1"}}',
            [   "ip" => "",
                "version" => "",
                "latitude" => "",
                "longitude" => "",
                "country_name" => "",
                "message" => "",
                "map" => "",
                "country_flag" => "",
            ], "145.38.5.6", "Ipv4"]
        ];
    }

    /**
     * Test
     *
     * @param string $ipAddress
     *
     * @return void
     *
     * @dataProvider providerConvertToJson
     */
    public function testConvertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType)
    {

        $geolocator = new GeoLocator();

        // public function getGeoLocation($ipAddress, $config, $curl2)
        // {
        //     $url = "http://api.ipstack.com/{$ipAddress}?access_key={$config['accessKeyGeo']}&fields=location.country_flag,location.country_flag_emoji,main&hostname=1";
        //     // $responseFromIpStack  = \Anna\Curl\Curl::curlAnUrl($url);
        //     $responseFromIpStack  = $curl2->curlAnUrl($url);
        //         // Show incoming variables and view helper functions
        //         //echo showEnvironment(get_defined_vars(), get_defined_functions());
        //     return $responseFromIpStack ;
        // }

        // Convert the incoming string to a javascript object
        $respFromIpStackExp = json_decode($responseFromIpStack);
        // echo "<br/>ipAddress in testGetGeoLocation() = ";
        // var_dump($ipAddress);
        //
        // echo "<br/>expected in testGetGeoLocation() = ";
        // var_dump($expected);
        //
        // echo "<br/>config in testGetGeoLocation() = ";
        // var_dump($config);
        //
        // echo "<br/>curl2 in testGetGeoLocation = ";
        // var_dump($curl2);
         $expected =
         [   "ip" => "145.38.5.6",
             "version" => "Ipv4",
             "latitude" => $respFromIpStackExp->latitude,
             "longitude" => $respFromIpStackExp->longitude,
             "country_name" => $respFromIpStackExp->country_name,
             "message" => "",
             "map" => "https://www.openstreetmap.org/?mlat=$respFromIpStackExp->latitude&mlon=$respFromIpStackExp->longitude",
             "country_flag" => $respFromIpStackExp->location->country_flag,
         ];

        // $responseFromIpStack  = $geolocator->getGeoLocation($ipAddress, $config, $curl2);
         $result = $geolocator->convertToJsonObject($responseFromIpStack, $geoJson, $ipAddress, $ipType);

    //    echo "<br/>dataDescription() = ";
    //    var_dump($this->dataDescription("dataName"));

        // $result =  $this->checkIfAdressIsPrivOrRes($ipAddress);

         echo "<br/>result = ";
        // var_dump($dataProvider);
         var_dump($result);
        // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
         $this->assertEquals($expected, $result);
       // }
    }
}
