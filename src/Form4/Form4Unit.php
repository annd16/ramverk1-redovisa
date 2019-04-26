<?php

/**
 * A module for Form class.
 *
 * This is the module containing the Form4Unit class.
 *
 * @author  Anna
 */


namespace Anna\Form4;

/**
 * A Form4Unit class that handles the html forms
 *
 */
class Form4Unit extends Form4
{

    /**********
     * Properties
     **********/

    //  /**
    //   * @var array $validNames - valid names for the name attribute.
    //   */
    // private $validNames = [];
    //
    // /**
    // * @var integer $noSubmitButtons - noOfSubmitButtons.
    //  */
    // private $noSubmitButtons;

    // /**
    // * @var array $vformActions - the form actions for the submit buttons.
    // */
    // // private $formActions = [];
    // public $formActions = [];


    /**********
     * Methods
     **********/

   //  /**
   //  * Constructor.
   //  *
   //  * @param array $formVars - the form variables.
   //  * @param array  $form - the configuration array for the form.
   //  * @param array $submitValues - array containing the submit values.
   //  * @param array $validNames - valid values for the name attribute.
   //  * @param integer $noSubmitButtons - number of submit buttons, defaults to 1.
   //  *
   //  * @return self
   //  */
   //  public function __construct($formVars, $form, $submitValues, $validNames, $noSubmitButtons = 1)
   //  {
   //      // $params = func_get_args();
   //      // $noParams = func_num_args();
   //      // echo "\$params  =";
   //      // var_dump($params);
   //      // echo "\$noParams  =";
   //      // var_dump($noParams);
   //
   //      $this->form = $form;
   //      $this->formVars = $formVars;
   //      $this->submitValues = $submitValues;
   //
   //      // New 181101
   //      $this->validNames = $validNames;
   //
   //      $this->noSubmitButtons = $noSubmitButtons;
   //
   //      // Tillagt 181220
   //      $this->formActions = [];
   //
   //      // För att sätta värdena på variablerna
   //
   //      $i = 0;
   //      while ($this->form[$i]['type'] !== "submit") {
   //          // echo "<br/>\$this->form[$i]['type'] in constructor = ";
   //          // var_dump($this->form[$i]['type']);
   //          // echo "<br/>\$formVars[\$this->form[$i]['name'] = ";
   //          // var_dump($formVars[$this->form[$i]['name']]);
   //          $this->form[$i]['value'] = $formVars[$this->form[$i]['name']];
   //          $i++;
   //      }
   //
   //      // Set values for the submit buttons
   //
   //      for ($i = 0; $i < $noSubmitButtons; $i++) {
   //          $this->form[count($this->form)-$noSubmitButtons + $i]['name'] = strtoLower($submitValues[$i]);
   //          $this->form[count($this->form)-$noSubmitButtons + $i]['value'] = $submitValues[$i];
   //          $this->form[count($this->form)-$noSubmitButtons + $i]['else'] = $formVars['else'];
   //          echo "<br/>\$submitValues[$i] = " . $submitValues[$i];
   //      }
   //  }
   //
   //  /**
   // * Form::getAction()
   // *
   // *  Get the action
   // *
   // * @param string - $type - the type of the input
   // * @param string - $name - the name of the input
   // * @param mixed - $value - the value
   // * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   // *
   // * @return string - the value for action attribute as a string
   // */
   //  public function getFormAction($name)
   //  {
   //      return $this->formActions[$name];
   //  }

  //  /**
  // * Form4::getFormAction()
  // *
  // *  Get the form action
  // *
  // * @param string - $name - the name of the input
  // *
  // * @return string - the value for action attribute as a string
  // */
  //  public function getFormAction($name)
  //  {
  //      echo "\nname inside getFormAction = ";
  //      var_dump($name);
  //      echo "\nthis->formActions inside getFormAction = ";
  //      var_dump($this->formActions);
  //      // echo "this inside getFormAction= ";
  //      // var_dump($this);
  //      // die();
  //      return $this->formActions[$name];
  //  // }


