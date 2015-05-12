<?php
namespace Typolib;

/**
 * Code class
 *
 * This class provides methods to manage a code: create, delete or update,
 * check if a code exists.
 *
 * @package Typolib
 */
class Code
{
    private $name;
    private $locale;
    private $path;
    private $common_code_user;
    private static $code_list = [];

    /**
     * Constructor that initializes all the arguments then call the method
     * to create the code if the locale is supported.
     *
     * @param  String  $name             The name of the new code.
     * @param  String  $locale           The locale of the new code.
     * @param  boolean $common_code_user True if the code must use the common
     *                                   code of the locale
     * @return boolean True if the code has been created.
     */
    public function __construct($name, $locale, $common_code_user)
    {
        if (Locale::isSupportedLocale($locale)) {
            $this->name = $name;
            $this->locale = $locale;
            $false_name = Utils::sanitizeFileName($this->name);
            if ($false_name != 'common') {
                $this->path = DATA_ROOT . RULES_REPO . "/$this->locale/$false_name";
                $this->common_code_user = $common_code_user;
                $this->createCode();
                if (! is_dir(DATA_ROOT . RULES_REPO . "/$this->locale/common")) {
                    $this->createCommonCode();
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Creates an code, its directory and its files (rules.php and exceptions.php).
     *
     * @return boolean True if the code doesn't exist and has been created.
     */
    private function createCode()
    {
        if (! file_exists($this->path)) {
            $code = ['name' => $this->name];

            $this->common_code_user ? $code['common'] = true : $code['common'] = false;

            // Maybe it's just a new file, but maybe the repo has not been cloned.
            // We need to make sure the repo is cloned before creating this directory.
            if (! is_dir(DATA_ROOT . RULES_REPO)) {
                new RepoManager();
            }
            mkdir($this->path, 0777, true);

            file_put_contents($this->path . '/rules.php', serialize($code));
            file_put_contents($this->path . '/exceptions.php', '');

            return true;
        }

        return false;
    }

    /**
     * Creates the common code, its directory and its files (rules.php
     * and exceptions.php).
     *
     * @return boolean True if the code doesn't exist and has been created.
     */
    private function createCommonCode()
    {
        if (Locale::isSupportedLocale($this->locale)) {
            $path = DATA_ROOT . RULES_REPO . "/$this->locale/common";
            if (! file_exists($path)) {
                mkdir($path, 0777, true);

                file_put_contents($path . '/rules.php', '');
                file_put_contents($path . '/exceptions.php', '');

                return true;
            }
        }

        return false;
    }

    /**
     * Deletes a code. Calls deleteFolder method to delete all the files related
     * to the code.
     *
     * @param  String  $name   The name of the code to delete.
     * @param  String  $locale The locale of the code to delete.
     * @return boolean True if the function succeeds.
     */
    public static function deleteCode($name, $locale)
    {
        $folder = DATA_ROOT . RULES_REPO . "/$locale/$name";

        return Utils::deleteFolder($folder);
    }

    /**
     * Check if the code exists in the rule repository.
     *
     * @param  String  $name   The name of the code we search.
     * @param  String  $locale The locale of the code we search.
     * @return boolean True if the code exists.
     */
    public static function existCode($name, $locale)
    {
        $folder = DATA_ROOT . RULES_REPO . "/$locale/$name";

        return file_exists($folder);
    }

    /**
     * List all the available codes for a given locale.
     *
     * @param  String $locale The locale of the codes we search.
     * @return array  The list of all the codes for the locale.
     */
    public static function getCodeList($locale)
    {
        if (Locale::isSupportedLocale($locale)) {
            $dir = DATA_ROOT . RULES_REPO . "/$locale";

            return self::scanDirectory($dir);
        } else {
            return false;
        }
    }

    /**
     * Scan a directory to find the name of all the codes in it.
     *
     * @param  String $dir The path directory we want to scan.
     * @return array  $code_list The list of all the corresponding codes
     *                    (key: the name of the folder,
     *                    value: the real name of the code).
     */
    private static function scanDirectory($dir)
    {
        if (is_dir($dir)) {
            $me = opendir($dir);
            while ($child = readdir($me)) {
                if ($child != '.' && $child != '..') {
                    $folder = $dir . DIRECTORY_SEPARATOR . $child;
                    if ($child == 'rules.php') {
                        $code = unserialize(file_get_contents($folder));
                        self::$code_list[basename($dir)] = $code['name'];
                    }
                    self::scanDirectory($folder);
                }
            }
            unset($code);
        }

        return self::$code_list;
    }
}
