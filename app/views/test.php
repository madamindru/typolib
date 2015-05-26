<?php
namespace Typolib;

/* Test something here */
$locales = Locale::getLocaleList();
dump($locales);
?>

<form id="mainform">
    <p>Result: <?=$locales[0]?></p>
</form>
