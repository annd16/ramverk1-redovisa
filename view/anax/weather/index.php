<?php

namespace Anax\View;

/**
 * Style chooser.
 */


 // Show incoming variables and view helper functions
 echo showEnvironment(get_defined_vars(), get_defined_functions());
 echo "<br/>\$data = ";
 var_dump($data);


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

var_dump($session)

?><h1>Weather</h1>

<?php include __DIR__ . "/../v2/navbar/default.php"; ?>
<p>Here you can enter an IP-adress, a position (latitud,longitud) OR a name for the geographical place (in the near future I hope...) you would like to get weather information for.</p>
<p>You will also get the domain name, if available.</p>

<!-- <?= "<br/>\$responseFromDarkSky = " . $responseFromDarkSky; ?> -->

<?php

echo "<br/>message = " . $message;
echo "<br/>message in session = " . $session->getOnce("message");

$result = "";
if (isset($responseObjects) && count($responseObjects) > 0) {
    foreach ($responseObjects as $key => $responseObject) {
        if ($responseObject !== null) {
            echo "<br/>responseObject in weather/index.php = ";
            var_dump($responseObject);
            $date = date('Y-M-d h:m', $responseObject->currently->time);
            $result .= "Position: <span id='lat'>{$responseObject->latitude}</span>, <span id='lon'>{$responseObject->longitude}</span><br/>"
            . "Timezone: {$responseObject->timezone}<br/>"
            . "Time: $date<br/>"
            . "Summary:  {$responseObject->currently->summary}<br/><br/>";
        }
    }

    $index = 0;
    $title = "Weather information";

    echo \Anna\Result\Result::displayResult($result, $index, $title);
    // echo "<br/>\$responseFromIpStack = " . $responseFromIpStack;
}

?>

<div class="developers">
    <h2> For developers: </h2>
        <p>The server of this web site provides a REST API for the Weather utility, please visit the API page to get more information.</p>

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
