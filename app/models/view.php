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
                                key($rules)
                            );

$codes = Code::getCodeList($locale);
reset($codes);
$code_selector = Utils::getHtmlSelectOptions(
                                Code::getCodeList($locale),
                                key($codes),
                                true
                            );

$ruletypes = Rule::getRulesTypeList();
$rules = Rule::getArrayRules(key($codes), $locale);
