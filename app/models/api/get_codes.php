<?php
namespace Typolib;

use Transvision\Utils;

$codes = $code_key = Code::getCodeList($locale, RULES_STAGING);
reset($code_key);
echo Utils::getHtmlSelectOptions($codes, key($code_key), true);
