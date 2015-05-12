<?php
use Typolib\Code;

$locale = $_GET['locale'];
$code_name = $_GET['name'];
$use_common_code = isset($_GET['common']);

if ($code_name != '') {
    try {
        $code = new Code($code_name, $locale, $use_common_code);
        $success = true;
    } catch (Exception $e) {
        $message = $e->getMessage();
        $success = false;
    }
} else {
    $message = 'code name is empty.';
    $success = false;
}
