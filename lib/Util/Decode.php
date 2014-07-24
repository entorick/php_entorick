<?php
/*
 * 各种decode相关的通用函数
 * @author entorick11@qq.com
 */

class Util_Decode{
    /*
     * 字符串在各层之间被多次htmlspecialchars
     * 用此方法decode
     * @params string $str    // 要转化的字符串
     * @parasm int    $level  // 反解的层级，默认false，表示无限
     */
    public static function multiHtmlSpecialCharsDecode($str, $level = false){
        while($str != htmlspecialchars_decode($str)){
            $str = htmlspecialchars_decode($str);
            if ($level !== false && $level > 0){
                $level--;
            } elseif ($level !== false){
                return $str;
            }
        }
        return $str;
    }
}
