<?php
namespace Typolib;

$id_rule = $_GET['id_rule'];

if (Rule::manageRule($code, $locale, $id_rule, 'delete')) {
    echo '1';
}
