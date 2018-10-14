<?php
/**
 * Created by PhpStorm.
 * User: pkirillw
 * Date: 14.10.18
 * Time: 3:58
 */

class LoaderApi
{
    static public function loadClasses($className)
    {
        $classFilePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($classFilePath)) {
            require_once $classFilePath;
            return true;
        }
        return false;
    }
}