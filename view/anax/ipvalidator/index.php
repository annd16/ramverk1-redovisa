<?php

namespace Anax\View;

/**
 * Style chooser.
 */

// Show incoming variables and view helper functions
echo showEnvironment(get_defined_vars(), get_defined_functions());

// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!


var_dump($session)

?><h1>IP validator</h1>
<p>Here you can enter an IP-adress to check if it is a valid IPv4 or IPv6 adress.</p>
<p>You will also get the domain name, if available.</p>


<div class="developers">
    <h2> For developers: </h2>
        <p>The server of this web site provides a REST API for this Ip-checking utility.</p>
        <p>Below will follow some examples on how to use it:</p>

        <ul>
        <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49">35.158.84.49, (a valid IPv4 address)</a></li>
        <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.247.130">144.63.247.130, (another valid IPv4 address)</a></li>

        <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/2001:cdba::3257:9652">2001:cdba::3257:9652, (a valid IPv6 address)</a></li>
        <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.2">144.63.2, (not a valid IP address)</a></li>
        </ul>



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
