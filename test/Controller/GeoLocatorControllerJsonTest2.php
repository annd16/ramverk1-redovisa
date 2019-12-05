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
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerSetParamsBasedOnArgsCount()
    {

        // $this->di = new DIFactoryConfig();
        // $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // View helpers uses the global $di so it needs its value
        // $di = $this->di;      /
        // To get rid of Avoid unused local variables such as '$di'.

        //$this->request = new \Anna\Request\Request();
        $this->request = new Request();


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

        //$this->geolocator = new \Anna\Geolocator\GeoLocator();
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
        $ipAddresses = "192.96.76.1, 145.38.5.6";
        $expected = [1, 3, 4];
        return [
            [$expected[0]],
            [$expected[1], $request, $geolocator],
            [$expected[2], $request, $geolocator, $ipAddresses],
        ];
    }

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
        // echo("\n\$args in testSetParamsBasedOnArgsCount(\$args)");
        // var_dump($args);
        $resultArray = $this->controller->setParamsBasedOnArgsCount($diService, $args);
        echo("\nresultArray i testSetParamsBasedOnArgsCount(...\$args) ");
     //    var_dump($resultArray);
        foreach ($resultArray as $key => $val) {
            gettype($val);
        }
        $counts1 = count($args);
        echo("\ncount = " . $counts1);
        $this->assertEquals($args[0], $counts1);
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


    /**
      * Test the route catchAll().
     *
     * @param string $ipAddress
     *
     * @return void
     *
     */
    public function testCatchAll()
    {
        $route = '/geo/knorr';

        echo "\nINSIDE testCatchAll in json!!!";
        // die();
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
