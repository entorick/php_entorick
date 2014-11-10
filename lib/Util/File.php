<?php
/*
 * 文件操作相关的工具类
 * @author entorick11@qq.com
 */

class Util_File{

    /**
     * 递归遍历全部文件夹
     * 默认遍历当前文件夹
     * 第二参数是处理文件的方法，第三参数是这个方法属于的类
     *
     * @params string $path         要遍历的根路径
     * @params string $handleMethod 找到文件时的处理函数，默认false，表示就输出一下文件名
     * @params string $classname    处理函数所属的类，默认false，表示不是类方法
     * @return null
     */
    public static function traverse($path = '.', $handleMethod = false, $classname = false){
        $current_dir = opendir($path);                         // opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false){      // readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    // 构建子目录路径
            if ($file == '.' || $file == '..'){
                continue;
            } elseif (is_dir($sub_dir)){                       // 如果是目录,进行递归
                traverse($sub_dir);
            } else {                                           // 如果是文件,做想做的事
                if ($handleMethod === false){ // 没有处理函数就直接输出文件名了
                    echo $path . DIRECTORY_SEPARATOR . $file . "\n";
                    continue;
                }
                $obj = null;
                if ($classname !== false){
                    if (!class_exists($classname)){
                        // @todo need log here
                        exit(0);
                    }
                    $obj = new $classname;
                }

                if (is_null($obj)){ // 处理方法不在类中
                    try{
                        call_user_func_array($handleMethod, array($path . DIRECTORY_SEPARATOR . $file));
                    }catch (Exception $e){
                        // 调用出错 @todo 可以加日志
                        echo $e->getMessage();
                    }
                } else { // 处理方法在类中
                    try{
                        call_user_func_array(array($obj, $handleMethod), array($path . DIRECTORY_SEPARATOR . $file));
                    }catch (Exception $e){
                        // 调用出错 @todo 可以加日志
                        echo $e->getMessage();
                    }
                }
            }
        }
    }

    /**
     * 获取一个文件的全部行，以array返回
     * entorick11@qq.com
     *
     * @param string $path // 文件路径
     * @param bool   $tab  // 每行是否按tab分割
     *
     * @return array // 文件行
     */
    public static function getFileLineArr($path, $tab = false){
        if (!file_exists($path)){
            return array();
        }
        $fileHandle = fopen($path, "r");
        $result = array();
        while(!feof($fileHandle)){
            $line = trim(fgets($fileHandle));
            if ($tab){
                $line = explode("\t", $line);
            }
            $result[] = $line;
        }
        return $result;
    }

}
