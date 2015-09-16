<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 15/7/22
 * Time: 下午6:35
 */
require_once("conf/Const.php");
class Init{
    public static function loadlibrary($className){
        if (!class_exists($className)){
            $curPath = dirname(__FILE__);
            $arr = explode("_", $className);
            $filePath = implode("/", $arr) . ".php";
            if (file_exists($curPath . "/" . $filePath)){
                require_once($curPath . "/" . $filePath);
            }
        }
    }
}

spl_autoload_register(array('Init', 'loadlibrary'));
