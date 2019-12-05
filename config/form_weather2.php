<?php
/**
 * Supply data for the form as an array.
 */
return [
    "inputFields" => [["text", "ipOrPos", 1, "IP-address or geographical position"], ["hidden", "timestamp", 1], ["submit", "", 5]],
    "buttonNames" => ["One Week Forecast", "Thirty Days History", "Json One Week Forecast", "Json Thirty Days History", "GetMyIp"]      // Length of array must be equal to the number in the submit array above
];
