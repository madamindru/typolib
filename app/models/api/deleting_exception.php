<?php
namespace Typolib;

$id_exception = $_GET['id_exception'];
$id_rule = $_GET['id_rule'];
try {
    RuleException::manageException($code, $locale, $id_rule, 'delete', $id_exception);
    echo '1';
} catch (Exception $e) {
}
