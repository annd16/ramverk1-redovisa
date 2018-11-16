<?php

/**
 * A module for Form class.
 *
 * This is the module containing the Form3 class.
 *
 * @author  Anna
 */


namespace Anna\Form3;

/**
 * A Form3 class that handles the html forms
 *
 */
class Form3
{

    /**********
     * Properties
     **********/
     /**
     *  $inputs
     * -
     *   input fields for Form3 form
     */
    private $validNames = [];
    private $noSubmitButtons;
    private $formActions = [];

    /**********
     * Methods
     **********/

    /**
    * Constructor.
    *
    * @param boolean $done - true if Form3edNumber is equal to theNumber.
    * @param string  $else - if $done is true than $else is "disabled", otherwise an empty string.
    * @param integer $Form3edNumber - the Form3ed number, defaults to null.
    * @param integer $theNumber - the random number,  defaults to null.
    * @param integer $noForm3esLeft - number of Form3es left, defaults to null.
    *
    * @return self
    */
    // public function __construct($done, $else, $player, $noRolls, $form)
    // public function __construct($formVars, $form, $submit, $validNames)
    public function __construct($formVars, $form, $submitValues, $validNames, $noSubmitButtons = 1)
    {
        // $params = func_get_args();
        // $noParams = func_num_args();
        // echo "\$params  =";
        // var_dump($params);
        // echo "\$noParams  =";
        // var_dump($noParams);

        $this->form = $form;
        $this->formVars = $formVars;
        $this->submitValues = $submitValues;

        // New 181101
        $this->validNames = $validNames;

        $this->noSubmitButtons = $noSubmitButtons;


        // // För att sätta värdena på variablerna
        //
        // for ($i=0; $i < count($this->formVars)-$noSubmitButtons; $i++) {
        //     echo "<br/>\$formVars[\$this->form[$i]['name'] = ";
        //     var_dump($formVars[$this->form[$i]['name']]);
        //     $this->form[$i]['value'] = $formVars[$this->form[$i]['name']];
        // }


        // För att sätta värdena på variablerna

        // for ($i=0; $i <= count($this->formVars)-$noSubmitButtons; $i++) {
        //     echo "<br/>\$formVars[\$this->form[$i]['name'] = ";
        //     var_dump($formVars[$this->form[$i]['name']]);
        //     $this->form[$i]['value'] = $formVars[$this->form[$i]['name']];
        // }

        $i = 0;
        while ($this->form[$i]['type'] !== "submit") {
            echo "<br/>\$this->form[$i]['type'] in constructor = ";
            var_dump($this->form[$i]['type']);
            echo "<br/>\$formVars[\$this->form[$i]['name'] = ";
            var_dump($formVars[$this->form[$i]['name']]);
            $this->form[$i]['value'] = $formVars[$this->form[$i]['name']];
            $i++;
        }

        // // Set values for the submit button
        // $this->form[count($this->form)-1]['name'] = strtoLower($submit);
        // $this->form[count($this->form)-1]['value'] = $submit;
        // $this->form[count($this->form)-1]['else'] = $formVars['else'];


        // // Set values for the submit buttons
        //
        // for ($i = $noSubmitButtons; $i > 0; $i--) {
        //     $this->form[count($this->form)-$i]['name'] = strtoLower( $submitValues[$noSubmitButtons-$i]);
        //     $this->form[count($this->form)-$i]['value'] = $submitValues[$noSubmitButtons-$i];
        //     $this->form[count($this->form)-$i]['else'] = $formVars['else'];
        // }

        // // Set values for the submit buttons
        //
        // for ($i = 0; $i < $noSubmitButtons; $i++) {
        //     $this->form[count($this->form)-1 + $i]['name'] = strtoLower($submitValues[$i]);
        //     $this->form[count($this->form)-1 + $i]['value'] = $submitValues[$i];
        //     $this->form[count($this->form)-1 + $i]['else'] = $formVars['else'];
        // }

        // Set values for the submit buttons

        for ($i = 0; $i < $noSubmitButtons; $i++) {
            $this->form[count($this->form)-$noSubmitButtons + $i]['name'] = strtoLower($submitValues[$i]);
            $this->form[count($this->form)-$noSubmitButtons + $i]['value'] = $submitValues[$i];
            $this->form[count($this->form)-$noSubmitButtons + $i]['else'] = $formVars['else'];
            echo "<br/>\$submitValues[$i] = " . $submitValues[$i];
        }




    }

