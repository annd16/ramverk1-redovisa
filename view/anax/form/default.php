<?php

namespace Anax\View;

/**
 * Template file to render a Form view.
 */

// Show incoming variables and view helper functions
// echo showEnvironment(get_defined_vars(), get_defined_functions());

$request = $this->di->get("request");
// echo "data = ";         // Kommer från INTE från redovisa/router/200_tärning.php (routen tärning/xxx),
//                         // Utan kommer från redovisa/config/page3.php
// var_dump($data);
//
//
// if (isset($formError)) {
//     echo "formError = ";         // Kommer från redovisa/config/form.php
//     var_dump($formError);
// }
//
// // echo "title = ";         // Kommer från redovisa/config/form.php
// // var_dump($title);

?>

<!-- <p>Inside 'form/default.php'</p> -->
<!-- <code><?= "<br/>The siteUrl = " . $request->getSiteUrl(); ?></code> -->
<!-- <code><?= "<br/>The baseUrl = " . $request->getBaseUrl(); ?></code> -->
<!-- <code><?= "<br/>The currentUrl = " . $request->getCurrentUrl(); ?></code> -->

<div class="form-wrap start" id=<?= $id = isset($id) ? $id : ""?>>

    <fieldset>
        <legend><?= $title ?></legend>
        <legend><?= (isset($description) && $description != "")?$description:"" ?></legend>

<?php
    echo $formIp->displayForm($mount, $formAttrs);
    $error = (isset($formError) && $formError != "")?$formError:"";
?>
    <div class='error'><?= $error ?></div>
    </fieldset>
</div>
