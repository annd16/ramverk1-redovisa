<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());


// echo "data = ";         // Kommer från INTE från redovisa/router/200_tärning.php (routen tärning/xxx),
//                         // Utan kommer från redovisa/config/page3.php
// var_dump($data);
//
//
// echo "form = ";         // Kommer från redovisa/config/form.php
// var_dump($form);
//
// // echo "title = ";         // Kommer från redovisa/config/form.php
// // var_dump($title);

?>

<div class="form-wrap start">

    <fieldset>
        <legend>IP validator</legend>

<?php
    // echo "this is the start form-view!";
    echo $formIp->displayForm($mount, $formAttrs);
?>
    </fieldset>
</div>
