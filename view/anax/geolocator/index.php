<?php

namespace Anax\View;

/**
 * Geolocator.
 */


 // Show incoming variables and view helper functions
 // echo showEnvironment(get_defined_vars(), get_defined_functions());
 // echo "<br/>\$data = ";
 // var_dump($data);


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

// var_dump($session);

?><h1>Geolocator</h1>

<?php include __DIR__ . "/../v2/navbar/default.php"; ?>
<p>Here you can enter an IP-address to get geographical info.</p>
<p>You will also get the domain name, if available.</p>

<!-- <?= "<br/>\$responseFromIpStack = " . htmlentities($responseFromIpStack); ?> -->


<?php


// echo "<br/>message = " . $message;
// echo "<br/>message in session = " . $session->getOnce("message");

if (isset($responseObject)) {
    // Sanitizing the output
    $responseObject->ip = htmlentities($responseObject->ip);
    $responseObject->type = htmlentities($responseObject->type);
    $responseObject->country_name = htmlentities($responseObject->country_name);
    $responseObject->latitude = htmlentities($responseObject->latitude);
    $responseObject->longitude = htmlentities($responseObject->longitude);
    $responseObject->location->country_flag = htmlentities($responseObject->location->country_flag);

    $result = "IP-address: {$responseObject->ip}<br/>"
    . "IP-type: {$responseObject->type}<br/>"
    . "Country: {$responseObject->country_name}<br/>"
    . "Latitude: <span id='lat'>{$responseObject->latitude}</span><br/>"
    . "Longitude:  <span id='lon'>{$responseObject->longitude}</span><br/>"
    // . "Country Flag:  <span id='lon'><a href='{$responseObject->location->country_flag}'>flag</a></span><br/>";
    // . "Country Flag:  <span class='flag'><img class='flag' src='{$responseObject->location->country_flag}'></img></span><br/>";
    . "Country Flag:  <span><img class='flag' src='{$responseObject->location->country_flag}'></img></span><br/>";

    $index = 0;
    $title = "Geographical information";

    echo \Anna\Result\Result2::displayResult($result, $index, $title);
    // echo "<br/>\$responseFromIpStack = " . $responseFromIpStack;
}

?>

<div class="developers">
    <h2> For developers: </h2>
        <p>The server of this web site provides a REST API for the Geo-locator utility, please visit the API page to get more information.</p>
</div>
