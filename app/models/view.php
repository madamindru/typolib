<?php
namespace Typolib;

use Transvision\Utils;

$locale_selector = Utils::getHtmlSelectOptions(
                                Locale::getLocaleList(),
                                $locale
                            );

$ruletypes_selector = Utils::getHtmlSelectOptions(
                                Rule::getRulesTypeList(),
                                Rule::getRulesTypeList()[0]
                            );

$code_selector = Utils::getHtmlSelectOptions(
                                Code::getCodeList($locale),
                                Code::getCodeList($locale)[0],
                                true
                            );
