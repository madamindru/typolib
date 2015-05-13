<?php
namespace Typolib;

Code::editCodeName('test', 'test2', 'fr', true);

if (isset($_GET['locale'])) {
    include MODELS . 'inserted.php';
    include VIEWS . 'inserted.php';
} else {
    include MODELS . 'insert.php';
    include VIEWS . 'insert.php';
}
