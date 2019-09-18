<?php
namespace Sunny\ext\redis;
class  Redis{
    private static $instance=null;
    private function __construct()
    {

    }

    public static function getInstance($config,$reConnection = false){
        if(!in_array('redis',get_loaded_extensions())) exit('redis extenion not found!');
        if(static::$instance!=null && $reConnection==false) return static::$instance;

        $redis = new \Redis();

        try{
           $redis->connect($config['host'],$config['port']);
            if(!empty($config['password'])) $redis->auth($config['password']);
            static::$instance=$redis;

        }catch (\Exception $e){
            throw new \Exception('redis connection fail！');
        }
        return $redis;
    }
}