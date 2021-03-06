<?php

/**
 * A module for Form class.
 *
 * This is the module containing the Form4 class.
 *
 * @author  Anna
 */


namespace Anna\Form4;

/**
 * A Form4 class that handles the html forms
 *
 */
class Form4
{

    /**********
     * Properties
     **********/

     /**
      * @var array $validNames - valid names for the name attribute.
      */
    private $validNames = [];

    /**
    * @var integer $noSubmitButtons - noOfSubmitButtons.
     */
    private $noSubmitButtons;

    /**
    * @var array $formActions - the form actions for the submit buttons.
    */
    public $formActions = [];


    /**********
     * Methods
     **********/

    /**
    * Form4::__construct().
    *
    */
    public function __construct()
    {
    }


    /**
     * Init the request class by reading information from the request.
     *
     * @param array $formVars - the form variables.
     * @param array  $form - the configuration array for the form.
     * @param array $submitValues - array containing the submit values.
     * @param array $validNames - valid values for the name attribute.
     * @param integer $noSubmitButtons - number of submit buttons, defaults to 1.
     *
     * @return $this
     */
    public function init($formVars, $form, $submitValues, $validNames, $noSubmitButtons = 1)
    {
        $this->form = $form;
        $this->formVars = $formVars;
        $this->submitValues = $submitValues;

        // New 181101
        $this->validNames = $validNames;

        $this->noSubmitButtons = $noSubmitButtons;

        // För att sätta värdena på variablerna

        $i = 0;
        while ($this->form[$i]['type'] !== "submit") {
            // echo "<br/>\$this->form[$i]['type'] in constructor = ";
            // var_dump($this->form[$i]['type']);
            // echo "<br/>\$formVars[\$this->form[$i]['name'] = ";
            // var_dump($formVars[$this->form[$i]['name']]);
            $this->form[$i]['value'] = $formVars[$this->form[$i]['name']];
            $i++;
        }

        // Set values for the submit buttons


        for ($i = 0; $i < $noSubmitButtons; $i++) {
            //$value = str_replace("_", " ", strtoLower($submitValues[$i]));
            //$value = str_replace(" ", "_", $submitValues[$i]);
            // Nytt 191128
            $name = str_replace(" ", "_", strtolower($submitValues[$i]));
            //$this->form[count($this->form)-$noSubmitButtons + $i]['name'] = strtoLower($submitValues[$i]);
            // Nytt 191128
            $this->form[count($this->form)-$noSubmitButtons + $i]['name'] = $name;
            $this->form[count($this->form)-$noSubmitButtons + $i]['value'] = $submitValues[$i];
            //$this->form[count($this->form)-$noSubmitButtons + $i]['value'] = $value;
            $this->form[count($this->form)-$noSubmitButtons + $i]['else'] = $formVars['else'];
            // echo "<br/>\$submitValues[$i] = " . $submitValues[$i];
        }
        return $this;
    }


    /**
   * Form4::getFormAction()
   *
   *  Get the form action
   *
   * @param string - $name - the name of the input
   *
   * @return string - the value for action attribute as a string
   */
    public function getFormAction($name)
    {
        // echo "\nname inside getFormAction = ";
        // var_dump($name);
        // echo "\nthis->formActions inside getFormAction = ";
        // var_dump($this->formActions);
        // echo "this inside getFormAction= ";
        // var_dump($this);
        // die();
        return $this->formActions[$name];
    }


