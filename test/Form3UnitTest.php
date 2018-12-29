<?php

namespace Anna\Form3;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Guess.
 */
class Form3UnitTest extends TestCase
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
        $this->form = new Form3Unit($formVars, $form, $this->submitValues, $validNames, $noSubmitButtons);
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
        $form = new Form3Unit($formVars, $form, $submitValues, $validNames, $noSubmitButtons);
        $this->assertInstanceOf("\Anna\Form3\Form3", $form);


        $mount = "ip";
        $formAttrs = [
            "game" => "ipvalidation",
            "save" => "session",
            "method" => "post",
        ];
        // $form->createForm($game, $method)
        $formStr = $form->displayForm($mount, $formAttrs);

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
        $formVars = \Anna\Form3\Form3Unit::populateFormVars4($form, $this->request, $timestamp, $defaults);
        $this->assertArrayHasKey("ipAddress", $formVars);
        $this->assertArrayHasKey("timestamp", $formVars);
        $this->assertArrayHasKey("else", $formVars);

        // $this->assertEquals($expected, $formStr);
    }


    public function testSetFormAction()
    {
        // // public function setFormAction($name, $mount, $submount = "", $params = [])
        // {
        //     // $submount måste ha formen /xxxx dvs börja med en slash.
        //     $this->formActions[$name] = $mount . $submount;
        //     if (count($params) > 0) {
        //         foreach ($params as $value) {
        //             $this->formActions[$name] .= "/" . $value;
        //         }
        //     }
        //     $this->formActions[$name] = \Anax\View\url($this->formActions[$name]);
        // }
        $name = strtoLower($this->submitValues[0]);
        $mount = "ip";
        $submount = "/web";

        $params = [3, 4, 5];

        $dummy = "HEEEJJJ!";

        $this->form->setFormAction($name, $mount, $dummy, $submount, $params);

        echo "this->form->formActions[$name] = ";
        var_dump($this->form->formActions[$name]);

        $result = $this->form->getFormAction($name);
        // $expected = "http://localhost:8081/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip/web/3/4/5";
        $expected = '://C:/Users/Anna/dbwebb-kurser/ramverk1/me/redovisa/vendor/phpunit/phpunit/ip/web/3/4/5';

        $this->assertEquals($expected, $result);
    }

    //
    //
    // public function testDisplayFormGetGetCheat()
    // {
    //     $theNumber = 34;
    //     $noGuessesLeft = 5;
    //     // $guess = new \Anna\Guess\Guess($theNumber, $noGuessesLeft);
    //     $done = "false";
    //     $else = "";
    //     $guessedNumber = null;
    //     $form = new FormUnit($done, $else, $guessedNumber, $theNumber, $noGuessesLeft);
    //     $this->assertInstanceOf("\Anna\Form\FormUnit", $form);
    //
    //     echo "validNames6 = ";
    //     var_dump($form->validNames);
    //
    //
    //     $game = "get";
    //     $method = "get";
    //     // $form->createForm($game, $method)
    //     $formStr = $form->displayForm($game, $method, true);
    //     $expected = "<form class='form form-cheat form-get' action='get' method='get'>"
    //         . "<input type='hidden' name='guessedNumber' value='$guessedNumber' $else>"
    //         . "<input type='hidden' name='theNumber' value='$theNumber' $else>"
    //         . "<input type='hidden' name='done' value='$done' $else>"
    //         . "<input type='hidden' name='noGuessesLeft' value='$noGuessesLeft' $else>"
    //         . "<input type='submit' class='submit' name='cheat' value='Cheat' $else>"
    //         . "</form>";
    //     $this->assertEquals($expected, $formStr);
    // }
    //
    //
    // public function testDisplayFormSessionPost()
    // {
    //     // $guess = new \Anna\Guess\Guess($theNumber, $noGuessesLeft);
    //     $done = "false";
    //     $else = "";
    //
    //     $guessedNumber = 22;
    //     $form = new FormUnit($done, $else, $guessedNumber);
    //     $this->assertInstanceOf("\Anna\Form\FormUnit", $form);
    //
    //     echo "validNames7 = ";
    //     var_dump($form->validNames);
    //
    //
    //     $game = "session";
    //     $method = "post";
    //     // $form->createForm($game, $method)
    //     $formStr = $form->displayForm($game, $method);
    //
    //     $theNumber = null;
    //     $noGuessesLeft = null;
    //     $expected = "<form class='form form-guess form-session' action='session' method='post'>"
    //         . "<input type='number' name='guessedNumber' value='$guessedNumber' $else>"
    //         // . "<input type='hidden' name='theNumber' value='$theNumber' $else>"
    //         . "<input type='hidden' name='done' value='$done' $else>"
    //         // . "<input type='hidden' name='noGuessesLeft' value='$noGuessesLeft' $else>"
    //         . "<input type='submit' class='submit' name='' value='Submit guess' $else>"
    //         . "</form>";
    //     // $expected = "<form class='form form-guess form-session' action='session' method='post'>"
    //     //     . "<input type='number' name='guessedNumber' value='$guessedNumber' $else>"
    //     //     . "<input type='hidden' name='done' value='$done' $else>"
    //     //     . "<input type='submit' class='submit' name='' value='Submit guess' $else>"
    //     //     . "</form>";
    //     $this->assertEquals($expected, $formStr);
    // }
    //
    //
    // public function testDisplayFormSessionPostCheat()
    // {
    //     // $guess = new \Anna\Guess\Guess($theNumber, $noGuessesLeft);
    //     $done = "false";
    //     $else = "";
    //
    //     // $guessedNumber = 22;
    //     $form = new FormUnit($done, $else);
    //     $this->assertInstanceOf("\Anna\Form\FormUnit", $form);
    //
    //     echo "validNames8 = ";
    //     var_dump($form->validNames);
    //
    //     $game = "session";
    //     $method = "post";
    //     // $form->createForm($game, $method)
    //     $formStr = $form->displayForm($game, $method, true);
    //
    //     // $guessedNumber = null;
    //     // $theNumber = null;
    //     // $noGuessesLeft = null;
    //     $expected = "<form class='form form-cheat form-session' action='session' method='post'>"
    //         // . "<input type='hidden' name='guessedNumber' value='$guessedNumber' $else>"
    //         // . "<input type='hidden' name='theNumber' value='$theNumber' $else>"
    //         . "<input type='hidden' name='done' value='$done' $else>"
    //         // . "<input type='hidden' name='noGuessesLeft' value='$noGuessesLeft' $else>"
    //         . "<input type='submit' class='submit' name='cheat' value='Cheat' $else>"
    //         . "</form>";
    //     // $expected = "<form class='form form-guess form-session' action='session' method='post'>"
    //     //     . "<input type='number' name='guessedNumber' value='$guessedNumber' $else>"
    //     //     . "<input type='hidden' name='done' value='$done' $else>"
    //     //     . "<input type='submit' class='submit' name='' value='Submit guess' $else>"
    //     //     . "</form>";
    //     $this->assertEquals($expected, $formStr);
    // }


