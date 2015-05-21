<?php
namespace Typolib;

$type = $_GET['type'];
$content = $_GET['content'];
$comment = $_GET['comment'];

if ($content != '') {
    try {
        $new_rule = new Rule($code, $locale, $content, $type, $comment);
        $rules = Rule::getArrayRules($code, $locale);
        $ruletypes = Rule::getRulesTypeList();
        $rule_exceptions = RuleException::getArrayExceptions($code, $locale);
        include VIEWS . 'view_treeview.php';
    } catch (Exception $e) {
    }
} else {
    echo '0';
}