    /**
   * Form::getAction()
   *
   *  Get the action
   *
   * @param string - $type - the type of the input
   * @param string - $name - the name of the input
   * @param mixed - $value - the value
   * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   *
   * @return string - the value for action attribute as a string
   */
    public function getFormAction($name)
    {
        return $this->formActions[$name];
    }


    /**
   * Form::setAction()
   *
   *  Set the action
   *
   * @param string - $type - the type of the input
   * @param string - $name - the name of the input
   * @param mixed - $value - the value
   * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   *
   * @return string - the value for action attribute as a string
   */
    private function setFormAction($name, $mount, $submount = "", $params = [])
    {
        // $submount måste ha formen /xxxx dvs börja med en slash.
        $this->formActions[$name] = $mount . $submount;
        if (count($params) > 0) {
            foreach ($params as $value) {
                $this->formActions[$name] .= "/" . $value;
            }
        }
        $this->formActions[$name] = \Anax\View\url($this->formActions[$name]);
    }


     /**
    * Form::createFormStartTag()
    *
    * Create an html <form> start tag

    * @param string - $class - the class as a string
    * @param string - $action - the action/route as a string
    * @param string - $method - the method used (i.e. get/post) as a string
    *
    * @return string - the start tag as a string
    */
    private function createFormStartTag($class, $action, $method)
    {
        return "<form class='$class' action='$action' method='$method'>";
    }



    /**
   * Form::createInput()
   *
   * Create an html <input> tag
   *
   * @param string - $type - the type of the input
   * @param string - $name - the name of the input
   * @param mixed - $value - the value
   * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   *
   * @return string - the input tag as a string
   */
    private function createInput($type, $name, $value, $else = "")
    {
        return "<input type='$type' name='$name' value='$value' $else>";
    }


   //  /**
   // * Form::createInput2()
   // *
   // * Create an html <input> tag
   // *
   // * @param string - $type - the type of the input
   // * @param string - $name - the name of the input
   // * @param mixed - $value - the value
   // * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   // *
   // * @return string - the input tag as a string
   // */
   //  private function createInput2($id, $type, $name, $value, $else = "")
   //  {
   //      return "<input id='$id' type='$type' name='$name' value='$value' $else>";
   //  }




    /**
   * Form::createInputSubmit()
   *
   * Create an html <input> submit tag
   *
   * @param string - $name - the name of the input
   * @param string - $value - the value
   * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   *
   * @return string - the input tag as a string
   */
    private function createInputSubmit($formaction, $name, $value, $else = "")
    {
        return "<input type='submit' class='submit' name='$name' formaction='$formaction' value='$value' $else>";
    }

    /**
   * Form::createFormEndTag()
   *
   * Create an html <form> end tag
   *
   * @return string - the start tag as a string
   */
    private function createFormEndTag()
    {
        return "</form>";
    }


