<?php
define('APP_PATH',__dir__);
include'Sunny/sunny.php';
try{
    Sunny::loadRoute('app');
    echo '加载完成！';
}catch(Exception $e){
    echo $e->getMessage();
}
