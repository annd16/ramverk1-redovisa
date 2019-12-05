<?php

namespace Anax\View;

/**
 * Weather view.
 */

 // The view expects an array of objects to loop through

 // Show incoming variables and view helper functions
 //echo showEnvironment(get_defined_vars(), get_defined_functions());
 // echo "<br/>\$data = ";
 // var_dump($data);


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

// var_dump($session)

?><h1>Weather</h1>

<?php include __DIR__ . "/../v2/navbar/default.php"; ?>
<p>Get the weather information for the past 30 days or for the coming 7 days for a certain place.</p>
<p>Enter an IP-adress (this can be either an Ipv4* address or an Ipv6** address) or a geographical position (latitude,longitude) OR a name for the geographical place*** you would like to get weather information for.</p>
<p>*   4 groups of decimal digits separated by dots, i.e. 198.51.100.1.</p>
<p>**  8 groups of hexadecimal digits separated by colons, i.e. 2001:db8:1f70:999:de8:7648:3a49:6e8</p>
<p>*** To be implemented soon I hope...</p>



<!-- <?= "<br/>\$responseFromIpStack = ";
var_dump($responseFromIpStack);?> -->
<!-- <?= "<br/>\$responseFromIpStackLat = ";
var_dump($responseFromIpStackLat);?>
<?= "<br/>\$responseFromIpStackLong = ";
var_dump($responseFromIpStackLong);?>
<?= "<br/>\$responseFromDarkSky = ";
var_dump($responseFromDarkSky);?> -->

<!-- <?= "\Anna\Weather\Weather::\$message = " . \Anna\Weather\Weather::$message; ?> -->
<?= "\Anna\Weather\Weather::\$message = " . $message2 ?>

<?php

// echo "<br/>message = " . $message;
// echo "<br/>message in session = " . $session->getOnce("message");

    $index = 0;
    $title = "Weather information";

if (isset($result)) {
    echo \Anna\Result\Result2::displayResult($result, $index, $title);
}
    // echo "<br/>\$responseFromIpStack = " . $responseFromIpStack;
// }

?>

<div class="developers">
    <h2> For developers: </h2>
        <p>The server of this web site provides a REST API for the Weather utility, please visit the API page to get more information.</p>

</div>

<!-- <?php $request = $di->get("request");



?><h1>Request</h1>

<p>Here are details on the current request.</p>

<table>
    <tr>
        <th>Method</th>
        <th>Result</th>
    </tr>
    <tr>
        <td><code>getCurrentUrl()</code></td>
        <td><code><?= $request->getCurrentUrl() ?></code></td>
    </tr>
    <tr>
        <td><code>getMethod()</code></td>
        <td><code><?= $request->getMethod() ?></code></td>
    </tr>
    <tr>
        <td><code>getSiteUrl()</code></td>
        <td><code><?= $request->getSiteUrl() ?></code></td>
    </tr>
    <tr>
        <td><code>getBaseUrl()</code></td>
        <td><code><?= $request->getBaseUrl() ?></code></td>
    </tr>
    <tr>
        <td><code>getScriptName()</code></td>
        <td><code><?= $request->getScriptName() ?></code></td>
    </tr>
    <tr>
        <td><code>getRoute()</code></td>
        <td><code><?= $request->getRoute() ?></code></td>
    </tr>
    <tr>
        <td><code>getRouteParts()</code></td>
        <td><code><?= "[ " . implode(", ", $request->getRouteParts()) . " ]" ?></code></td>
    </tr>
</table> -->
