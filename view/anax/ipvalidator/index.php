<?php

namespace Anax\View;

/**
 * IpValidator view.
 */

// Show incoming variables and view helper functions


// $form =  require __DIR__ . "/../../../config/form_ipvalidation.php";         // fungerar!

// $form =  require asset("form_ipvalidation");         // fungerar inte!

$request = $this->di->get("request");

// var_dump($session)

?><h1>IP validator</h1>

<?php include __DIR__ . "/../v2/navbar/default.php"; ?>

<p>Here you can enter an IP-adress to check if it is a valid IPv4 or IPv6 adress.</p>
<p>You will also get the domain name, if available.</p>


<div class="developers">
    <h2> For developers: </h2>
        <p>The server of this web site provides a REST API for the IP-checking utility, please visit the API page to get more information.</p>
</div>
