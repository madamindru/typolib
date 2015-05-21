<?php
namespace Typolib;

use Zend_Cache;
use Zend_Translate;

/**
 * File class
 *
 * This class provides methods to extract strings from different industry file
 * formats like .tbx, .tmx or .ts.
 *
 * @package Typolib
 */
class File
{
    private static $supported_type = [
                                        'array' => 'Zend_Translate_Adapter_Array',
                                        'csv'   => 'Zend_Translate_Adapter_Csv',
                                        'mo'    => 'Zend_Translate_Adapter_Gettext',
                                        'tbx'   => 'Zend_Translate_Adapter_Tbx',
                                        'tmx'   => 'Zend_Translate_Adapter_Tmx',
                                        'ts'    => 'Zend_Translate_Adapter_Qt',
                                        'xliff' => 'Zend_Translate_Adapter_Xliff',
                                    ];

    /**
     * Extract strings from different industry file formats.
     *
     * @param  String $path   The path of the file we want to extract the strings.
     * @param  String $locale The locale for which we want the translations.
     * @param  String $type   The type of the file given in the path.
     * @return array  The extracted strings.
     */
    public static function getFileContent($path, $locale, $type)
    {
        if (self::isSupportedType($type)) {
            $cache = Zend_Cache::factory(
                'Core', 'File',
                [
                    'caching'                   => true,
                    'lifetime'                  => 900,
                    'automatic_serialization'   => true,
                    'automatic_cleaning_factor' => 20,
                    'cache_id_prefix'           => 'Translate',
                ],
                [
                    'hashed_directory_level' => 0,
                    'cache_dir'              => DATA_ROOT . 'tmp',
                ]
            );

            Zend_Translate::setCache($cache);

            $adapter = self::getTypeAdapter($type);

            $translate = new Zend_Translate(
                [
                    'adapter' => $adapter,
                    'content' => $path,
                    'locale'  => 'auto',
                ]
            );

            $locales = $translate->getAdapter()->getList();

            if (in_array($locale, $locales)) {
                return $translate->getAdapter()->getMessages($locale);
            }
        }

        return false;
    }

    /**
     * Check if a type of file is supported or not.
     *
     * @param  String  $type The type we want to check.
     * @return boolean True if the type is supported.
     */
    public static function isSupportedType($type)
    {
        return array_key_exists($type, self::$supported_type);
    }

    /**
     * Get the zend adapter depending on the provided type of file.
     *
     * @param  String $type The type of file we want to get the adapter.
     * @return String The adapter for the provided type.
     */
    public static function getTypeAdapter($type)
    {
        if (self::isSupportedType($type)) {
            return self::$supported_type[$type];
        }

        return false;
    }
}
