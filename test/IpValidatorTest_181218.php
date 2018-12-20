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
                'post' => [
                    'ipAddress' => "145.38.5.6",
                    'timestamp' => 1544913716,
                    'web' => "Web",
                ]
            ]
        );
    }


    /**
     * Provider for the Ip-addresses
     *
     * @return array
     */
    public function providerIpAddress()
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
            // ["35.158.84.49"],
            // ["23.34"],
            // ["35.158.84.49, 23.34"],
        ];
    }


    /**
     * Provider for the expected result
     *
     * @return array
     */
    public function providerExpected()
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
            [false],
            ["4"],
            ["4"],
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
    public function testCheckIfValidIp($expected, $ipAddress)
    {
        // $results = [];
        // $expectedValues = [false, "4", "4"];
        //
        // // public ArrayIterator::__construct ([ mixed $array = array() [, int $flags = 0 ]] )
        // $expValuesArrayIterator = new \ArrayIterator($expectedValues);
        // $myExpectedValuesObjectIterator = new \ObjectIterator($expectedValues);

//
//         $array = array('1' => 'one',
//                '2' => 'two',
//                '3' => 'three');
//
// $arrayobject = new ArrayObject($array);
//
// for($iterator = $arrayobject->getIterator();
//     $iterator->valid();
//     $iterator->next()) {
//
//     echo $iterator->key() . ' => ' . $iterator->current() . "\n";
// }


// $arrayobject = new \ArrayObject($expectedValues);
//
// for ($iterator = $arrayobject->getIterator();
//     $iterator->valid();
//     $iterator->next()) {
//
//         echo "\niterator-key => iterator-value:";
//     echo "<br/>" .  $iterator->key() . ' => ' . $iterator->current() . "\n";
// }
//
//         echo "<br/>myExpectedValuesArrayIterator = ";
//         var_dump($myExpectedValuesArrayIterator);

        echo "<br/>ipAddress in testCheckIfvalidIp() = ";
        var_dump($ipAddress);

        echo "<br/>expected in testCheckIfvalidIp() = ";
        var_dump($expected);

        echo "<br/>dataProvider = ";
        // var_dump($dataProvider);
        var_dump($this->providerIpAddress());

        //
        // echo "<br/>dataDescription() = ";
        // var_dump($this->dataDescription("dataName"));
        $result =  \Anna\IpValidator\IpValidator::checkIfValidIp($ipAddress);


        echo "<br/>result = ";
        // var_dump($dataProvider);
        var_dump($result);
        // if(is_array($ipAddress)) {
            // foreach ($ipAddress as $key => $address) {
            // for ($expValuesArrayIterator
            //     $expValuesArrayIterator->valid()
            //     $expValuesArrayIterator->next()) {

        // foreach ($expectedValues as $key => $exp) {
        //         echo "</br>var_export(\$expectedValues[$key] = ";
        //         var_dump(var_export($expectedValues[$key]));
                // $this->assertContains(var_export($expectedValues[$key], true), var_export($results[$key], true));
                $this->assertEquals($expected, $result);
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
