<?php

namespace Anax\View;

/**
 * API view.
 */

// Show incoming variables and view helper functions


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

var_dump($session)

?><h1>API</h1>

<div class="developers">
    <h2> For developers: </h2>
            <p>The server of this web site provides a REST API for the IP-checker, geolocator and the weather utility.</p>

        <p>For a particular input sequence the following will be checked:
            *if it is a valid IPv4 or IPv6 address.
            *if so it will also check if the adress is within a private
            and/or reserved range.

The following ranges are considered to be private:
IPv4: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
IPv6: adresses starting with FD or FC.

The following ranges are considered to be reserved:
IPv4: 0.0.0.0/8, 169.254.0.0/16, 127.0.0.0/8 and 240.0.0.0/4.
IPv6: ::1/128, ::/128, ::ffff:0:0/96 and fe80::/10.</p>

<p>Inside 'ipvalidator/index.php'</p>
<code><?= "<br/>The siteUrl = " . $request->getSiteUrl(); ?></code>
<code><?= "<br/>The baseUrl = " . $request->getBaseUrl(); ?></code>
<code><?= "<br/>The currentUrl = " . $request->getCurrentUrl(); ?></code>

    <h3> About the API </h3>


    <!-- <p>The base url to use:</p>

    <a href='http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdocs'></a> -->



    <p>The base url to use:</p>

    <code><?= $request->getBaseUrl(); ?></code>

    <p>The endpoint for the IP-validation is:</p>

    <!-- http://www.student.bth.se/~annd16/dbwebb-kurser/ramverk1/me/redovisa/htdoc/ip/json/[address] -->

    <code>GET /ip/json/[address]</code>

    <p>will give you the validation data for the (assumed) IP address, <em>adress</em>.</p>

    <p>The result will be in JSON format, and look something like this:</p>

    <pre>

        {
            "ip": "35.158.84.49",
            "version": "IPv4",
            "type": "not private",
            "host": "ec2-35-158-84-49.eu-central-1.compute.amazonaws.com",
            "message": "35.158.84.49 is a valid IPv4 address"
        }
    </pre>

    <p>There is also a possibility to check several IP addresses at a time:</p>

        <code>GET /ip/json/[address1]/&ltaddress2&gt/&ltaddress3&gt...</code>


    <p>Below are some examples:</p>

    <ul>
    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49">91.192.30.117   - a valid public IPv4 address with known domain.</a></li>
    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.247.130">144.63.247.130    - a valid public IPv4 address without known domain.</a></li>

    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/2001:cdba::3257:9652">2001:cdba::3257:9652    - a valid IPv6 address without known domain</a></li>
    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/fd12:3456:789a:1::1">fd12:3456:789a:1::1    - a valid IPv6 private address</a></li>
    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/0.2.1.8">0.2.1.8    - a valid IPv4 reserved address</a></li>
    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/144.63.2">144.63.2    -  an invalid IP address.</a></li>

    <li><a href="http://localhost:8081/dbwebb/ramverk1/me/redovisa/htdocs/ip/json/35.158.84.49/144.62.2">35.158.84.49 &amp 144.62.2   - two adresses, one valid and one invalid.</a></li>


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
