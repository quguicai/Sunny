<?php
namespace Sunny\framework\config;
class  Config{
       private static $config = [];
       public static function get(String $confFileName,String $name=null){
           if(!empty($confFileName) && !empty($name)){
              $configs = static::$config[$confFileName][$name]??"";

              if(empty($configs) && $configs!=0 ) throw new \Exception('config name not find!');
              return $configs;
           }

           if(empty($name)) return static::$config[$confFileName]??[];

       }

       public  static function scanConfig($dir =APP_PATH.'/config'){
           $dirFile = scandir($dir);
           foreach ($dirFile as $k => $v) {
               if ($k > 1) {
                   if (is_file($dir.'/'.$v)&&(pathinfo($dir.'/'.$v)['extension']=='php')){

                       $filename = pathinfo($dir . '/' . $v,PATHINFO_FILENAME);

                       $config = include($dir.'/'.$v);
                       static::$config[$filename] = $config;
                   }
                   if (is_dir($dir.'/'.$v))static::scanConfig($dir.'/'.$v);
               }
           }

       }
}