<?php
namespace Typolib;

$exception = $_GET['content'];
$id_rule = $_GET['id_rule'];

if ($exception != '') {
    try {
        $new_exception = new RuleException($code, $locale, $id_rule, $exception);
        $id_exception = $new_exception->getId();
        include VIEWS . 'view_exception.php';
    } catch (Exception $e) {
    }
} else {
    echo '0';
}
