<?php
namespace Typolib;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: text/html; charset=utf-8');

$locale = $_GET['locale'];
$code = $_GET['code'];

// We must only allow those files, otherwise, any .php file on the server could be
// included below.
$models = ['get_codes', 'get_rules', 'adding_rule', 'adding_exception',
           'deleting_exception', ];

if (in_array($_GET['action'], $models)) {
    include MODELS . 'api/' . $_GET['action'] . '.php';
}
