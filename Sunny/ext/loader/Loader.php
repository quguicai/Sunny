<?php
namespace Sunny\ext\loader;
class Loader
{
    static function autoload($class)
    {

        $dir = FORMWORK_PATH . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($dir)) {
            include $dir;
            return;
        }

        $dir = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($dir)) {
            include $dir;
            return;
        }
        throw new \Exception('file not find '.$dir);

    }
}