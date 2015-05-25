<?php
namespace Typolib;

/**
 * Strings class
 *
 * This class is for all the methods we need to manipulate strings
 *
 * @package Typolib
 */
class Strings
{
    public static $regex_extract_sentences = '
    /# Split sentences on whitespace between them.
    (?<=                # Begin positive lookbehind.
      [.!?]             # Either an end of sentence punct,
    | [.!?][\'"]        # or end of sentence punct and quote.
    )                   # End positive lookbehind.
    (?<!                # Begin negative lookbehind.
      Mr\.              # Skip either "Mr."
    | Mrs\.             # or "Mrs.",
    | Ms\.              # or "Ms.",
    | Jr\.              # or "Jr.",
    | Dr\.              # or "Dr.",
    | Prof\.            # or "Prof.",
    | Sr\.              # or "Sr.",
    | T\.V\.A\.         # or "T.V.A.",
                        # or... (you get the idea).
    )                   # End negative lookbehind.
    \s+                 # Split on whitespace between sentences.
    /ix';

    /**
     * Split a string into sentences
     *
     * @param  string $text Text
     * @return string array Array of sentences
     */
    public static function getSentencesFromText($text)
    {
        $sentences_array = preg_split(self::$regex_extract_sentences, $text, -1, PREG_SPLIT_NO_EMPTY);

        return $sentences_array;
    }

    /**
     * Replace a character in a string given its position.
     *
     * @param  String $string   The input string.
     * @param  String $char     The replacement string.
     * @param  int    $position The position where the replacing will begin
     *                          (1 if empty).
     * @param  int    $length   The length of the portion of string which is to be
     *                          replaced.
     * @param  String $encoding The character encoding ('UTF-8' if empty).
     * @return string $string   The text corrected.
     */
    public static function replaceString($string, $char, $position, $length = 1, $encoding = 'UTF-8')
    {
        mb_internal_encoding($encoding);

        $startString = mb_substr($string, 0, $position);
        $endString = mb_substr($string, $position + $length, mb_strlen($string));
        $string = $startString . $char . $endString;

        return $string;
    }
}
