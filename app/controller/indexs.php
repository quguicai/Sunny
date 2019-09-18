<?php
namespace app\controller;
use Sunny\ext\db\Db;

/**
 * Class indexs
 * @RequestMap('/user')
 */
class indexs{
	/**
	  *@RequestMap('/aa')
	*/
	public function index(){
	    $a = Db::query('db2','select *  from sku_tab limit 2');
	    echo'<pre>';
	    print_r($a);
	}

    /**
     *@RequestMap('/add')
     */
    public function index2(){
       echo '/user/add';
    }

    /**
     *@RequestMap('/del')
     */
    public function index3(){
        echo '/user/del';
    }
}