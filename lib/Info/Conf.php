<?php
/**
 * 处理仿AP的配置文件
 * xx.xx.0="123"  这种形式的
 * @author entorick11@qq.com
 */
class Info_Conf{
    private $basePath = '';// 根目录
    private static $instance = null;
    private static $cache;


    /**
     * 构造方法，初始化配置文件所在的根目录
     */
    private function __construct($basePath = false){
        if ($basePath === false || !is_dir($basePath)){
            $this->basePath = dirname(__FILE__) . '/../conf/';
        } else {
            $this->basePath = $basePath;
        }
    }


    /**
     * 处理配置文件
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
            $value = str_replace(array("\"", "\'"), "", $value);
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
            self::$instance = new Conf();
        }
        if (!isset(self::$cache[$filename])) {
            $objConf = self::$instance->processConfFile(self::$instance->basePath . $filename);
            self::$cache[$filename] = $objConf;
        }
        
        $confVal = self::$cache[$filename];
        
        $arrKey = explode('.', $key);
        foreach ($arrKey as $val){
            $confVal = $confVal[$val];
        }
        
        return $confVal;
    }

}
