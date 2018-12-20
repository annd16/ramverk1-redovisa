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
class Result
{

    /**********
     * Properties
     **********/

    //  $result = "";

    /**********
     * Methods
     **********/

     // private static function caseAnException($resCode, $dicegame = null, $extraMessage = "")
     // 181105: To avoid validation error: "Avoid unused parameters such as '$resCode'"
     // private static function caseAnException($extraMessage)
     // 181105: To avoid (faulty) validation error: "Avoid unused private methods such as 'caseStatistics'".

      /**
     * Result::caseAnException()
     *
     * Set result when an exception has occured
     *
     * @param string $extraMessage - the message that should be displayed
     *
     * @return string - $result - the result as an HTML string
     */
    protected static function caseAnException($extraMessage)
    {
        $result = "<h3>Caught exception:</h3>" . "<h4>" . $extraMessage . "</h4>" . "<h4 class='guessesleft'>Number of guesses left: " . ($noGuessesLeft > 0 ?  htmlentities($noGuessesLeft) : "none.") . "</h4>";
        return $result;
    }

    // private static function caseStatistics($resCode, $dicegame = null, $extraMessage = "")
    // 181105: To avoid (faulty) validation error: "Avoid unused private methods such as 'caseStatistics'".

     /**
    * Result::caseStatistics()
    *
    * Get different result dependant on result code.
    *
    * @param object $dicegame - the dicegame object, defaults to null
    *
    * @return string - $result - the result as an HTML string
    */
    protected static function caseStatistics($dicegame)
    {
        $result = "<h3>Statistics</h3>";
        $playerId = $dicegame->getPlayer();
        $player = $dicegame->getPlayerObj($playerId);
        // $playerName = $dicegame->getPlayerName();
        $playerName = $player->getPlayerName();
        $offsets = $player->getDicehand()->getOffsets();
        $round =  $dicegame->getDiceround($playerId)->getValue("round");
        $index = $round > 1 ? $round-1 : 0;
        // $result .= "<h4 class='guessesleft'>Player: " . $playerId . "</h4>";
        $result .= "<h4 class='guessesleft'>Player: " . $playerName . "</h4>";
        $result .= "<h3>Here are the details of last roll(s):</h3>" . "<h4>" . "<pre>" . $player->getDicehand()->detailsOfLastRolls() . "</pre>"  . "</h4>";
        // $result .= "<h3>Total sum this round:</h3>" . "<h4>" . "<pre>" . $player->getDicehand()->calculateAndGetSum() . "</pre>"  . "</h4>";
        $result .= "<h3>Total sum this round:</h3>" . "<h4>" . "<pre>" . array_sum($player->getDicehand()->getSumsThisRound2($offsets[$index])) . "</pre>"  . "</h4>";
        return $result;
    }

   /**
  * Result::getResult()
  *
  * Get different result dependant on result code.
  *
  * @param string $resCode - the result code
  * @param object $dicegame - the dicegame object, defaults to null
  * @param string $extraMessage - any extra message, defaults to an empty string
  *
  * @return string - $result - the result as an HTML string
  */
    public static function getResult($resCode, $dicegame = null, $extraMessage = "")
    {
        switch ($resCode) {
            case "start":
                $result = "<h3 class='centered'>NEW GAME STARTED</h3>";
                break;
            case "roll":
                $result = "<h3>Alea Acta Est!</h3>";
                $playerId = $dicegame->getPlayer();
                $result .= $dicegame->detailsOfPlayersRollGraphic($playerId);
                break;
            case "hold":
            case "busted":
                $result = "<h3>" . rtrim(strtoupper($resCode), "C") . "!</h3>";
                $playerId = $dicegame->getPlayer();
                $result .= $dicegame->detailsOfPlayersRollGraphic($playerId);
                break;
            case "status":
                $result = "<h3>Status</h3>";
                $player = $dicegame->getPlayerObj($dicegame->getValue("currentPlayerId"));
                $result .= "<h3>Here are selected details of the game:</h3>" . "<h4>" . "<pre>" . $dicegame->selectedDetailsOfObject($dicegame) . "</pre>"  . "</h4>";
                // $result .= "<h3>Total sum this round:</h3>" . "<h4>" . "<pre>" . $player->getDicehand()->calculateAndGetSum() . "</pre>"  . "</h4>";
                break;
            case "statistics":
                // $result = \Anna\Result2\Result2::caseStatistics();
                // 181105: To avoid validation error "Avoid using static access to class '\Anna\Result2\Result2'"
                $result = $self::caseStatistics($dicegame);
                break;
            case "winner":
                $result = "<h3>" . rtrim(strtoupper($resCode), "C") . "!</h3>";
                $playerId = $dicegame->getPlayer();
                $player = $dicegame->getPlayerObj($dicegame->getValue("currentPlayerId"));
                $result .= "<h3>Total noRolls:<br/>" . $dicegame->getDiceround($playerId)->getNoRollsInThisRound() . "</h3>";
                $result .= "<h3>Total sum:<br/>" . $dicegame->getPlayerObj($playerId)->getDicehand()->calculateAndGetSum2()  . "</h3>";
                break;
            case "anException":
                // $result = \Anna\Result2\Result2::caseAnException();
                // 181105: To avoid validation error "Avoid using static access to class '\Anna\Result2\Result2'"
                $result = $self::caseAnException($extraMessage);
                break;
            default:
                $result = "<h3>Nothing to display yet</h3>";
                break;
        }
        return $result;
    }


   /**
  * Result::setClassOnH3()
  *
  * Set class on h3 element depending on what value $comparison has
  *
  * @param string - $comparison - the result as string from the comparison i.e. "too low"/"too high"/"correct"
  *
  * @return string - $LastWord - the extracted string that should be used as the class
  */
    public static function setClassOnH3($comparison)
    {
        $words=[];
        $words = explode(' ', $comparison);
        $lastWord = array_pop($words);
        $lastWord = substr($lastWord, 0, -1);
        return $lastWord;
    }


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