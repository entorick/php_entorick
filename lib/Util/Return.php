<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 15/7/22
 * Time: 下午6:24
 */
class Util_Return{
    /**
     * 成功
     * @param $data
     * @return array
     */
    public static function success($data){
        return array(
            'status' => 0,
            'data' => $data,
        );
    }

    public static function err($errno, $data = false){
        return array(
            'status' => $errno,
            'data' => (!$data) ? $data : '',
        );
    }

    public static function isSuc($arr){
        if ($arr['status'] == 0){
            return true;
        }
        return false;
    }

    public static function getData($arr){
        return $arr['data'];
    }
}