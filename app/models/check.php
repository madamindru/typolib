<?php
namespace Typolib;

use Transvision\Utils;

$locale_selector = Utils::getHtmlSelectOptions(
                                Locale::getLocaleList(),
                                $locale
                            );

$rules = Rule::getRulesTypeList();
reset($rules);
$ruletypes_selector = Utils::getHtmlSelectOptions(
                                Rule::getRulesTypeList(),
                                key($rules),
                                true
                            );

$codes = $code_key = Code::getCodeList($locale, RULES_STAGING);
reset($code_key);
$code_key = key($code_key);
$code_selector = Utils::getHtmlSelectOptions($codes, $code_key, true);

$ruletypes = Rule::getRulesTypeList();
$first_rule = array_values($rules)[0];
$rules = Rule::getArrayRules($code_key, $locale, RULES_STAGING);

$rule_exceptions = RuleException::getArrayExceptions($code_key, $locale, RULES_STAGING);
