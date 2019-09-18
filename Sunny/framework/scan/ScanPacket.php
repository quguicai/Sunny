<?php
namespace Sunny\framework\scan;
class ScanPacket{
    private static $abc = [];

    /**扫描app下所有文件包
     * @param string $dir
     * @param string $ext
     * @param callable $func1
     * @param $func2
     * @param $funcNname
     * @return array
     * @throws \ReflectionException
     */
    public static function scanF($dir='./',$ext = 'php',callable $func1,$func2,$funcNname):array
    {
        $arr = scandir($dir);
        foreach ($arr as $k => $v) {
            if($k>1){
                if (is_file($dir.'/'.$v)&&(pathinfo($dir.'/'.$v)['extension']==$ext)) {
                    $f = file_get_contents($dir.'/'.$v);
                    preg_match('/(?:namespace)(.*?)(?:\;)/i', $f, $r);
                    $namespace = preg_replace('/\s/', '', $r[1]);
                    //include($dir.'/'.$v);

                    $obj = new \ReflectionClass($namespace.'\\'.pathinfo($v)['filename']);
                    $mainTrace = $func1($obj->getDocComment(),$funcNname);
                    $classTrace = "";
                    if(!empty($mainTrace)){
                        preg_match('/\((.*?)\)/',$mainTrace,$n);
                        $classTrace = call_user_func_array([$func2,$funcNname],explode(",",$n[1]));
                    }
                    //echo ($obj->getDocComment());
                    $methodsArr = $obj->getMethods(\ReflectionMethod::IS_PUBLIC);
                    $className = $obj->getName();
                    foreach ($methodsArr as $key => $value) {
                        $method = $func1($value->getDocComment(),$funcNname);
                        $f = null;
                        if (!empty($method)){
                            preg_match('/\((.*?)\)/',$method,$f);
                            $callRes = call_user_func_array([$func2,$funcNname],explode(",",$f[1]));
                            if(isset(static::$abc[$callRes]))  throw new \Exception('Route repetition!('.$dir.'/'.$v.')');
                            static::$abc[$classTrace.$callRes] =['className'=>$className,'funName'=>$value->getName(),'dir'=>$dir.'/'.$v] ;
                        }
                    }
                }

                if (is_dir($dir.'/'.$v))static::scanF($dir.'/'.$v,$ext,$func1,$func2,$funcNname);
            }
        }
        return static::$abc;
    }
}