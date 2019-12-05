<?php

namespace Anax\View;

/**
 * Weather view.
 */


 // Show incoming variables and view helper functions
 echo showEnvironment(get_defined_vars(), get_defined_functions());
 // echo "<br/>\$data = ";
 // var_dump($data);


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

var_dump($session)

?><h1>Curl</h1>

<?php include __DIR__ . "/../v2/navbar/default.php"; ?>
<p>Here you can get the program tableau for 'Sveriges Radio' for one day (single curl) or three days (multiple curls).</p>


<?php

//echo "<br/>message = " . $message;
//echo "<br/>message in session = " . $session->getOnce("message");

$result = "";
if (isset($responseObjects) && count($responseObjects) > 0) {
    foreach ($responseObjects as $key => $responseObj) {
        if ($responseObj !== null) {
            echo "<br/><br/>ResponseObj {$key} in curl/index.php = ";

            var_dump($responseObj);
            //var_dump(json_decode($responseObj["data"], false));
            $responseObj["data"] =  json_decode($responseObj["data"], false);
            $date = substr($responseObj["info"]["url"], -10);
            $result .= "<div id='lat'><h3>{$key} {$date}</h3></div>";
            //$date = date('Y-M-d h:m', $responseObj->daily->data[0]->time);
            foreach ($responseObj["data"]->schedule as $key => $program) {
                $program->starttimeutc = str_replace('/', '', $program->starttimeutc);
                $program->starttimeutc = str_replace('Date(', '', $program->starttimeutc);
                $program->starttimeutc = str_replace(')', '', $program->starttimeutc);
                // var_dump($program->starttimeutc);
                // echo "\$program->starttimeutc = ";
                // var_dump((int)$program->starttimeutc/1000);
                // //$program->starttimeutc = substr($program->starttimeutc, 0, -2);
                // //echo "\$program->starttimeutc = ";
                // //var_dump((int)$program->starttimeutc);
                // echo "time() = ";
                // var_dump(time());
                //$date = new \DateTime();
                //$date->setTimestamp((int)$program->starttimeutc);
                //$datetime = new \DateTime($program->starttimeutc);
                //var_dump($datetime);
                //echo(date_default_timezone_get());
                //$date = date("Y-m-d", (int)$program->starttimeutc/1000);
                $program->starttimeutc = date("Y-m-d h:i:s", (int)$program->starttimeutc/1000);
                $result .= "<span id='lat'>{$program->title}</span>, <span id='lon'>{$program->starttimeutc}</span><br/>";
                //. "Time: $date<br/>"
                //. "Summary:  {$responseObj->daily->data[0]->summary}<br/><br/>";
            }
        }
    }

    $index = 0;
    $title = "SR tableau";

    echo \Anna\Result\Result2::displayResult($result, $index, $title);
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
