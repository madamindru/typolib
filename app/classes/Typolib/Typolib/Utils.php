<?php
namespace Typolib;

/**
 * Utils class
 *
 * @package Typolib
 */
class Utils
{
    public static function sanitizeFileName($name)
    {
        return preg_replace('/[^a-zA-Z0-9-_\.]/', '', $name);
    }

    public static function deleteFolder($folder)
    {
        if (is_dir($folder)) {
            $objects = scandir($folder);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($folder . '/' . $object) == 'dir') {
                        Utils::deleteFolder($folder . '/' . $object);
                    } else {
                        unlink($folder . '/' . $object);
                    }
                }
            }
            reset($objects);
            rmdir($folder);

            return true;
        }

        return false;
    }

    /**
     * Creates a directory when we try to put a file in a directory that doesn't
     * exists. We are also making sure to check if the repo has been cloned.
     *
     * @param $path    String of the file we want to save
     * @param $content String we want to save into the file
     */
    public static function fileForceContents($path, $content)
    {

        // Maybe it's just a new file, but maybe the repo has not been cloned.
        // We need to make sure the repo is cloned before saving into this directory.
        if (! file_exists($path)) {
            new RepoManager();
        }

        $parts = explode('/', $path);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (! is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }

        file_put_contents("$dir/$file", $content);
    }
}
