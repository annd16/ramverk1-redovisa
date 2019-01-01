<?php

namespace Anna\Request;

use \PHPUnit\Framework\TestCase;

/**
 * Storing information from the request and calculating related essentials.
 */
class RequestTest extends TestCase
{
    /**
     * Properties
     */
    private $request;



    /**
     * Set up a request object
     *
     * @return void
     */
    public function setUp()
    {
        $this->request = new Request();
        $this->request->setGlobals(
            [
                'server' => [
                    'REQUEST_SCHEME' => "http",
                    'HTTPS'       => null, //"on",
                    'SERVER_NAME' => "dbwebb.se",
                    'SERVER_PORT' => "80",
                    'REQUEST_URI' => "/anax-mvc/webroot/app.php",
                    'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
                ],
            ]
        );
    }



    /**
     * Test
     *
     * @return void
     *
     */
    public function testGet()
    {
        // 1. Testing getGet() with non-existing key:
        $get = $this->request->getGet("nothing");
        $this->assertEmpty($get, "Nothing is NOT empty.");

        // 2. Testing setGet() with single key-value pair, then fetching it with getGet():
        $key = "somekey";
        $value = "somevalue";
        $this->request->setGet($key, $value);
        $get = $this->request->getGet($key);
        $this->assertEquals($get, $value, "Missmatch between " . $get . " and " . $value);

        // 3. Testing setGet() with an array of key-value pairs, then fetching it with getGet():
        $key = ["someKey" => "first", "someOtherKey" => "second"];
        // $value = "somevalue";
        $this->request->setGet($key);
        // $get = $this->request->getGet($key);

        $result = $this->request->getGet("someKey");
        $expected =  $key['someKey'];

        $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);

        $result = $this->request->getGet("someOtherKey");
        $expected =  $key['someOtherKey'];

