<?php

use Typolib\Code;
use Typolib\Exception;
use Typolib\RepoManager;
use Typolib\Rule;

$code = new Code('typolib\'', 'fr', true);

$ru = new Rule('firefox', 'fr', 'regle test', 'if_then');
/*$ru1 = new Rule('firefox', 'fr', 'regle numéro 2', 'ifthen');
Rule::manageRule('firefox', 'fr', 0, 'update_content', 'test switch');
$ex = new Exception('firefox', 'fr', 0, 'contenu de l\'exception');

$pr = new RepoManager();*/

/*
$file_name = DATA_ROOT . 'typolib-rules/test.php';
// Update content in repository
file_put_contents($file_name, "Règle 1\nRègle 2\n");

$pr->commitAndPush();
*/

$javascript_include = ['ajax_insert.js'];
include MODELS . 'view.php';
include VIEWS . 'view.php';