    /**
   * Form3::createForm()
   * Create a form
   *
   * @param string  $save - the save version i.e get/post/session/session-object
   * @param string  $method - the method used to send data i.e. GET or POST
   * @param boolean $cheat -true if cheat
   *
   * @return string
   */
    // private function createForm($game, $save, $method)
    // private function createForm($game, $save, $method, $mount)
    private function createForm($game, $save, $method, $mount)
    {
        // $action = \Anax\View\url("tärning/" . strtoLower($this->submit));
        // $action = \Anax\View\url($mount . "/" . strtoLower($this->submit));          // Fungerar inte, action blir 'http://localhost:8081/ip'
        // $action = \Anax\View\url(strtoLower($this->submit));                         // Fungerar!!

        // $action = \Anax\View\url($mount . "/" . strtoLower($this->submit));

        // Fungerar också förutsatt att $mount sätts till tom sträng i controllern.
       // Så här borde det fungera även för spelen?.          /
        // if ($mount === "")
        //     $action = \Anax\View\url(strtoLower($this->submitValues[0]) . "/process");      // Fungerar också förutsatt att $mount sätts till tom sträng i controllern.
        // else {
        //     $action = \Anax\View\url($mount . "/" . strtoLower($this->submitValues[0] . "/process"));
        // }

        $action = isset($action) ? $action : "";

        // if (strtoLower($this->submit) === "start") {
        //     echo $this->form[0]['name'];
        //     echo $this->form[1]['name'];
        //
        //     echo "<label for='{$this->form[0]['name']}'>{$this->form[0]['name']}";
        //     echo "<label for='{$this->form[1]['name']}'>{$this->form[1]['name']}";
        //     // $action .= "/" . $this->form[0]["value"] . "/" . $this->form[1]["value"];
        // }
        $formAsString = "";
        $formAsString .= $this->createFormStartTag("form form-" . $game . " form-" . $save . " form-" . strtoLower($this->submitValues[0]), $action, $method);   // Fungerar!
        // echo "\$formAsString = " . $formAsString;
        // echo "hello1";
        // if (strtoLower($this->submit) === "start") {
        //     $formAsString .=
        //     "<div class='form-label'>" .
        //     "<label class='label' for={$this->form[0]['name']}>{$this->form[0]['name']}</label>" .
        //     "<label class='label' for={$this->form[1]['name']}>{$this->form[1]['name']}</label>" .
        //     "</div>";
        //     // $action .= "/" . $this->form[0]["value"] . "/" . $this->form[1]["value"];
        // }
        // for ($i = 0; $i < count($form)-1; $i++) {
        // $formAsString .= "<div class='unit'>";
        // for ($i = 0; $i < count($this->form)-1; $i++) {
        for ($i = 0; $i < count($this->form)-$this->noSubmitButtons; $i++) {

            echo "\$this->form[$i]['name'] inside createForm()";
            var_dump($this->form[$i]["name"]);
            // echo "hello2";
            // echo "\$this->\$inputs[$i]['type'] = " . $form[$i]["type"];
            // $formAsString .= $this->createInput($type, $name, $value, $else = "");
            // Test 181019
            if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
                $formAsString .=
                // "<div class='form-label'>" .
                // "<div class='start-input-with-label'><label class='start-label' for={$this->form[$i]['name']}>{$this->form[$i]['name']}";
                "<div class='start-input-with-label'><label class='start-label'>{$this->form[$i]['name']}";
            }
            if (in_array($this->form[$i]["name"], $this->validNames)) {
                $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
            } else {
                // $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
                $formAsString .= $this->createInput("hidden", $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
            }
            if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
                $formAsString .=
                "</label></div>";
            }
        }
        // $formAsString .= "</div>";
        // echo "\$this->formAsString = " . $formAsString;


        // $index = count($this->form)-1;

        // Option to have more than one submit button:
        // for ($i = $this->noSubmitButtons-1; $i >= 0; $i--) {
        //     echo "<br/>noSubmitButtons in createForm() = ";
        //     var_dump($this->noSubmitButtons);
        //     $index = count($this->form)-$i-1;
        //     echo "<br/>index = ";
        //     var_dump($index);
        //     $mount = strtoLower($this->submitValues[$i]);
        //     $submount = "";
        //     $params = [];
        //     $this->setFormAction(strtoLower($this->submitValues[$i]), $mount, $submount, $params);
        //     $formAction = $this->getFormAction(strtoLower($this->submitValues[$i]));
        //     // $formAsString .= $this->createInputSubmit($this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
        //     $formAsString .= $this->createInputSubmit($this->formActions[strtoLower($this->submitValues[$i])], $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
        // }


        // Set values for the submit buttons

        for ($i = 0; $i < $this->noSubmitButtons; $i++) {
            // $this->form[count($this->form)-$noSubmitButtons + $i]['name'] = strtoLower($submitValues[$i]);
            // $this->form[count($this->form)-$noSubmitButtons + $i]['value'] = $submitValues[$i];
            // $this->form[count($this->form)-$noSubmitButtons + $i]['else'] = $formVars['else'];
            // echo "<br/>\$submitValues[$i] = " . $submitValues[$i];


            // for ($i = $this->noSubmitButtons-1; $i >= 0; $i--) {
                echo "<br/>noSubmitButtons in createForm() = ";
                var_dump($this->noSubmitButtons);
                $index =count($this->form)-$this->noSubmitButtons + $i;
                echo "<br/>index = ";
                var_dump($index);
                // $mount = strtoLower($this->submitValues[$i]);
                // $submount = "";
                $submount = "/" . strtoLower($this->submitValues[$i]);
                $params = [];
                $this->setFormAction(strtoLower($this->submitValues[$i]), $mount, $submount, $params);
                $formAction = $this->getFormAction(strtoLower($this->submitValues[$i]));
                // $formAsString .= $this->createInputSubmit($this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
                $formAsString .= $this->createInputSubmit($this->formActions[strtoLower($this->submitValues[$i])], $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
            // }
        }


        // $formAsString .= $this->createInputSubmit($this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
        // $formAsString .= "</div>";
        // echo "\$formAsString = " . $formAsString;
        // }
        $formAsString .= $this->createFormEndTag();
        // echo "\$formAsString = " . $formAsString;
        return $formAsString;
    }

