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

$codes = Code::getCodeList($locale);
reset($codes);
$code_selector = Utils::getHtmlSelectOptions(
                                Code::getCodeList($locale),
                                key($codes),
                                true
                            );

$ruletypes = Rule::getRulesTypeList();
$first_rule = array_values($rules)[0];
$rules = Rule::getArrayRules(key($codes), $locale);

$rule_exceptions = RuleException::getArrayExceptions(key($codes), $locale);
