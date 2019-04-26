<?php

/**
 * A module for Result class.
 *
 * This is the module containing the Result class.
 *
 * @author  Anna
 */


namespace Anna\Result;

/**
 * A Result class that handles the result.
 */
class Result2
{

    /**********
     * Properties
     **********/

    //  $result = "";

    /**********
     * Methods
     **********/

  //    // private static function caseAnException($resCode, $dicegame = null, $extraMessage = "")
  //    // 181105: To avoid validation error: "Avoid unused parameters such as '$resCode'"
  //    // private static function caseAnException($extraMessage)
  //    // 181105: To avoid (faulty) validation error: "Avoid unused private methods such as 'caseStatistics'".
  //
  //     /**
  //    * Result::caseAnException()
  //    *
  //    * Set result when an exception has occured
  //    *
  //    * @param string $extraMessage - the message that should be displayed
  //    *
  //    * @return string - $result - the result as an HTML string
  //    */
  //   protected static function caseAnException($extraMessage)
  //   {
  //       $result = "<h3>Caught exception:</h3>" . "<h4>" . $extraMessage . "</h4>" . "<h4 class='guessesleft'>Number of guesses left: " . ($noGuessesLeft > 0 ?  htmlentities($noGuessesLeft) : "none.") . "</h4>";
  //       return $result;
  //   }
  //
  //
  //  /**
  // * Result::setClassOnH3()
  // *
  // * Set class on h3 element depending on what value $comparison has
  // *
  // * @param string - $comparison - the result as string from the comparison i.e. "too low"/"too high"/"correct"
  // *
  // * @return string - $LastWord - the extracted string that should be used as the class
  // */
  //   public static function setClassOnH3($comparison)
  //   {
  //       $words=[];
  //       $words = explode(' ', $comparison);
  //       $lastWord = array_pop($words);
  //       $lastWord = substr($lastWord, 0, -1);
  //       return $lastWord;
  //   }


    /**
   * Result::displayResult()
   *
   * Display the result div
   *
   * @param string - $result - the result as a html-string
   * @param string? - $index - the index of the result (to be used in the naming of the div class).
   * @param string - $title - the title
   *
   * @return void
   */
    public static function displayResult($result, $index, $title)
    {

        return "<div class='result' id='result{$index}'><h3>{$title}</h3>" . $result . "</div>";
    }
}
