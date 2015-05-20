<?php
namespace Typolib;

/* Model we call to process the data sent using Edit Code form. */

$common = isset($_GET['common']);
Code::editCodeName($_GET['old_code'], $_GET['name'], $_GET['locale'], $common);
$success = 'Code successfully updated.';
