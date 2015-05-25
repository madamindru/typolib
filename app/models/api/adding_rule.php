<?php
namespace Typolib;

$type = $_GET['type'];
$content = $_GET['content'];
$comment = $_GET['comment'];
$content_array = json_decode($_GET['array']);

$content_array = array_filter($content_array);

$array_OK = true;
if (!empty($content_array)) {
    foreach ($content_array as $key => $value) {
        if($value == '') {
            $array_OK = false;
        }
    }
    try {
        if ($array_OK) {
            $new_rule = new Rule($code, $locale, $content_array, $type, $comment);
            $rules = Rule::getArrayRules($code, $locale, RULES_STAGING);
            $ruletypes = Rule::getRulesTypeList();
            $rule_exceptions = RuleException::getArrayExceptions(
                                                                    $code,
                                                                    $locale,
                                                                    RULES_STAGING
                                                                );
        include VIEWS . 'view_treeview.php';
        } else {
            echo '0';
        }
    } catch (Exception $e) {
    }
} else {
    echo '0';
}