        $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);
        // $this->assertArrayHasKey('someKey', $_GET, "\$_GET is missing the expected key 'someKey'.");
        // $this->assertArrayHasKey($key[1], $_GET, "\$_GET is missing the expected key {$key[1]}.");

        // 4. Testing that $_GET is not empty when the request object is created:
        $_GET = ["key0" => "global0"];

        $this->request = new Request;
        $this->request->setGlobals(
            [
                "get" => [
                    "key1" => "global1",
                    "key2" => "global2",
                ]
            ]
        );

        $this->assertArrayHasKey("key0", $_GET, "\$_GET is missing the expected key 'key0'.");

        // 4.Testing hasGet with existing key and a non-existing key:
        $this->request->setGet("key55", "value1");
        $result = $this->request->hasGet("key55");
        $this->assertTrue($result);

        $result = $this->request->hasGet("key56");
        $this->assertFalse($result);
    }


    // /**
    //  * Test
    //  *
    //  * @return void
    //  *
    //  */
    // public function testGetKeyIsArray()
    // {
    //     // $get = $this->request->getGet("nothing");
    //     // $this->assertEmpty($get, "Nothing is NOT empty.");
    //
    //     $key = ["someKey" => "first", "someOtherKey" => "second"];
    //     // $value = "somevalue";
    //     $this->request->setGet($key);
    //     // $get = $this->request->getGet($key);
    //
    //     $result = $this->request->getGet("someKey");
    //     $expected =  $key['someKey'];
    //
    //     $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);
    //
    //     $result = $this->request->getGet("someOtherKey");
    //     $expected =  $key['someOtherKey'];
    //
    //     $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);
    //     // $this->assertArrayHasKey('someKey', $_GET, "\$_GET is missing the expected key 'someKey'.");
    //
    //     // $this->assertArrayHasKey($key[1], $_GET, "\$_GET is missing the expected key {$key[1]}.");
    // }



    /**
     * Test
     *
     * @return void
     *
     */
    public function testPost()
    {
        // testPost()
        $post = $this->request->getPost("nothing");
        $this->assertEmpty($post, "Nothing is NOT empty.");

        $key = "somekey";
        $value = "somevalue";
        $this->request->setPost($key, $value);
        $post = $this->request->getPost($key);
        $this->assertEquals($post, $value, "Missmatch between " . $post . " and " . $value);

        $key = ["someKey" => "first", "someOtherKey" => "second"];
        // $value = "somevalue";
        $this->request->setPost($key);
        // $get = $this->request->getPost($key);

        // testPostKeyIsArray()
        $result = $this->request->getPost("someKey");
        $expected = $key['someKey'];

        $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);

        $result = $this->request->getPost("someOtherKey");
        $expected =  $key['someOtherKey'];

        $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);


        // testPostGetWholePostArray()

        $this->request->setGlobals(
            [   "post" => [
                    "someKey" => "first",
                    "someOtherKey" => "second",
                ]
            ]
        );
        // $this->assertEmpty($get, "Nothing is NOT empty.");

        // $key = ["someKey" => "first", "someOtherKey" => "second"];
        // $value = "somevalue";
        $result = $this->request->getPost();
        // $get = $this->request->getPost($key);

        $this->assertArrayHasKey('someKey', $result, "\$_POST is missing the expected key 'someKey'.");

        $this->assertArrayHasKey('someOtherKey', $result, "\$_POST is missing the expected key 'someOtherKey'.");
    }


    // /**
    //  * Test
    //  *
    //  * @return void
    //  *
    //  */
    // public function testPostKeyIsArray()
    // {
    //     // $get = $this->request->getGet("nothing");
    //     // $this->assertEmpty($get, "Nothing is NOT empty.");
    //
    //     $key = ["someKey" => "first", "someOtherKey" => "second"];
    //     // $value = "somevalue";
    //     $this->request->setPost($key);
    //     // $get = $this->request->getPost($key);
    //
    //     $result = $this->request->getPost("someKey");
    //     $expected = $key['someKey'];
    //
    //     $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);
    //
    //     $result = $this->request->getPost("someOtherKey");
    //     $expected =  $key['someOtherKey'];
    //
    //     $this->assertEquals($result, $expected, "Missmatch between " . $result . " and " . $expected);
    //     // $this->assertArrayHasKey('someKey', $_GET, "\$_GET is missing the expected key 'someKey'.");
    //
    //     // $this->assertArrayHasKey($key[1], $_GET, "\$_GET is missing the expected key {$key[1]}.");
    // }



    // /**
    //  * Test
    //  *
    //  * @return void
    //  *
    //  */
    // public function testPostGetWholePostArray()
    // {
    //     $this->request->setGlobals(
    //         [   "post" => [
    //                 "someKey" => "first",
    //                 "someOtherKey" => "second",
    //             ]
    //         ]
    //     );
    //     // $this->assertEmpty($get, "Nothing is NOT empty.");
    //
    //     // $key = ["someKey" => "first", "someOtherKey" => "second"];
    //     // $value = "somevalue";
    //     $result = $this->request->getPost();
    //     // $get = $this->request->getPost($key);
    //
    //     $this->assertArrayHasKey('someKey', $result, "\$_POST is missing the expected key 'someKey'.");
    //
    //     $this->assertArrayHasKey('someOtherKey', $result, "\$_POST is missing the expected key 'someOtherKey'.");
    // }




