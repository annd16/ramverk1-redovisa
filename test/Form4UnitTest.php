<?php

namespace Anna\Form4;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Guess.
 */
class Form4UnitTest extends TestCase
{
    protected $di;
    protected $form;
    protected $submitValues;

    /**
     * Set up a request object
     *
     * @return void
     */
    public function setUp()
    {
        global $di;

        // Setup di
        $this->di = new \Anax\DI\DIFactoryConfig();
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

        // $this->response = new \Anax\Response\Response();
        // $this->request = new \Anax\Request\Request();
        $this->request = new \Anna\Request\Request();
        // $this->session = new  \Anax\Session\Session();


        $formVars = [
            'ipAddress' => '::1',
            'timestamp' => 1545168040,
            '' => null,
            'else' => '',
        ];
        $form = [
            [
                "type" => "text",
                "name" => "ipAddress",
                "value" => null,
                "else" => "",

            ],
            [
                "type" => "hidden",
                "name" => "timestamp",
                "value" => null,
                "else" => "",
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
        ];
        $this->submitValues = ["Web", "Json", "GetMyIp"];
        $validNames = ["ipAddress", "submit"];
        $noSubmitButtons = 3;
        // $this->form = new formObjUnit($formVars, $form, $this->submitValues, $validNames, $noSubmitButtons);
        $this->formObj = new Form4Unit();
        $this->formObj->init($formVars, $form, $this->submitValues, $validNames, $noSubmitButtons);
    }


    public function testDisplayFormSessionPost()
    {
        $formVars = [
            'ipAddress' => '::1',
            'timestamp' => 1545168040,
            '' => null,
            'else' => '',
        ];
        $form = [
            [
                "type" => "text",
                "name" => "ipAddress",
                "value" => null,
                "else" => "",

            ],
            [
                "type" => "hidden",
                "name" => "timestamp",
                "value" => null,
                "else" => "",
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
        ];
        $submitValues = ["Web", "Json", "GetMyIp"];
        $validNames = ["ipAddress", "submit"];
        $noSubmitButtons = 3;
        // $form = new formObjUnit($formVars, $form, $submitValues, $validNames, $noSubmitButtons);
        $formObj = new Form4Unit();
        // $formObj->init($formVars, $form, $this->submitValues, $validNames, $noSubmitButtons);
        $formObj->init($formVars, $form, $submitValues, $validNames, $noSubmitButtons);
        $this->assertInstanceOf("\Anna\Form4\Form4", $formObj);


        $mount = "ip";
        $formAttrs = [
            "game" => "ipvalidation",
            "save" => "session",
            "method" => "post",
        ];
        // $form->createForm($game, $method)
        $formStr = $formObj->displayForm($mount, $formAttrs);

        // $url1 = new \Anax\Mock\MockUrl();
        // $url1->create('://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/web');

        $expected =
            // "<form class='form form-ipvalidation form-session form-web' action='' method='post'>"
            // . "<input type='text' name='ipAddress' value='::1' >"
            // . "<input type='hidden' name='timestamp' value='1545168040' >"
            // . "<input type='submit' class='submit' name='web' formaction='http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/web' value='Web' >"
            // . "<input type='submit' class='submit' name='json' formaction='http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json' value='Json' >"
            // . "<input type='submit' class='submit' name='getmyip' formaction='http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/getmyip' value='GetMyIp' ></form>";

            "<form class='form form-ipvalidation form-session form-web' action='' method='post'>"
            . "<input type='text' name='ipAddress' value='::1' >"
            . "<input type='hidden' name='timestamp' value='1545168040' >"
            . "<input type='submit' class='submit' name='web' formaction='://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/web' value='Web' >"
            . "<input type='submit' class='submit' name='json' formaction='://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/json' value='Json' >"
            . "<input type='submit' class='submit' name='getmyip' formaction='://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/getmyip' value='GetMyIp' ></form>";
        $this->assertEquals($expected, $formStr);
    }


    public function testPopulateFormVars4()
    {
        // $form, $di, $timestamp, $defaults = []
        $form = [
            [
                "type" => "text",
                "name" => "ipAddress",
                "value" => null,
                "else" => "",

            ],
            [
                "type" => "number",
                "name" => "testIsNumeric",
                "value" => null,
                "else" => "",

            ],
            [
                "type" => "hidden",
                "name" => "timestamp",
                "value" => null,
                "else" => "",
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
            [
                "type" => "submit",
                "name" => "",
                "value" => null,
                "else" => null,
            ],
        ];

        $this->request->setGet("testIsNumeric", 5);
        $ipAddress = "127.0.0.1";
        $timestamp = true;
        $defaults = ["ipAddress" => $ipAddress];
        // $formVars = \Anna\formObj\formObjUnit::populateFormVars4($form, $this->request, $timestamp, $defaults);
        $formVars = $this->formObj->populateFormVars4($form, $this->request, $timestamp, $defaults);
        $this->assertArrayHasKey("ipAddress", $formVars);
        $this->assertArrayHasKey("timestamp", $formVars);
        $this->assertArrayHasKey("else", $formVars);

        // $this->assertEquals($expected, $formStr);
    }


    // public function testSetFormAction()
    // {
    //     // // public function setFormAction($name, $mount, $submount = "", $params = [])
    //     // {
    //     //     // $submount måste ha formen /xxxx dvs börja med en slash.
    //     //     $this->formActions[$name] = $mount . $submount;
    //     //     if (count($params) > 0) {
    //     //         foreach ($params as $value) {
    //     //             $this->formActions[$name] .= "/" . $value;
    //     //         }
    //     //     }
    //     //     $this->formActions[$name] = \Anax\View\url($this->formActions[$name]);
    //     // }
    //     $name = strtoLower($this->submitValues[0]);
    //     $mount = "ip";
    //     $submount = "/web";
    //
    //     $params = [3, 4, 5];
    //
    //     $dummy = "HEEEJJJ!";
    //
    //     $this->formObj->setFormAction($name, $mount, $dummy, $submount, $params);
    //
    //     echo "this->form->formActions[$name] = ";
    //     var_dump($this->formObj->formActions[$name]);
    //
    //     $result = $this->formObj->getFormAction($name);
    //     // $expected = "http://localhost:8081/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip/web/3/4/5";
    //     $expected = '://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/web/3/4/5';
    //
    //     $this->assertEquals($expected, $result);
    // }
}