   /**
  * Form4Unit::setFormAction()
  *
  *  Set the formaction
  *
  * @param string - $name - the name attribute of the input.
  * @param string - $mount- the mounting point
  * @param string - $dummy - just a dummy parameter to make sure this setFormAction-method is called
  * @param string - $submount - the "things" that comes below the mounting point, defaults to an empty string
  * @param  array - $params - the parameters to send with the url, defaults to an array.
  *
  * @return string - the value for action attribute as a string
  */
    public function setFormAction($name, $mount, $dummy, $submount = "", $params = [])
    {
        // $submount måste ha formen /xxxx dvs börja med en slash.
        // echo "<br/>name =  " . $name;
        // die();
        $this->formActions[$name] = $mount . $submount;
        if (count($params) > 0) {
            // echo "params = ";
            // var_dump($params);

            foreach ($params as $value) {
                $this->formActions[$name] .= "/" . $value;
            }
        }

        echo "\nIN SETFORMACTION!";
        echo "<br/>name =  " . $name;
        echo "\nthis->formActions[$name] = ";
        var_dump($this->formActions[$name]);

        // Gör om till en länk
        $this->formActions[$name] = \Anax\View\url($this->formActions[$name]);
        echo "\nIN SETFORMACTION!";
        echo "<br/>name =  " . $name;
        echo "\nthis->formActions[$name] = ";
        var_dump($this->formActions[$name]);
        // echo "this inside setFormAction= ";
        // var_dump($this);
        // echo $dummy;
        // die();
    }


   //   /**
   //  * Form::createFormStartTag()
   //  *
   //  * Create an html <form> start tag
   //
   //  * @param string - $class - the class as a string
   //  * @param string - $action - the action/route as a string
   //  * @param string - $method - the method used (i.e. get/post) as a string
   //  *
   //  * @return string - the start tag as a string
   //  */
   //  private function createFormStartTag($class, $action, $method)
   //  {
   //      return "<form class='$class' action='$action' method='$method'>";
   //  }
   //
   //
   //
   //  /**
   // * Form::createInput()
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
   //  private function createInput($type, $name, $value, $else = "")
   //  {
   //      return "<input type='$type' name='$name' value='$value' $else>";
   //  }
   //
   //
   //  /**
   // * Form::createInputSubmit()
   // *
   // * Create an html <input> submit tag
   // *
   // * @param string - $formaction - the action to be associated with the button
   // * @param string - $name - the name of the input
   // * @param string - $value - the value
   // * @param  string - $else - "disabled" if $done is true, else an epmty string "", defaults to an empty string
   // *
   // * @return string - the input tag as a string
   // */
   //  private function createInputSubmit($formaction, $name, $value, $else = "")
   //  {
   //      return "<input type='submit' class='submit' name='$name' formaction='$formaction' value='$value' $else>";
   //  }
   //
   //  /**
   // * Form::createFormEndTag()
   // *
   // * Create an html <form> end tag
   // *
   // * @return string - the start tag as a string
   // */
   //  private function createFormEndTag()
   //  {
   //      return "</form>";
   //  }

