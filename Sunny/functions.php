<?php
function getip(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}
function isAjax($host)
{
    if(!isset($_SERVER['HTTP_REFERER']) || !stripos($_SERVER['HTTP_REFERER'],$host)) {
        exit('400');
    }
}

function xmlToArray($xml)
{
    libxml_disable_entity_loader(true);
    //$values = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOEMPTYTAG);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement',LIBXML_NOCDATA)), true);
    return array_filter($values);
}

function sqlStr($mailno){
    $cc=explode("\r\n",$mailno);
    $dd='';
    foreach($cc as $mm){
        $dd.="'".$mm."'".",";
    }
    $zz=str_replace(array("",'',"\r\n","\t"," "),"",$dd);
    $zz= rtrim($dd ,",");
    $vv=str_replace(",''",'',$zz);
    return $vv;
}

function arrayToXml($arr,$dom=0,$item=0){
    if (!$dom){
        $dom = new DOMDocument("1.0",'utf-8');
    }
    if(!$item){
        $item = $dom->createElement("root");
        $dom->appendChild($item);
    }
    foreach ($arr as $key=>$val){
        $itemx = $dom->createElement(is_string($key)?$key:"item");
        $item->appendChild($itemx);
        if (!is_array($val)){
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);

        }else {
            arrayToXml($val,$dom,$itemx);
        }
    }
    return $dom->saveXML();
}

//数据分组
function array_group_by($arr, $key){
    $grouped = array();
    foreach ($arr as $value) {
        $grouped[$value[$key]][] = $value;
    }
    if (func_num_args() > 2) {
        $args = func_get_args();
        foreach ($grouped as $key => $value) {
            $parms = array_merge($value, array_slice($args, 2, func_num_args()));
            $grouped[$key] = call_user_func_array('array_group_by', $parms);
        }
    }

    return $grouped;
}

//判断2个数组是否相等
function compair_arr_diff($arr1,$arr2){
    if(count($arr1)!=count($arr2))
    {
        return false;
    }
    array_multisort($arr1);
    array_multisort($arr2);

    if($arr1==$arr2){
        return true;
    }
    return false;
}

function nip(){
    $host_name = exec("hostname");
    $host_ip = gethostbyname($host_name);
    return $host_ip;
}