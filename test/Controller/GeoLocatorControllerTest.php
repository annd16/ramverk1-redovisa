<?php

namespace Anna\GeoLocator;

use Anax\DI\DIFactoryConfig;
use \Anax\Response\Response;
use \Anna\Request\Request;
use \Anna\Session\Session2;
use \Anna\Geolocator\GeoLocator;

use PHPUnit\Framework\TestCase;

/**
 * Test the GeoLocatorController.
 */
class GeoLocatorControllerTest extends TestCase
{

    private $request;
    private $response;
    // Create the di container.
    public $di;
    protected $controller;
    protected $ipvalMock;
    protected $geolocMock;
    protected $geolocator;

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
        $this->controller = new GeoLocatorController();
        $this->controller->setDI($this->di);

        $this->response = new Response();
        $this->request = new Request();
        $this->session = new Session2();

        $this->request->setGlobals(
            [
                'server' => [
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

        // Setup the geolocator
        // $this->geolocMock = new GeoLocator();
        $this->geolocator = new GeoLocator();
        // $this->geolocator->setDI($this->di);

        $this->ipvalMock = $this->getMockForTrait('\Anna\Commons\IpValidatorTrait');
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
    public function testInitialize()
    {
     //    echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

        // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
     //    if ($ipAddress && $this->ipvalMock->checkIfValidIp($ipAddress)) {
     //        echo "<br/>the pre-filled IP-address is valid!";
     //        $defaults["ipAddress"] = $ipAddress;
     //        echo "<br/>defaults = ";
     //        var_dump($defaults);
     //    }
        $myObject = $this->controller->initialize();
        $this->assertInstanceOf("\Anna\Geolocator\Geolocator", $myObject);
     //    $body = $res->getBody();
     //    $exp = "| ramverk1</title>";
     //    $this->assertContains($exp, $body);
    }

   /**
    * Provider for the Ip-addresses
    *
    * @return array
    */
    public function providerSetParamsBasedOnArgsCount()
    {

        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // View helpers uses the global $di so it needs its value
        // $di = $this->di;    To get rid of validation error: Avoid unused variables as $di

     //    // Setup the controller
     //    $this->controller = new GeoLocatorController();
     //    $this->controller->setDI($this->di);
        //
     //    $this->response = new \Anax\Response\Response();

        //$this->request = new \Anna\Request\Request();
        $this->request = new Request();

     //    $this->session = new \Anna\Session\Session2();


        $this->request->setGlobals(
            [
               'server' => [
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

        // Setup the geolocator
        // $this->geolocMock = new GeoLocator();
        $this->geolocator = new GeoLocator();
     //    $this->geolocator->setDI($this->di);

        $request = unserialize(serialize($this->request));       // Behövs nog inte serializa/deserializa!
        echo("\n\$this->request in provider = ");
        var_dump(gettype($this->request));
        var_dump(get_class($this->request));
     //    $geolocator = $this->geolocator;
        $geolocator = unserialize(serialize($this->geolocator));
        echo("\n\$this->geolocator in provider = ");
        var_dump($this->geolocator);
        $expected = [1, 3];
        return [
        //    [$request, null],
           [$expected[0]],
           [$expected[1], $request, $geolocator],
        ];
    }

   // /**
   //  * Test
   //  *
   //  * @param string $ipAddress
   //  *
   //  * @return void
   //  *
   //  * @dataProvider providerSetParamsBasedOnArgsCount
   //  */
   // // public function testSetParamsBasedOnArgsCount($request, $geolocator)
   // public function testSetParamsBasedOnArgsCount($request, $geolocator)
   // {
   //     $diService = $this->di;
   //  //    echo("diService = ");
   //  //    var_dump($diService);
   //     $this->controller->setParamsBasedOnArgsCount($diService, $request, $geolocator);
   //     $result == count(argv);
   //     $this->assertEquals($expected, $result);
   // }


   /**
    * Test
    *
    * @param string $ipAddress
    *
    * @return void
    *
    * @dataProvider providerSetParamsBasedOnArgsCount
    */
    public function testSetParamsBasedOnArgsCount(...$args)
    {
        $diService = $this->di;
     //    echo("diService = ");
     //    var_dump($diService);
     //    $this->controller->setParamsBasedOnArgsCount($diService, $request, $geolocator);
        $resultArray = $this->controller->setParamsBasedOnArgsCount($diService, $args);
        echo("\nresultArray i testSetParamsBasedOnArgsCount(...\$args) ");
     //    var_dump($resultArray);
        foreach ($resultArray as $key => $val) {
            gettype($val);
        }
        $counts1 = count($args);
        echo("\ncount = " . $counts1);
        $this->assertEquals($args[0], $counts1);
        $counts2 = count($resultArray);
        echo("\ncount = " . $counts2);
        $this->assertEquals(2, $counts2);
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
    //  * Provider for the Ip-addresses
    //  *
    //  * @return array
    //  */
    // public function providerIpAddressAndMore()
    // {
    //     $request = $this->di->get("request");
    //     $geolocator = $this->geolocator;
    //     return [
    //         [""],
    //         ["192.96.76.1", $request, $geolocator],
    //         ["145.38.5.6", $request, $geolocator],
    //         // ["35.158.84.49"],
    //         // ["23.34"],
    //         // ["35.158.84.49, 23.34"],
    //     ];
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
        echo "<br/>ipAddress in testIndexAction() = " . $ipAddress;

        // if ($ipAddress && $this->checkIfValidIp($ipAddress)) {
        if ($ipAddress && $this->ipvalMock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction($this->request, $this->geolocator);
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
        if ($ipAddress && $this->ipvalMock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction($this->request, $this->geolocator);
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
     * @dataProvider providerIpAddress
     *
     * @return void
     */
    // public function testIndexActionKillSession($ipAddress)
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
        $this->controller->indexAction($this->request, $this->geolocator);
     // $res = $this->controller->indexAction($this->request->getGet("destroy"));
        $output = ob_get_contents();
        ob_end_clean();
     // $res = $controller->catchAll(...$args);
        $this->assertContains("Session has been killed", $output);


     //    echo "<br/>session in testIndexActionKillSession() = ";
     //    var_dump($this->session);
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
        if ($ipAddress && $this->ipvalMock->checkIfValidIp($ipAddress)) {
            echo "<br/>the pre-filled IP-address is valid!";
            $defaults["ipAddress"] = $ipAddress;
            echo "<br/>defaults = ";
            var_dump($defaults);
        }
        $res = $this->controller->indexAction(null, $this->geolocator);
        $this->assertInstanceOf("\Anax\Response\Response", $res);
        $body = $res->getBody();
        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }
}
