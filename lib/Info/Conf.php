<?php
/**
 * 处理仿AP的配置文件
 * xx.xx.0="123"  这种形式的
 * @author entorick11@qq.com
 */
class Info_Conf{
    private static $instance = null;
    private static $cache;


    /**
     * 构造方法，初始化配置文件所在的根目录
     */
    private function __construct(){
    }


    /**
     * 处理配置文件
     *
     * @param string $filename // file path
     *
     * @return array
     *
     */
    private function processConfFile($filename){
        if (!file_exists($filename)){
            return array();
        }

        $content = file_get_contents($filename);

        $content = explode("\n", $content);

        $patterns = "/^[a-zA-Z][^=]*=[^=]*$/"; // 配置行以字母开始,并且只有一个=号

        $result = array();

        foreach ($content as $val){
            $val = trim($val);
            // 遍历每一行
            if (!preg_match($patterns, $val)){
                continue;
            }

            $val = explode("=", $val);
            $keyline = $val[0];
            $value = $val[1];
            $value = str_replace(array("\"", "'"), "", $value);
            $keyline = explode(".", $keyline);
            $this->recursive($keyline, $result, $value);
        }
        return $result;
    }

    private function recursive(&$keyArr, &$ret, $value){
        $tmp = array_shift($keyArr);
        if (is_null($ret[$tmp])){
            $ret[$tmp] = array();
        }
        if (count($keyArr) == 0){
            if (empty($res[$tmp])){
                $ret[$tmp] = $value;
            } else {
                $ret[$tmp][] = $value;
            }
            return true;
        }
        $this->recursive($keyArr, $ret[$tmp], $value);
    }

    /**
     * 获取配置项的值
     *
     * @param string $key，ini配置文件中的key部分
     * @param string $filename，配置文件全路径名称
     * 
     * @return mixed
     */
    public static function get($key, $filename)
    {
        if (self::$instance == null){
            self::$instance = new self();
        }
        if (!isset(self::$cache[$filename])) {
            $objConf = self::$instance->processConfFile($filename);
            self::$cache[$filename] = $objConf;
        }
        
        $confVal = self::$cache[$filename];
        
        $arrKey = explode('.', $key);
        foreach ($arrKey as $val){
            $confVal = $confVal[$val];
        }
        
        return $confVal;
    }

    /**
     * 根据数组编写配置
     * @param string $key
     * @param array $confArr
     * @param string $filename
     *
     * @return bool true|false
     */
    public static function write($key, $confArr, $filename){
        if (!is_array($confArr) || !file_exists($filename)){
            return false;
        }
        $str = self::buildConfString($confArr, $key . ".");
        echo $str;
        return true;
    }

    /**
     * 递归根据传入的数组建立配置字符串
     * @param array  $confArr
     * @param string $preString
     * @return string
     */
    private static function buildConfString($confArr, $preString = ''){
        $retStr = '';
        foreach ($confArr as $k => $v){
            if (is_array($v)){
                $tmpStr = $preString . $k . ".";
                $tmpStr = self::buildConfString($v, $tmpStr);
            } else {
                $tmpStr = $preString . $k . "=" . "\"" . $v . "\"\n";
            }
            $retStr .= $tmpStr;
        }
        return $retStr;
    }

}

