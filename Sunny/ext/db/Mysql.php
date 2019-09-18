<?php
namespace Sunny\ext\db;
class Mysql{
    private static $instance = null;
    private function __construct()
    {

    }
    public static function getInstance(){
        return static:: $instance;
    }
    public static function setInstance(callable $db){
        static:: $instance = $db();
        return self::class;

    }
    public static function getDb(){
        return self::class;
    }
    public  static  function query($sql,$type = 2)
    {
        $arr =  self::$instance->query($sql);
        if(!$arr) return false;
        @$data = $arr->fetchAll($type);
        if($data){
            return $data;
        }else{
            return '0';
        }
    }

    public static  function fetch($sql)
    {
        $sth =  self::$instance->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        if(empty($result)) return false;
        return $result;
    }

    public  static  function exec($sql)
    {
        self::$instance->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        try{
            $row = self::$instance->exec($sql);
            return $row;
        }catch(\PDOException $e){
            return false;
        }

    }

    public static function updateAll($table,$data,$where)
    {
        if(!empty($table)&&!empty($data))
        {
            $sql = 'update '.$table.' set ';
            foreach($data as $key=>$val)
            {
                $sql.= $key.'='.self::quote($val).',';
            }
            $tmp = trim($sql,',');
            return $tmp.=$where;

        }
        return 'null';
    }

    public static function quote($str){
        return self::$instance->quote($str);
    }

    public  static function del($sql,$where=true){
        if($where){
            if(!strstr($sql,'where')){
                return false;
            }
        }else{
            return self::exec($sql);
        }
    }

    public static function execute($sql,$data){
        $tmp = self::$instance;
        //$tmp->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        $pre = $tmp->prepare($sql);
        $i=0;
        if(is_array($data)){
            foreach($data as $info){
                $i+=$pre->execute($info);
            }

        }

        return $i;
    }

    public static function insertAllSql($data,$table,$filters=[],$type='insert'){
        $key = array_keys($data);
        $sql_data=$data;
        $keys = $key;
        $key = implode($key,',');
        $sql_order_tab =$type.' into '.$table.'('.$key.')values(';
        for($i=0;$i<count($keys);$i++){
            if(!empty($filters)&& in_array($keys[$i],$filters)){
                $sql_order_tab.=$sql_data[$keys[$i]].',';
            }else{
                $sql_order_tab.=self::quote($sql_data[$keys[$i]]).',';
            }

        }
        return  trim($sql_order_tab,',').')';
    }

    public static function transaction (callable $func)
    {
        try{
            $dbh = self::$instance;
            $dbh->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
            $dbh->beginTransaction();
            call_user_func_array($func,[]);
            $dbh->commit();
            return '1';
        }catch(\PDOException $e){
            $dbh->rollBack();
            return '-1';
        }
    }
    public static function prepareSql($table,$data)
    {
        if(empty($data) || empty($table))
        {
            return false;
        }
        $key = array_keys($data);
        $sql = 'insert into '.$table.'('.preg_replace('/\s/','',implode(',',$key)).')values(';
        foreach($key as $k=>$v)
        {
            $sql.=':'.preg_replace('/\s/','',$v).',';
        }
        $sql = trim($sql,',').')';
        return $sql;

    }
    public static function prepareSqlReplcae($table,$data)
    {
        if(empty($data) || empty($table))
        {
            return false;
        }
        $key = array_keys($data);
        $sql = 'replace  into '.$table.'('.implode(',',$key).')values(';
        foreach($key as $k=>$v)
        {
            $sql.=':'.$v.',';
        }
        $sql = trim($sql,',').')';
        return $sql;

    }

    public  static function start(){
        self::$instance->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);

        self::$instance->beginTransaction();
    }

    public  static function commit(){
        self::$instance->commit();
    }

    public  static function rollback(){
        self::$instance->rollback();
    }

}