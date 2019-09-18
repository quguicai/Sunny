<?php
namespace Sunny\framework\trace;
use Sunny\framework\config\Config;
use Sunny\framework\scan\ScanPacket;
use Sunny\ext\redis\Redis;
class Trace{
    /**
     * 获取路由
     */
    private static $trace = [];
    private static $app = null;
    public static function getTrace($traceName)
    {

        if(Config::get('trace','cache')=='file'){
            if(!file_exists(Config::get('trace','path'))){
                static::parseTrace(self::$app);
            }
            $data = file_get_contents(Config::get('trace','path'));
            if($data){
                $data = json_decode($data,true);
                if(!isset($data[$traceName]))throw  new \Exception('not find route!');
                return $data[$traceName];
            }

        }
        if(Config::get('trace','cache')=='redis'){
            $redis =Redis::getInstance(Config::get('redis'));
            $redis->select(Config::get('trace','dbname'));
            $trace = $redis->get($traceName);
            if(!$trace){
                static::parseTrace(self::$app);
            }
            $trace = $redis->get($traceName);
            return json_decode($trace,true);
        }
    }



    /**
     * 解析路由
     */
    public static function parseTrace($app){
        self::$app = $app;
        if(Config::get('trace','cache')=='file'){



            if(!file_exists(Config::get('trace','path')) || filesize(Config::get('trace','path'))==0){
                $trace = ScanPacket::scanF(APP_PATH.'/'.$app,'php',function ($doc,$funcName){
                    return  ParseDoc::getTrace($doc,$funcName);
                },ParseDoc::class,'RequestMap');
                $f = fopen(Config::get('trace','path'),'w');
                fwrite($f,json_encode($trace));
                fclose($f);
            }
            $data = file_get_contents(Config::get('trace','path'));

        }

        if(Config::get('trace','cache')=='redis'){
            $trace = ScanPacket::scanF(APP_PATH.'/'.$app,'php',function ($doc,$funcName){
                return  ParseDoc::getTrace($doc,$funcName);
            },ParseDoc::class,'RequestMap');
            $redis =Redis::getInstance(Config::get('redis'));
            $redis->select(Config::get('trace','dbname'));
            $redis->flushDB();
            foreach ($trace as $k => $v){

                $redis->set($k,json_encode($v));
            }

        }

    }
    public static function setPatch($app){
         self::$app = $app;
    }

}
