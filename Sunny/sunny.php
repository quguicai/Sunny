<?php
include'ext/loader/Loader.php';
include 'functions.php';
define('FORMWORK_PATH',dirname(__dir__));
use Sunny\framework\config\Config;
use Sunny\framework\http\Http;
use Sunny\framework\trace\Trace;
spl_autoload_register('Sunny\ext\loader\Loader::autoload');
class Sunny
{
    public static function start($app = 'app')
    {

        set_error_handler(function ($errno, $errstr, $errfile, $errline){
            throw new \Exception('错误号：'.$errno.',错误描述：'.$errstr.',错误文件：'.$errfile.',错误行号：'.$errline.','.date('Y-m-d H:i:s')."\r\n");
        });

        try {
            /**
             * 加载配置文件
             */
            self::loadSet();

            Trace::setPatch($app);

            /**
             * 启动controller映射
             */
            self::startController();

        } catch (Exception $e) {

            if(APP_DEBUG){
                exit($e->getMessage());
            }
            static::log($e->getMessage());
            exit('<h1>^^您访问的页面丢失了！</h1>');

        }

    }

   static function log($msg){
       $f = fopen(Config::get('log','logPath').'/'.date('Y-m-d').'.log','a');
       fwrite($f,$msg.' '.date('Y-m-d H:i:s')."\r\n");
       fclose($f);
   }
   private  static function startController(){
       /**
        * 启动路由映射
        */
       Http::controllerMap(function ($traceName){
           return Trace::getTrace($traceName);
       });
   }

   private static function  loadSet(){
       Config::scanConfig();

   }

    /**加载路由
     * @param $app
     */
   public static function loadRoute($app){
       Config::scanConfig();
       Trace::setPatch($app);
       Trace::parseTrace($app);
   }


}


