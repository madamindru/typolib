<?php
namespace Typolib;

$rules = Rule::getArrayRules($code, $locale, RULES_STAGING);
$ruletypes = Rule::getRulesTypeList();
$rule_exceptions = RuleException::getArrayExceptions($code, $locale, RULES_STAGING);
include VIEWS . 'view_treeview.php';
