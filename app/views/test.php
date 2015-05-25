<?php
namespace Typolib;

/* Test something here */
$locales = Locale::getLocaleList();
dump($locales);
$repo_mgr = new RepoManager();

$rules = Rule::getArrayRules('test', 'fr', RULES_STAGING);
foreach ($rules['rules'] as $key => $value) {
    dump($buildRule[$key] = Rule::buildRuleString($value['type'], $value['content']));
}
?>

<form id="mainform">
    <p>Result: <?=$locales[0]?></p>
</form>
