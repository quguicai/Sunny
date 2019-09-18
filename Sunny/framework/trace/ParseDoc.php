<?php
namespace Sunny\framework\trace;
class ParseDoc{
    /**
     * @param String $doc
     * @param String $func
     * @return string
     */
    public static function getTrace(String $doc = '',String $func):string
    {
        $returnStr = "";
        if ($doc == '') return false;
        $arr = explode('@', $doc);
        foreach ($arr as $key => $value) {
            $value = str_replace(['/*','*/','*'],'',$value);
            $value = preg_replace('/\s/',"",$value);
            $res = preg_match_all('/^'.$func.'(.*?)/',$value,$a);
            if( $res) $returnStr= $value;
        }
        return $returnStr;
    }

    /**
     * @param $prt
     * @param string $method
     * @return String
     */
    public static function  RequestMap($prt,$method=""):String{
        if(!empty($method)){
            $method = str_replace('\'','',strtoupper($method));
            switch ($method){
                case 'POST':
                    if(!static::isPost())exit('该请求，仅支持POST');
                    break;
                case 'GET':
                    if(static::isPost())exit('该请求，仅支持GET');
                    break;
                default:
                    break;
            }
        }
        return  str_replace('\'','',$prt);
    }

    /**
     * @return bool
     */
    public  static function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST');
    }
}
