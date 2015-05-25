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
            foreach (Rule::getRulesTypeList() as $key => $value) {
                $ruletypes[$key]=sprintf(str_replace ('%s' , '%1$s' , $value), '[…]');
            }
            $rule_exceptions = RuleException::getArrayExceptions(
                                                                    $code,
                                                                    $locale,
                                                                    RULES_STAGING
                                                                );
            foreach ($rules['rules'] as $key => $value) {
                $buildRule[$key] = Rule::buildRuleString($value['type'], $value['content']);
            }
        include VIEWS . 'view_treeview.php';
        } else {
            echo '0';
        }
    } catch (Exception $e) {
    }
} else {
    echo '0';
}
