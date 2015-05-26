<?php
namespace Typolib;

use Exception;
use IntlBreakIterator;
use Transvision\Strings;

/**
 * Rule class
 *
 * This class provides methods to manage a rule: create, delete or update,
 * check if a rule exists and get all the rules for a specific code.
 *
 * @package Typolib
 */
class Rule
{
    private $id;
    private $content;
    private $type;
    private $comment;
    // FIXME: string?
    public static $rules_type = [ 'if_then'     => 'REPLACE %s WITH%s',
                                  'contains'    => 'CONTAINS %s',
                                  'string'      => 'STRING',
                                  'starts_with' => 'STARTS WITH %s',
                                  'ends_with'   => 'ENDS WITH %s', ];
    private static $ifThenRuleArray = [];
    private static $variable_to_ignore_array = [];
    private static $start_variable_tag = '<-';
    private static $end_variable_tag = '->';
    private static $plural_separator_array = [];
    private static $all_ids = [];
    private static $quotation_marks = [
                                            ['«','»'],
                                            ['“','”'],
                                            ['"', '"'],
                                            ['‘','’'],
                                            ['»','«'],
                                            ['„','“'],
                                            ['„','”'],
                                            ['”','”'],
                                      ];

    /**
     * Constructor that initializes all the arguments then call the method to
     * create the rule if the code exists.
     *
     * @param  String  $name_code   The code name from which the rule depends.
     * @param  String  $locale_code The locale code from which the rule depends.
     * @param  array   $content     The content of the new rule.
     * @param  String  $type        The type of the new rule.
     * @param  String  $comment     The comment of the new rule.
     * @return boolean True if the rule has been created.
     */
    public function __construct($name_code, $locale_code, $content, $type, $comment = '')
    {
        $success = false;

        if (Code::existCode($name_code, $locale_code, RULES_STAGING) && self::isSupportedType($type)) {
            if (array_filter($content)) {
                $this->content = $content;
                $this->type = $type;
                $this->comment = $comment;
                $this->createRule($name_code, $locale_code);
                $success = true;
            }
        }

        if (! $success) {
            throw new Exception('Rule creation failed.');
        }
    }

    /**
     * Creates a rule into the rules.php file located inside the code directory.
     *
     * @param String $name_code   The code name from which the rule depends.
     * @param String $locale_code The locale code from which the rule depends.
     */
    private function createRule($name_code, $locale_code)
    {
        $file = DATA_ROOT . RULES_STAGING . "/$locale_code/$name_code/rules.php";
        $code = Rule::getArrayRules($name_code, $locale_code, RULES_STAGING);
        $code['rules'][] = [
                                'content' => $this->content,
                                'type'    => $this->type,
                            ];

        //Get the last inserted id
        end($code['rules']);
        $this->id = key($code['rules']);

        if ($this->comment != '') {
            $code['rules'][$this->id]['comment'] = $this->comment;
        }

        $repo_mgr = new RepoManager();

        file_put_contents($file, serialize($code));

        $repo_mgr->commitAndPush("Adding new rule in /$locale_code/$name_code");
    }

    /**
     * Allows deleting a rule, or updating the content or the type of a rule.
     *
     * @param  String  $name_code   The code name from which the rule depends.
     * @param  String  $locale_code The locale code from which the rule depends.
     * @param  integer $id          The identity of the rule.
     * @param  String  $action      The action to perform: 'delete', 'update_content',
     *                              'update_type' or 'update_comment'.
     * @param  String  $value       The new content or type of the rule. If action
     *                              is 'delete' the value must be empty.
     * @return boolean True if the function succeeds.
     */
    public static function manageRule($name_code, $locale_code, $id, $action, $value = '')
    {
        $file = DATA_ROOT . RULES_STAGING . "/$locale_code/$name_code/rules.php";

        $code = Rule::getArrayRules($name_code, $locale_code, RULES_STAGING);
        if ($code != null && Rule::existRule($code, $id)) {
            switch ($action) {
                case 'delete':
                    unset($code['rules'][$id]);

                    //delete all the exceptions for the rule.
                    $rule_exceptions = self::getArrayRuleExceptions($name_code,
                                                                    $locale_code,
                                                                    $id,
                                                                    RULES_STAGING
                                                                );
                    if ($rule_exceptions != false) {
                        foreach ($rule_exceptions as $id_exception => $content) {
                            RuleException::manageException(
                                                            $name_code,
                                                            $locale_code,
                                                            $id_exception,
                                                            'delete'
                                                        );
                        }
                    }
                    break;

                case 'update_content':
                    $code['rules'][$id]['content'] = $value;
                    break;

                case 'update_type':
                    if (self::isSupportedType($value)) {
                        $code['rules'][$id]['type'] = $value;
                    } else {
                        return false;
                    }
                    break;
                case 'update_comment':
                    $code['rules'][$id]['comment'] = $value;
                    break;
            }

            $repo_mgr = new RepoManager();

            file_put_contents($file, serialize($code));

            $repo_mgr->commitAndPush("Editing rule in /$locale_code/$name_code");

            return true;
        }

        return false;
    }

