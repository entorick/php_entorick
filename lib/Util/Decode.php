<?php
/*
 * decode相关的通用函数
 * @author entorick11@qq.com
 */

class Util_Decode{
    /*
     * 字符串在各层之间被多次htmlspecialchars
     * 用此方法decode
     */
    public static function multiHtmlSpecialCharsDecode($str){
        while($str != htmlspecialchars_decode($str)){
            $str = htmlspecialchars_decode($str);
        }
        return $str;
    }
}