//     /**
//      * Provider for routes
//      *
//      * @return array
//      */
//     public function providerRoute()
//     {
//         return [
//             [""],
//             ["controller"],
//             ["controller/action"],
//             ["controller/action/arg1"],
//             ["controller/action/arg1/arg2"],
//             ["controller/action/arg1/arg2/arg3"],
//         ];
//     }
//
//
//
//     /**
//      * Test
//      *
//      * @param string $route the route part
//      *
//      * @return void
//      *
//      * @dataProvider providerRoute
//      */
//     public function testGetRoute($route)
//     {
//         $uri = $this->request->getServer('REQUEST_URI');
//         //$this->assertEmpty($uri, "REQUEST_URI is empty.");
//
//         $this->request->setServer('REQUEST_URI', $uri . '/' . $route);
//         $this->request->init();
//
//         $this->assertEquals($route, $this->request->extractRoute(), "Failed extractRoute: " . $route);
//         $this->assertEquals($route, $this->request->getRoute(), "Failed getRoute: " . $route);
//
//
//         // AA 181229
//
//         // $uri2 = $this->request->getServer('REQUEST_URI');
//
//         $this->request->setServer('REQUEST_URI', $uri . '/' . $route . "?querystring=true");
//         $this->request->init();
//
//         $route .= "?querystring=true";
//         // $this->assertEquals($route, $this->request->extractRoute(), "Failed extractRoute: " . $route);
//         $this->assertNotEquals($route, $this->request->getRoute(), "Failed getRoute: " . $route);
//
//
//         $routeParts = $this->request->getRouteParts();
//         echo "routeParts = ";
//         var_dump($routeParts);
//
//
//         $this->assertInternalType("array", $routeParts);
//         $this->assertNotEmpty($routeParts);
//     }
//
//
//
//     /**
//      * Provider for $_SERVER
//      *
//      * @return array
//      */
//     public function providerGetCurrentUrl()
//     {
//         return [
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/",
//                     'url'         => "http://dbwebb.se",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/img",
//                     'url'         => "http://dbwebb.se/img",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/img/",
//                     'url'         => "http://dbwebb.se/img",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "http://dbwebb.se/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "http://dbwebb.se:8080/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/%31.php",
//                     'url'         => "http://dbwebb.se:8080/anax-mvc/webroot/1.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on", //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "443",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "https://dbwebb.se/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on", //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "https://dbwebb.se:8080/anax-mvc/webroot/app.php",
//                 ]
//             ],
//         ];
//     }
//
//
//
//     /**
//      * Test
//      *
//      * @param string $server the $_SERVER part
//      *
//      * @return void
//      *
//      * @dataProvider providerGetCurrentUrl
//      *
//      */
//     public function testGetCurrentUrl($server)
//     {
//         $this->request->setServer('REQUEST_SCHEME', $server['REQUEST_SCHEME']);
//         $this->request->setServer('HTTPS', $server['HTTPS']);
//         $this->request->setServer('SERVER_NAME', $server['SERVER_NAME']);
//         $this->request->setServer('SERVER_PORT', $server['SERVER_PORT']);
//         $this->request->setServer('REQUEST_URI', $server['REQUEST_URI']);
//
//         $url = $server['url'];
//
//         $res = $this->request->getCurrentUrl();
//
//         $this->assertEquals($url, $res, "Failed url: " . $url);
//     }
//
//
//     /**
//      * Provider for $_SERVER
//      *
//      * @return array
//      */
//     public function providerGetCurrentUrlNoServerName()
//     {
//         return [
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'HTTP_HOST'   => "webdev.dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/",
//                     'url'         => "http://dbwebb.se",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "webdev.dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/img",
//                     'url'         => "http://webdev.dbwebb.se/img",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
// //                    'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/img/",
//                     'url'         => "http://dbwebb.se/img",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "http://dbwebb.se/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "http://dbwebb.se:8080/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on", //"on",
//                     'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "dbwebb.se",
//                     'SERVER_PORT' => "443",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "https://dbwebb.se/anax-mvc/webroot/app.php",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on", //"on",
//                     'SERVER_NAME' => "",
//                     'HTTP_HOST'   => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'url'         => "https://dbwebb.se:8080/anax-mvc/webroot/app.php",
//                 ]
//             ],
//         ];
//     }
//
//
//
//     /**
//      * Test
//      *
//      * @param string $server the $_SERVER part
//      *
//      * @return void
//      *
//      * @dataProvider providerGetCurrentUrlNoServerName
//      *
//      */
//     public function testGetCurrentUrlNoServerName($server)
//     {
//         $fakeGlobal = ['server' => $server];
//
//         $this->request->setGlobals($fakeGlobal);
//
//         $url = $fakeGlobal['server']['url'];
//
//         $res = $this->request->getCurrentUrl();
//
//         $this->assertEquals($url, $res, "Failed url: " . $url);
//     }
//
//
//     /**
//      * Provider for $_SERVER
//      *
//      * @return array
//      */
//     public function providerInit()
//     {
//         return [
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "80",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
//                     'siteUrl'     => "http://dbwebb.se",
//                     'baseUrl'     => "http://dbwebb.se/anax-mvc/webroot",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "http",
//                     'HTTPS'       => null, //"on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
//                     'siteUrl'     => "http://dbwebb.se:8080",
//                     'baseUrl'     => "http://dbwebb.se:8080/anax-mvc/webroot",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "8080",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
//                     'siteUrl'     => "https://dbwebb.se:8080",
//                     'baseUrl'     => "https://dbwebb.se:8080/anax-mvc/webroot",
//                 ]
//             ],
//             [
//                 [
//                     'REQUEST_SCHEME' => "https",
//                     'HTTPS'       => "on",
//                     'SERVER_NAME' => "dbwebb.se",
//                     'SERVER_PORT' => "443",
//                     'REQUEST_URI' => "/anax-mvc/webroot/app.php",
//                     'SCRIPT_NAME' => "/anax-mvc/webroot/app.php",
//                     'siteUrl'     => "https://dbwebb.se",
//                     'baseUrl'     => "https://dbwebb.se/anax-mvc/webroot",
//                 ]
//             ]
//         ];
//     }
//
//
//
//     /**
//      * Test
//      *
//      * @param string $server the route part
//      *
//      * @return void
//      *
//      * @dataProvider providerInit
//      *
//      */
//     public function testInit($server)
//     {
//         $this->request->setServer('REQUEST_SCHEME', $server['REQUEST_SCHEME']);
//         $this->request->setServer('HTTPS', $server['HTTPS']);
//         $this->request->setServer('SERVER_NAME', $server['SERVER_NAME']);
//         $this->request->setServer('SERVER_PORT', $server['SERVER_PORT']);
//         $this->request->setServer('REQUEST_URI', $server['REQUEST_URI']);
//         $this->request->setServer('SCRIPT_NAME', $server['SCRIPT_NAME']);
//
//         $siteUrl = $server['siteUrl'];
//         $baseUrl = $server['baseUrl'];
//
//         $res = $this->request->init();
//         $this->assertInstanceOf(get_class($this->request), $res, "Init did not return this.");
//
//         $this->assertEquals($siteUrl, $this->request->getSiteUrl(), "Failed siteurl: " . $siteUrl);
//         $this->assertEquals($baseUrl, $this->request->getBaseUrl(), "Failed baseurl: " . $baseUrl);
//
//         echo $this->request->getMethod();
//     }
//
//     /**
//      * Test
//      *
//      * @param string $server the route part
//      *
//      * @return void
//      *
//      * @dataProvider providerInit
//      *
//      */
//     public function testSetServerKeyIsArray()
//     {
//         $potifar = ["key1" => "value1", "key2" => "value2"];
//         $this->request->setServer($potifar);
//         $result = $this->request->getServer("key1");
//         $expected =  $potifar['key1'];
//
//         $this->assertEquals($expected, $result, "Failed to assert that {$expected} and {$result} is equal.");
//
//         // $this->assertArrayHasKey("key1", $_SERVER, "The server-array should have the key 'key1'.");
//     }
//
//
//     // /**
//     //  * Test
//     //  *
//     //  * @param string $server the route part
//     //  *
//     //  * @return void
//     //  *
//     //  * @dataProvider providerInit
//     //  *
//     //  */
//     // public function testHasGet()
//     // {
//     //     $this->request->setGet("key1", "value1");
//     //     $result = $this->request->hasGet("key1");
//     //     $this->assertTrue($result);
//     //
//     //     $result = $this->request->hasGet("key2");
//     //     $this->assertFalse($result);
//     //
//     //     // $this->assertArrayHasKey("key1", $_SERVER, "The server-array should have the key 'key1'.");
//     // }
//
//
//     /**
//      * Test
//      */
//     public function testRequestMethod()
//     {
//         $this->request->setServer("REQUEST_METHOD", "GET");
//         $this->assertEquals("GET", $this->request->getMethod());
//     }
//
//
    /**
     * Test
     *
     * @param string $server the route part
     *
     * @return void
     *
     *
     */
    public function testSetAndGetBody()
    {
        $this->request->setBody("<h2>Hello!</h2>");
        $result = $this->request->getBody();
        $expected = "<h2>Hello!</h2>";
        $this->assertEquals($expected, $result);

        // $this->assertArrayHasKey("key1", $_SERVER, "The server-array should have the key 'key1'.");
    }
}
