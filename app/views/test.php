<?php
namespace Typolib;

/* Test something here */
$locales = Locale::getLocaleList();
dump($locales);
$repo_mgr = new RepoManager();
?>

<form id="mainform">
    <p>Result: <?=$locales[0]?></p>
</form>
