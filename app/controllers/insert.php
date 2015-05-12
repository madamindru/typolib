<?php


if (isset($_GET['locale'])) {
    include MODELS . 'inserted.php';
    include VIEWS . 'inserted.php';
} else {
    include MODELS . 'insert.php';
    include VIEWS . 'insert.php';
}