//     /**
//      * Construct object and verify that the object has the expected
//      * properties. Use only first argument.
//      */
//     public function testCreateObjectFirstArgument()
//     {
//         $guess = new Guess(42);
//         $this->assertInstanceOf("\Anna\Guess\Guess", $guess);
//
//         $res = $guess->getNoGuessesLeft();
//         $exp = 6;
//         $this->assertEquals($exp, $res);
//
//         $res = $guess->getTheNumber();
//         $exp = 42;
//         $this->assertEquals($exp, $res);
//     }
//
//
//
//     /**
//      * Construct object and verify that the object has the expected
//      * properties. Use both arguments.
//      */
//     public function testCreateObjectBothArguments()
//     {
//         $guess = new Guess(42, 7);
//         $this->assertInstanceOf("\Anna\Guess\Guess", $guess);
//
//         $res = $guess->getNoGuessesLeft();
//         $exp = 7;
//         $this->assertEquals($exp, $res);
//
//         $res = $guess->getTheNumber();
//         $exp = 42;
//         $this->assertEquals($exp, $res);
//     }
//
// // /**
// //  * Make a Guess and verify that the method behaves as expected.
// //  * No tries left (tries < 1).
// //  */
// // public function testCheckTheGuessNoGuessesLeftLowerThanOne()
// // {
// //     $theSecretNumber = 67;
// //     $noGuessesLeft = 0;
// //     $guess = new Guess($theSecretNumber, $noGuessesLeft);
// //     $guessedNumber = 34;
// //     $expected = "no guesses left.";
// //     $result = $guess->makeGuess($guessedNumber);
// //     $this->assertEquals($expected, $result);
// // }
//
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCheckTheGuessGuessedNumberLowerThanSecret()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 34;
//         $expected = "too low!";
//         $result = $guess->checkTheGuess($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCheckTheGuessGuessedNumberHigherThanSecret()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 84;
//         $expected = "too high!";
//         $result = $guess->checkTheGuess($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCheckTheGuessGuessedNumberEqualsSecret()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 67;
//         $expected = "correct!";
//         $result = $guess->checkTheGuess($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCompareGuessedNumberEqualsSecret()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 67;
//         $expected = "true";
//         $result = $guess->compare($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCheckNoGuessesLeftEqualsOrAboveZero()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 0;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 67;
//         $expected = "aboveZeroLeft";
//         $result = $guess->checkNoGuessesLeft($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCheckNoGuessesLeftBelowZero()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = -1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $guessedNumber = 67;
//         $expected = "zeroLeft";
//         $result = $guess->checkNoGuessesLeft($guessedNumber);
//         $this->assertEquals($expected, $result);
//     }
//
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testCreateTheNumber()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 1;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         for ($i = 0; $i <= 100; $i++) {
//             $guess->createTheNumber();
//             $theRandomNumber = $guess->getTheNumber();
//             $this->assertGreaterThanOrEqual(1, $theRandomNumber);
//             $this->assertLessThanOrEqual(100, $theRandomNumber);
//         }
//     }
//
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testConstMaxNoGuesses()
//     {
//         $this->assertEquals(6, MAX_NO_GUESSES);
//     }
//
//     /**
//      * Make a Guess and verify that the method behaves as expected.
//      * No tries left (tries > 0).
//      */
//     public function testSetNoGuessesLeft()
//     {
//         $theSecretNumber = 67;
//         $noGuessesLeft = 4;
//         $guess = new Guess($theSecretNumber, $noGuessesLeft);
//         $noGuessesLeft = 2;
//         $guess->setNoGuessesLeft($noGuessesLeft);
//         $result = $guess->getNoGuessesLeft();
//         $expected = $noGuessesLeft;
//         $this->assertEquals($expected, $result);
//     }
}
