<?php
namespace Typolib;

use Transvision\Utils;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: text/html; charset=utf-8');

switch ($_GET['action']) {
    case 'codes':
        $locale = $_GET['locale'];
        echo Utils::getHtmlSelectOptions(Code::getCodeList($locale), Code::getCodeList($locale)[0], true);
        break;
    case 'rules':
        $locale = $_GET['locale'];
        $code = $_GET['code'];
        echo json_encode(Rule::getArrayRules($code, $locale));
    break;
}
