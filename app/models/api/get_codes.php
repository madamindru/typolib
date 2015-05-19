<?php
namespace Typolib;

use Transvision\Utils;

$codes = Code::getCodeList($locale);
reset($codes);
echo Utils::getHtmlSelectOptions(Code::getCodeList($locale), key($codes), true);