    /**
     * Check if the rule exists in a rules array.
     *
     * @param  array   $code The array in which the rule must be searched.
     * @param  integer $id   The identity of the rule we search.
     * @return boolean True if the rule exists
     */
    public static function existRule($code, $id)
    {
        return array_key_exists($id, $code['rules']);
    }

    /**
     * Get an array of all the rules for a specific code.
     *
     * @param String $name_code   The code name from which the rules depend.
     * @param String $locale_code The locale code from which the rules depend.
     * @param String $repo        Repository we want to check (staging or production)
     */
    public static function getArrayRules($name_code, $locale_code, $repo)
    {
        if (Code::existCode($name_code, $locale_code, $repo)) {
            $file = DATA_ROOT . $repo . "/$locale_code/$name_code/rules.php";

            return unserialize(file_get_contents($file));
        }
    }

    /**
     * Get an array of all the exceptions for a specific rule.
     *
     * @param String $name_code   The code name from which the exceptions depend.
     * @param String $locale_code The locale code from which the exceptions depend.
     * @param String $id          The rule id from which the exceptions depend.
     * @param String $repo        Repository we want to check (staging or production)
     */
    public static function getArrayRuleExceptions($name_code, $locale_code, $id, $repo)
    {
        $code = Rule::getArrayRules($name_code, $locale_code, $repo);
        if ($code != null && Rule::existRule($code, $id)) {
            $rule_exceptions = RuleException::getArrayExceptions(
                                                                $name_code,
                                                                $locale_code,
                                                                $repo
                                                            );

            if ($rule_exceptions != false) {
                foreach ($rule_exceptions['exceptions'] as $id_exception => $exception) {
                    if ($exception['rule_id'] == $id) {
                        $array[$id_exception] = $exception['content'];
                    }
                }

                return $array;
            }
        }

        return false;
    }

    /**
     * Check in a string if there is quotation marks.
     *
     * @param  String $string The string to check.
     * @return array  $position The list of all quotation marks present in the
     *                       string (with their position in the string).
     */
    private static function findQuotationMarks($string)
    {
        $position = null;
        $i = 0;
        $code_point_iterator = IntlBreakIterator::createCodePointInstance();
        $code_point_iterator->setText($string);
        $parts_iterator = $code_point_iterator->getPartsIterator();

        foreach ($parts_iterator as $part) {
            foreach (array_values(self::$quotation_marks) as $key => $value) {
                if (in_array($part, $value)) {
                    $position[$i] = $part;
                }
            }
            $i++;
        }

        return $position != null ? $position : false;
    }

    /**
     * Check a "quotation mark" rule.
     *
     * @param  string $user_string The string entered by the user.
     * @param  string $before      The opening quotation mark wanted by the user.
     * @param  string $after       The ending quotation mark wanted by the user.
     * @return array  $res         The text corrected and the position of the
     *                            quotation .
     */
    public static function checkQuotationMarkRule($user_string, $before, $after)
    {
        $res = []; // var to be returned
        $array_quotation_marks = self::findQuotationMarks($user_string);

        if ($array_quotation_marks != false) {
            $count = 0;
            foreach ($array_quotation_marks as $position => $quote) {
                if ($count % 2 == 0) {
                    $user_string = \Typolib\Strings::replaceString(
                                                            $user_string,
                                                            $before,
                                                            $position
                                                        );
                } else {
                    $user_string = \Typolib\Strings::replaceString(
                                                            $user_string,
                                                            $after,
                                                            $position
                                                        );
                }
                $count++;
            }

            array_push($res, $user_string);
            array_push($res, array_keys($array_quotation_marks));

            return $res;
        }

        return false;
    }

    /**
     * Add a "if x then y" rule to the global array
     *
     * @param string $user_string the string entered by the user
     */
    public function addRuleToIfThenArrayRule($user_string)
    {
        $pieces = explode(' ', $user_string);
        $input_character = $pieces[1];
        $new_character = $pieces[3];

        // if a value with the same key is added, the previous value will be
        // replaced by the new one
        self::$ifThenRuleArray[$input_character] = $new_character;
    }

    /**
     * Display all the rules of the "if then" array
     */
    public static function displayIfThenArrayRule()
    {
        foreach (self::$ifThenRuleArray as $key => $value) {
            echo "Input character: $key => New character: $value<br />\n";
        }
    }

