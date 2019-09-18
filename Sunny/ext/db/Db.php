<?php
namespace Sunny\ext\db;
use Sunny\framework\config\Config;
class Db{
    private  static $db = null;
    public static  function db(String $db='db'){
         $driver = Config::get('db')[$db]['driver'];
         $setAttribute = Config::get('db')[$db]['setAttribute'];
         $pdoAttr = Config::get('db')[$db]['pdoAttr'];

             static::$db = $driver::setInstance(function ()use($db,$setAttribute,$pdoAttr){
                 $pdo = null;
                 try {
                     $pdo= new \PDO(Config::get('db')[$db]['dns'],Config::get('db')[$db]['username'],
                         Config::get('db')[$db]['password']
                         ,$pdoAttr);
                     $pdo->exec("set names utf8");
                 } catch ( \PDOException $e ){
                     echo  'Connection failed: '.$e -> getMessage ();
                 }
                 if(!empty($setAttribute)){
                     foreach ($setAttribute as $k=>$v){
                         $pdo->setAttribute($v[0],$v[1]);
                     }
                 }

                 return $pdo;

             });



    }

    public static function __callStatic($name, $arguments)
    {
        $db = 'db';
        if(in_array($arguments[0],array_keys(Config::get('db')))){
            $db = $arguments[0];
            array_splice($arguments,0,1);
        }
        if (self::$db==null)self::db($db);
        if(Config::get('log','sqlPrint')){
           \Sunny::log('['.$arguments[0].']');
        }

        return  call_user_func_array([self::$db,$name],$arguments);


    }


}