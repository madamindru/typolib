<?php

if (isset($_GET['edit_code'])) {
    include MODELS . 'edit_code.php';
    include VIEWS . 'edit_code.php';
} elseif (isset($_GET['edit_code_sent'])) {
    $javascript_include = ['ajax_insert.js'];
    $css_include = ['treeview.css', 'buttons.css'];
    include MODELS . 'edit_code_sent.php';
    include MODELS . 'view.php';
    include VIEWS . 'view.php';
} elseif (isset($_GET['delete_code'])) {
    include MODELS . 'delete_code.php';
    include MODELS . 'view.php';
    include VIEWS . 'view.php';
} else {
    $javascript_include = ['ajax_insert.js'];
    $css_include = ['treeview.css', 'buttons.css'];
    include MODELS . 'view.php';
    include VIEWS . 'view.php';
}
