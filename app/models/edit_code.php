<?php
namespace Typolib;

/* Model we call to populate the Edit Code form. */

$locale = $_GET['locale'];
$old_code = $_GET['code'];
$rules = Rule::getArrayRules($old_code, $locale, RULES_STAGING);
$common = $rules['common'];
$name = $rules['name'];
unset($rules);