    /**
   * Form4::setFormAction()
   *
   *  Set the formaction
   *
   * @param string - $name - the name attribute of the input.
   * @param string - $mount- the mounting point
   * @param string - $submount - the "things" that comes below the mounting point, defaults to an empty string
   * @param  array - $params - the parameters to send with the url, defaults to an array.
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

        // echo "\nIN SETFORMACTION IN Form4!";
        // echo "<br/>name =  " . $name;
        // echo "\nthis->formActions[$name] = ";
        // var_dump($this->formActions[$name]);


        // Gör om till en länk
        $this->formActions[$name] = \Anax\View\url($this->formActions[$name]);
    }


     /**
    * Form4::createFormStartTag()
    *
    *  Create an html <form> start tag

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
   * Form4::createInput()
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
    private function createInput($type, $name, $value, $else = "", $label = "")
    {
        //return "<input type='$type' name='$name' value='$value' $else>";
        if ($type !== "hidden" && $type !== "submit") {
            return
            "<label>$label</label>"
            . "<input type='$type' name='$name' value='$value' $else>";
        } else {
            return "<input type='$type' name='$name' value='$value' $else>";
        }
    }


    /**
   * Form4::createInputSubmit()
   *
   * Create an html <input> submit tag
   *
   * @param string - $formaction - the action to be associated with the button
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
   * Form4::createFormEndTag()
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
   * Form4::createForm()
   * Create a form
   *
   * @param string  $game - the name of the "game" to be used in the class name.
   * @param string  $save - the save version i.e get/post/session/session-object
   * @param string  $method - the method used to send data i.e. GET or POST
   * @param boolean $mount - the name of the mount point
   *
   * @return string
   */
    private function createForm($game, $save, $method, $mount)
    {
        $action = isset($action) ? $action : "";

        $formAsString = "";
        $formAsString .= $this->createFormStartTag("form form-" . $game . " form-" . $save . " form-" . strtoLower($this->submitValues[0]), $action, $method);   // Fungerar!
        // echo "\$formAsString = " . $formAsString;
        // echo "hello1";
        $end = count($this->form)-$this->noSubmitButtons;
        for ($i = 0; $i < $end; $i++) {
            // echo "\$this->form[$i]['name'] inside createForm()";
            // var_dump($this->form[$i]["name"]);

            // echo "\$this->\$inputs[$i]['type'] = " . $form[$i]["type"];
            // // Changed 181225 to make the method more general and make it easier to test the class.
            // // if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
            // if (strtoLower($this->submitValues[0]) === "start" && $conditions[$i]) {
            //     $formAsString .=
            //     // "<div class='form-label'>" .
            //     // "<div class='start-input-with-label'><label class='start-label' for={$this->form[$i]['name']}>{$this->form[$i]['name']}";
            //     "<div class='start-input-with-label'><label class='start-label'>{$this->form[$i]['name']}";
            // }
            if (in_array($this->form[$i]["name"], $this->validNames)) {
                if ($this->form[$i]["type"] !== "submit" && isset($this->form[$i]["label"])) {
                    $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"], $this->form[$i]["label"]);
                } else {
                    $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
                }
            } else {
                // $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
                $formAsString .= $this->createInput("hidden", $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
            }
            // // Changed 181225 to make the method more general and make it easier to test the class.
            // // if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
            // if (strtoLower($this->submitValues[0]) === "start" && $conditions[$i]) {
            //     $formAsString .=
            //     "</label></div>";
            // }
        }

        // Set values for the submit buttons

        for ($i = 0; $i < $this->noSubmitButtons; $i++) {
                $index =count($this->form)-$this->noSubmitButtons + $i;
                // echo "<br/>index = ";
                // var_dump($index);
                // $mount = strtoLower($this->submitValues[$i]);
                // $submount = "";
                //$submount = "/" . strtoLower($this->submitValues[$i]);
                // Nytt 191128
                $submount = "/" . lcfirst(str_replace(" ", "", $this->submitValues[$i]));
                $params = [];
                $this->setFormAction(strtoLower($this->submitValues[$i]), $mount, $submount, $params);
                $formAction = $this->getFormAction(strtoLower($this->submitValues[$i]));
                // $formAsString .= $this->createInputSubmit($this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
                // 181229 to get rid of validation error:
                // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\Form4\Form4.php:268       Avoid unused local variables such as '$formAction'.
                // $formAsString .= $this->createInputSubmit($this->formActions[strtoLower($this->submitValues[$i])], $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
                $formAsString .= $this->createInputSubmit($formAction, $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
            // }
        }
        $formAsString .= $this->createFormEndTag();
        // echo "\$formAsString = " . $formAsString;
        return $formAsString;
    }


    /**
   * Form4::displayForm()
   * Display a form
   *
   * @param string  $mount - the mount point
   * @param array  $formAttrs - the form attributes
   *
   * @return string $formAsString - the html form as a string
   */
    public function displayForm($mount, $formAttrs)
    {
        // echo "noSubmitButtons = ";
        // echo $noSubmitButtons;
        $formAsString = $this->createForm($formAttrs['game'], $formAttrs['save'], $formAttrs['method'], $mount);
        return $formAsString;
    }


  /**
   * Form4::populateFormVars4()
   *
   * Populate form variables
   *
   * @param array  $form - the form config array
   * @param object  $request - the request object
   * @param object  $timestamp - the timestamp to be sent, defaults to false
   * @param array  $defaults - the default values to be used as initial values in an input field in the form
   *
   * @return array  $formVars - array containing the formVars
   */
    public static function populateFormVars4($form, $request, $timestamp, $defaults = [])
    {
        // echo "<br/>populateFormVars4()";
        //  foreach($form as $key => $val) {
        $end = count($form);
        for ($key = 0; $key < $end; $key++) {
            // echo "<br/>key = " . $key . "<br/>";
            // echo "form[\$key] = ";
            // var_dump($form[$key]);
            // echo("<br/>");
            $index = $form[$key]['name'];               // Get the value of the name-attribute in form
            // echo "index (\$form[$key]['name'])= " . $index . "<br/>";

            // Test 181029
            // if ($index === "timestamp") {
            // Test 181101
            if ($index === "timestamp" && $timestamp === true) {
                $formVars[$index] = time();
            // If the key "index" exists in the defaults-array then this entry is added to the $formVars-array
            } elseif (array_key_exists($index, $defaults)) {
                $formVars[$index] = $defaults[$index];
            } else {
                // **************************

                $formVars[$index] = $request->getGet($index, null);

                // echo("\$formVars[\$index] =");
                // var_dump($formVars[$index]);
                // echo("<br/>");

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
                    // echo "<br/>index2 in populateFormVars2() in Form4 = ";
                    // echo $index2;       // An empty string?
                    $formVars['else'] = $request->getGet($index2, "");
                }
            } // Test 181029
        }
        // echo("<br/>\$formVars in populateFormVars4 =");
        // var_dump($formVars);
        // echo("<br/>");
        return $formVars;
    }


    /**
     * Form4::createFormArray()
     *
     * Create form array (new method added in kmom03)
     *
     * @param array  $formconf - the form configuration array
     *
     * @return array  $form - an array making up the base for the form.
     */
    public function createFormArray($formconf)
    {
        $form = [];
        foreach ($formconf["inputFields"] as $key => $field) {
            for ($i = 0; $i < $field[2]; $i++) {
                array_push(
                    $form,
                    [
                    "type" => $field[0],
                    "name" => $field[1],
                    "value" => null,
                    "else"  => "",
                    "label" => isset($field[3]) ? $field[3] : "",
                    ]
                );
            }
        }
        return $form;
    }
}