    /**
     * Check a "if x then y" rule (just for ellipsis character)
     * TODO : generic method for any character of the ifThen rule array
     *
     * @param string $user_string the string entered by the user
     */
    public static function checkIfThenRule($user_string)
    {
        $res = []; // var to be returned
        $searches = self::$ifThenRuleArray;
        $positions = []; // array containing the positions of the errors detected in the source string

        foreach ($searches as $search => $replace) {
            $last_position = 0;

            // save all the positions of the errors
            while (($last_position = strpos($user_string, $search, $last_position)) !== false) {
                $$next_position = $last_position + strlen($search);
                $positions[$last_position] = $$next_position;
                $last_position = $$next_position;
            }

            // replace all the errors by the characters entered by the user
            if (strpos($user_string, $search) !== false) {
                $user_string = str_replace($search, $replace, $user_string);
            }
        }

        array_push($res, $user_string);
        array_push($res, $positions);

        return $res;
    }

    /**
     * Add a variable to the global array of variables to ignore
     *
     * @param string $user_string the string entered by the user
     */
    public static function addRuleToVariableToIgnoreArray($user_string)
    {
        $var_array = preg_split('/[\s]+/', $user_string);

        self::$variable_to_ignore_array = array_merge(
                                            self::$variable_to_ignore_array,
                                            $var_array
                                        );

        //self::$variable_to_ignore_array[$user_string] = self::$start_variable_tag . $user_string . self::$end_variable_tag;
    }

    /**
     * Display all the rules of the global array of variables to ignore
     */
    public static function displayVariableToIgnoreArray()
    {
        foreach (self::$variable_to_ignore_array as $key => $value) {
            echo "Variable to ignore: $key<br />\n";
        }
    }

    /**
     * Add a separator to the global array of plural separators
     *
     * @param string $user_string the string entered by the user
     */
    public static function addRuleToPluralSeparatorArray($user_string)
    {
        self::$plural_separator_array[] = $user_string;
    }

    /**
     * Display all the rules of the global array of plural separators
     */
    public static function displayPluralSeparatorArray()
    {
        foreach (self::$plural_separator_array as $key => $value) {
            echo "Plural separator: $value<br />\n";
        }
    }

    public static function checkSeparatorRule($user_string)
    {
        foreach (self::$plural_separator_array as $key => $separator) {
            $pos = strpos($user_string, $separator);

            if ($pos !== false) {
                //$separator = ';';
                $split_strings = explode($separator, $user_string);
                $levenshtein_results = [];
                $acceptance_level = 90;

                $arr_length = count($split_strings);
                for ($i = 0;$i < $arr_length;$i++) {
                    if ($i + 1 < $arr_length) {
                        $levenshtein_results[] = Strings::levenshteinQuality(
                                                            $split_strings[$i],
                                                            $split_strings[$i + 1]
                                                        );
                    }
                }

                $levenshtein_results_average = 0;

                foreach ($levenshtein_results as $key => $value) {
                    $levenshtein_results_average += $value;
                }

                $levenshtein_results_average =
                        $levenshtein_results_average / count($levenshtein_results);

                if ($levenshtein_results_average > $acceptance_level) {
                    $user_string = str_replace(
                                $separator,
                                $start_variable_tag . $separator . $end_variable_tag,
                                $user_string
                            );
                }
            }
        }

        return $user_string;
    }

    /**
     * Ignore all the variables of the variable_to_ignore_array in the user string
     *
     * @param string $user_string the string entered by the user
     */
    public static function ignoreVariables($user_string)
    {
        strtr($user_string, $variable_to_ignore_array);
    }

    /**
     * Unused for now
     */
    public static function generateRuleId()
    {
        $array = Rule::scanDirectory(DATA_ROOT . 'code');
        $id = empty($array) ? 0 : max($array);

        return ++$id;
    }

    /**
     * Scan the directory and put all the rules id in an array
     *
     * @param  String $dir The directory to be scanned.
     * @return array  $all_ids The array which contains all the rules id.
     */
    public static function scanDirectory($dir)
    {
        if (is_dir($dir)) {
            $me = opendir($dir);
            while ($child = readdir($me)) {
                if ($child != '.' && $child != '..') {
                    $folder = $dir . DIRECTORY_SEPARATOR . $child;
                    if ($child == 'rules.php') {
                        $code = unserialize(file_get_contents($folder));
                        foreach (array_keys($code['rules']) as $key => $value) {
                            self::$all_ids[] = $value;
                        }
                    }
                    Rule::scanDirectory($folder);
                }
            }
            unset($code);
        }

        return self::$all_ids;
    }

    /**
     * Check if the type of the rule is supported or not
     *
     * @param  String  $type The type of the rule we want to check.
     * @return boolean True if the type is supported.
     */
    public static function isSupportedType($type)
    {
        return array_key_exists($type, self::$rules_type);
    }

    /**
     * Get the list of all the types of rules
     *
     * @return array rules_type which contains all the supported types.
     */
    public static function getRulesTypeList()
    {
        return self::$rules_type;
    }

    public static function buildRuleString($type, $rule)
    {
        if (self::isSupportedType($type)) {
            return vsprintf(self::$rules_type[$type], $rule);
        }
    }
}
