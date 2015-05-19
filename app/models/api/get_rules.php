<?php
namespace Typolib;

$rules = Rule::getArrayRules($code, $locale);
$ruletypes = Rule::getRulesTypeList();
$rule_exceptions = RuleException::getArrayExceptions($code, $locale);
include VIEWS . 'view_treeview.php';
