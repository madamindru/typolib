<?php
namespace Typolib;

/* Test something here */
$locales = Locale::getLocaleList();
dump($locales);
$repo_mgr = new RepoManager();

$content_array = ["erte", "ter"];
$new_rule = new Rule('test', 'fr', $content_array, 'if_then');
?>

<form id="mainform">
    <p>Result: <?=$locales[0]?></p>
</form>
