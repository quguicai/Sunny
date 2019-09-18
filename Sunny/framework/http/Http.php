<?php
namespace Sunny\framework\http;
class Http{
    private static $request = [];
    public static function controllerMap(callable $func)
    {

        $trace = $func($_SERVER['PATH_INFO']);
        if(!empty($_SERVER['PATH_INFO']) && !empty($trace)) {

            $classes = new $trace['className'];
            $func = $trace['funName'];
            $classes->$func();
        }else{
            throw new \Exception('访问的功能不存在');
        }

    }

}