   //  /**
   // * Form4Unit::createForm()
   // * Create a form
   // *
   // * @param string  $game - the name of the "game" to be used in the class name.
   // * @param string  $save - the save version i.e get/post/session/session-object
   // * @param string  $method - the method used to send data i.e. GET or POST
   // * @param boolean $mount - the name of the mount point
   // *
   // * @return string
   // */
   //  public function createForm($game, $save, $method, $mount)
   //  {
   //      $action = isset($action) ? $action : "";
   //
   //      $formAsString = "";
   //      $formAsString .= $this->createFormStartTag("form form-" . $game . " form-" . $save . " form-" . strtoLower($this->submitValues[0]), $action, $method);   // Fungerar!
   //      // echo "\$formAsString = " . $formAsString;
   //      // echo "hello1";
   //      for ($i = 0; $i < count($this->form)-$this->noSubmitButtons; $i++) {
   //          // echo "\$this->form[$i]['name'] inside createForm()";
   //          // var_dump($this->form[$i]["name"]);
   //
   //          // echo "\$this->\$inputs[$i]['type'] = " . $form[$i]["type"];
   //          // Changed 181225 to make the method more general and make it easier to test the class.
   //          // if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
   //          // if (strtoLower($this->submitValues[0]) === "start" && $conditions[$i]) {
   //          //     $formAsString .=
   //          //     // "<div class='form-label'>" .
   //          //     // "<div class='start-input-with-label'><label class='start-label' for={$this->form[$i]['name']}>{$this->form[$i]['name']}";
   //          //     "<div class='start-input-with-label'><label class='start-label'>{$this->form[$i]['name']}";
   //          // }
   //          if (in_array($this->form[$i]["name"], $this->validNames)) {
   //              $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
   //          } else {
   //              // $formAsString .= $this->createInput($this->form[$i]["type"], $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
   //              $formAsString .= $this->createInput("hidden", $this->form[$i]["name"], $this->form[$i]["value"], $this->form[$i]["else"]);
   //          }
   //          // // Changed 181225 to make the method more general and make it easier to test the class.
   //          // // if (strtoLower($this->submitValues[0]) === "start" && $this->form[$i]['type'] === "number") {
   //          // if (strtoLower($this->submitValues[0]) === "start" && $conditions[$i]) {
   //          //     $formAsString .=
   //          //     "</label></div>";
   //          // }
   //      }
   //
   //      // Set values for the submit buttons
   //
   //      for ($i = 0; $i < $this->noSubmitButtons; $i++) {
   //              $index =count($this->form)-$this->noSubmitButtons + $i;
   //              // echo "<br/>index = ";
   //              // var_dump($index);
   //              // $mount = strtoLower($this->submitValues[$i]);
   //              // $submount = "";
   //              $submount = "/" . strtoLower($this->submitValues[$i]);
   //              $params = [];
   //              $this->setFormAction(strtoLower($this->submitValues[$i]), $mount, $submount, $params);
   //              $formAction = $this->getFormAction(strtoLower($this->submitValues[$i]));
   //              // $formAsString .= $this->createInputSubmit($this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
   //              // 181229 to get rid of validation error:
   //              // C:\Users\Anna\dbwebb-kurser\ramverk1\me\redovisa\src\Form4\Form4.php:268       Avoid unused local variables such as '$formAction'.
   //              // $formAsString .= $this->createInputSubmit($this->formActions[strtoLower($this->submitValues[$i])], $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
   //              $formAsString .= $this->createInputSubmit($formAction, $this->form[$index]["name"], $this->form[$index]["value"], $this->form[$index]["else"]);
   //          // }
   //      }
   //      $formAsString .= $this->createFormEndTag();
   //      // echo "\$formAsString = " . $formAsString;
   //      return $formAsString;
   //  }

   //  /**
   // * Form4::displayForm()
   // * Display a form
   // *
   // * @param string  $mount - the mount point
   // * @param array  $formAttrs - the form attributes
   // * @param boolean $condition - the condition to be met if submitValues[0] = "Start";
   // *
   // * @return string $formAsString - the html form as a string
   // */
   //  public function displayForm($mount, $formAttrs)
   //  {
   //      // echo "noSubmitButtons = ";
   //      // echo $noSubmitButtons;
   //      $formAsString = $this->createForm($formAttrs['game'], $formAttrs['save'], $formAttrs['method'], $mount);
   //      return $formAsString;
   //  }

