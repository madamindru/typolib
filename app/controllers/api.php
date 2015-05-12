<?php
namespace Typolib;

use Transvision\Utils;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: text/html; charset=utf-8');

$locale = $_GET['locale'];

switch ($_GET['action']) {
    case 'codes':
        $codes = Code::getCodeList($locale);
        reset($codes);
        echo Utils::getHtmlSelectOptions(Code::getCodeList($locale), key($codes), true);
        break;
    case 'rules':
        $code = $_GET['code'];
        $rules = Rule::getArrayRules($code, $locale);
        $ruletypes = Rule::getRulesTypeList();
        include VIEWS . 'rules_treeview.php';
    break;
}
