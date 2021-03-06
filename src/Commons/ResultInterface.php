<?php

/**
 * A module for CurlInterface class.
 *
 * This is the module containing the IpValidatorInterface class for IP-analyzations.
 *
 * @author  Anna
 */

namespace Anna\Commons;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use IpValidatorInterface;

/**
 * IpValidatorInterface
 *
 * Ip-analyzation.
 */
interface ResultInterface
{
    // use ContainerInjectableTrait;

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
    public static function displayResult($result, $index, $title);
}