   //  /**
   // * Form4::populateFormVars()
   // * Populate form variables
   // *
   // * @param array  $form - the form config array
   // * @param object  $app - the app object
   // *
   // * @return array  $formVars - array containing the formVars
   // */
   //  public static function populateFormVars($form, $app)
   //  {
   //      // Populate formVars:
   //      for ($key = 0; $key < count($form); $key++) {
   //          // echo "key = " . $key . "<br/>";
   //          // echo "form[\$key] = ";
   //          // var_dump($form[$key]);
   //          // echo("<br/>");
   //          $index = $form[$key]['name'];               // Get the value of the name-attribute in form
   //
   //          // Test 181029
   //          if ($index === "timestamp") {
   //              $formVars[$index] = time();
   //          } else {
   //              // **************************
   //
   //
   //              // echo "index = " . $index . "<br/>";
   //              // $formVars[$index] = $app->request->getGet($index, $default = null);
   //              $formVars[$index] = $app->request->getGet($index, null);            // Test 181029
   //              if (is_numeric($formVars[$index])) {
   //                  // echo("\$formVars[\$index] =");
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //                  $formVars[$index] = intval($formVars[$index]);
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //              }
   //              if ($form[$key]['type'] === "submit") {
   //                  $index2 = $form[$key]['else'];
   //                  // Test 181005
   //                  if ($formVars[$index] !== null || !empty($formVars[$index])) {
   //                      $formVars['else'] = $app->request->getGet($index2, "");
   //                  } elseif ($formVars[$index] === null) {
   //                      $formVars['else'] = $app->request->getGet($index2, "disabled");
   //                  }
   //              }
   //          } // Test 181029
   //      }
   //      return $formVars;
   //  }
   //
   //
   //  /**
   // * Form4::populateFormVars2()
   // * Populate form variables
   // *
   // * @param array  $form - the form config array
   // * @param object  $app - the app object
   // * @param object  $timestamp - the timestamp to be sent, defaults to false
   // *
   // * @return array  $formVars - array containing the formVars
   // */
   //  public static function populateFormVars2($form, $app, $timestamp = false)
   //  {
   //      echo "<br/>populateFormVars2()";
   //      //  foreach($form as $key => $val) {
   //      for ($key = 0; $key < count($form); $key++) {
   //          // echo "<br/>key = " . $key . "<br/>";
   //          // echo "form[\$key] = ";
   //          // var_dump($form[$key]);
   //          // echo("<br/>");
   //          $index = $form[$key]['name'];               // Get the value of the name-attribute in form
   //          echo "index (\$form[$key]['name'])= " . $index . "<br/>";
   //
   //          // Test 181029
   //          // if ($index === "timestamp") {
   //          // Test 181101
   //          if ($index === "timestamp" && $timestamp === true) {
   //              $formVars[$index] = time();
   //          } else {
   //              // **************************
   //
   //              $formVars[$index] = $app->request->getGet($index, null);
   //
   //              // echo("\$formVars[\$index] =");
   //              // var_dump($formVars[$index]);
   //              // echo("<br/>");
   //
   //              if (is_numeric($formVars[$index])) {
   //                  // echo("\$formVars[\$index] =");
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //                  $formVars[$index] = intval($formVars[$index]);
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //              }
   //              if ($form[$key]['type'] === "submit") {
   //                  $index2 = $form[$key]['else'];
   //                  // echo "<br/>index2 in populateFormVars2() in Form4 = ";
   //                  // echo $index2;       // An empty string?
   //                  $formVars['else'] = $app->request->getGet($index2, "");
   //              }
   //          } // Test 181029
   //      }
   //      return $formVars;
   //  }



   //  /**
   // * Form4::populateFormVars4()
   // * Populate form variables
   // *
   // * @param array  $form - the form config array
   // * @param object  $request - the request object
   // * @param object  $timestamp - the timestamp to be sent, defaults to false
   // * @param array  $defaults - the default values to be used as initial values in an input field in the form
   // *
   // * @return array  $formVars - array containing the formVars
   // */
   //  public static function populateFormVars4($form, $request, $timestamp, $defaults = [])
   //  {
   //      echo "<br/>populateFormVars4()";
   //      //  foreach($form as $key => $val) {
   //      for ($key = 0; $key < count($form); $key++) {
   //          // echo "<br/>key = " . $key . "<br/>";
   //          // echo "form[\$key] = ";
   //          // var_dump($form[$key]);
   //          // echo("<br/>");
   //          $index = $form[$key]['name'];               // Get the value of the name-attribute in form
   //          // echo "index (\$form[$key]['name'])= " . $index . "<br/>";
   //
   //          // Test 181029
   //          // if ($index === "timestamp") {
   //          // Test 181101
   //          if ($index === "timestamp" && $timestamp === true) {
   //              $formVars[$index] = time();
   //          // If the key "index" exists in the defaults-array then this entry is added to the $formVars-array
   //          } elseif (array_key_exists($index, $defaults)) {
   //              $formVars[$index] = $defaults[$index];
   //          } else {
   //              // **************************
   //
   //              $formVars[$index] = $request->getGet($index, null);
   //
   //              // echo("\$formVars[\$index] =");
   //              // var_dump($formVars[$index]);
   //              // echo("<br/>");
   //
   //              if (is_numeric($formVars[$index])) {
   //                  // echo("\$formVars[\$index] =");
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //                  $formVars[$index] = intval($formVars[$index]);
   //                  // var_dump($formVars[$index]);
   //                  // echo("<br/>");
   //              }
   //              if ($form[$key]['type'] === "submit") {
   //                  $index2 = $form[$key]['else'];
   //                  // echo "<br/>index2 in populateFormVars2() in Form4 = ";
   //                  // echo $index2;       // An empty string?
   //                  $formVars['else'] = $request->getGet($index2, "");
   //              }
   //          } // Test 181029
   //      }
   //      // echo("<br/>\$formVars in populateFormVars4 =");
   //      // var_dump($formVars);
   //      // echo("<br/>");
   //      return $formVars;
   //  }
}