    /**
   * Form3::displayForm()
   * Display a form
   *
   * @param array  $formAttrs - the form attributes
   * @param string  $param - the xxxxx
   *
   * @return string $formAsString - the html form as a string
   */
    // public function displayForm($mount, $formAttrs)
    public function displayForm($mount, $formAttrs)
    {
        // echo "noSubmitButtons = ";
        // echo $noSubmitButtons;
        $formAsString = $this->createForm($formAttrs['game'], $formAttrs['save'], $formAttrs['method'], $mount);
        return $formAsString;
    }

    /**
   * Form3::populateFormVars()
   * Populate form variables
   *
   * @param array  $form - the form config array
   *
   * @return array  $formVars - array containing the formVars
   */
    public static function populateFormVars($form, $app)
    {
        // Populate formVars:
        for ($key = 0; $key < count($form); $key++) {
            // echo "key = " . $key . "<br/>";
            // echo "form[\$key] = ";
            // var_dump($form[$key]);
            // echo("<br/>");
            $index = $form[$key]['name'];               // Get the value of the name-attribute in form

            // Test 181029
            if ($index === "timestamp") {
                $formVars[$index] = time();
            } else {
                // **************************


                // echo "index = " . $index . "<br/>";
                // $formVars[$index] = $app->request->getGet($index, $default = null);
                $formVars[$index] = $app->request->getGet($index, null);            // Test 181029
                // if ($formVars[$index] === null && $index !== "") {
                //     // echo "index = ";
                //     // var_dump($index);
                //     $valueInGameObject =  $dicegame->getValue((string)($index));
                //     // echo "\$valueInGameObject = ";
                //     // var_dump($valueInGameObject);
                //     $formVars[$index] = isset($valueInGameObject) ? $valueInGameObject : null;
                // }
                // echo("\$formVars[\$index] =");
                // var_dump($formVars[$index]);
                if (is_numeric($formVars[$index])) {
                    // echo("\$formVars[\$index] =");
                    // var_dump($formVars[$index]);
                    // echo("<br/>");
                    $formVars[$index] = intval($formVars[$index]);
                    // var_dump($formVars[$index]);
                    // echo("<br/>");
                }
                if ($form[$key]['type'] === "submit") {
                    $index2 = $form[$key]['else'];
                    // Test 181005
                    if ($formVars[$index] !== null || !empty($formVars[$index])) {
                        $formVars['else'] = $app->request->getGet($index2, "");
                    } elseif ($formVars[$index] === null) {
                        $formVars['else'] = $app->request->getGet($index2, "disabled");
                    }
                }
            } // Test 181029
        }
        return $formVars;
    }


    /**
   * Form3::populateFormVars()
   * Populate form variables
   *
   * @param array  $form - the form config array
   *
   * @return array  $formVars - array containing the formVars
   */
    public static function populateFormVars2($form, $app, $timestamp = false)
    {
        echo "<br/>populateFormVars2()";
        //  foreach($form as $key => $val) {
        for ($key = 0; $key < count($form); $key++) {
            echo "<br/>key = " . $key . "<br/>";
            echo "form[\$key] = ";
            var_dump($form[$key]);
            echo("<br/>");
            $index = $form[$key]['name'];               // Get the value of the name-attribute in form
            echo "index (\$form[$key]['name'])= " . $index . "<br/>";

            // Test 181029
            // if ($index === "timestamp") {
            // Test 181101
            if ($index === "timestamp" && $timestamp === true) {
                $formVars[$index] = time();
            } else {
                // **************************

                $formVars[$index] = $app->request->getGet($index, null);

                echo("\$formVars[\$index] =");
                var_dump($formVars[$index]);
                echo("<br/>");

                if (is_numeric($formVars[$index])) {
                    // echo("\$formVars[\$index] =");
                    // var_dump($formVars[$index]);
                    // echo("<br/>");
                    $formVars[$index] = intval($formVars[$index]);
                    // var_dump($formVars[$index]);
                    // echo("<br/>");
                }
                if ($form[$key]['type'] === "submit") {
                    $index2 = $form[$key]['else'];
                    echo "<br/>index2 in populateFormVars2() in Form3 = ";
                    echo $index2;
                    $formVars['else'] = $app->request->getGet($index2, "");
                }
            } // Test 181029
        }
        return $formVars;
    }
}
