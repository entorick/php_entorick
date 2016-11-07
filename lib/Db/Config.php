<?php
/**
 * 数据库配置
 */
class Db_Config{
    public static function getByConf($key, $confPath){
        $conf = Info_Conf::get($key, $confPath);
        $conf['dbkey'] = $key;
        return $conf;
    }

    /*
     * decode string like mysql -h127.0.0.1 -P3306 -uadmin -padmin
     * @return array(
     *   'dbkey' => '',
     *   'host' => '',
     *   'user' => '',
     *   'pass' => '',
     *   'port' => '',
     * )
     */
    public static function decodeMysqlString($string){
        $retArr = array(
            'dbkey' => '',
            'host' => '',
            'user' => '',
            'pass' => '',
            'port' => '',
        );
        if (empty($string)){
            return false;
        }

        for ($i = 0; $i < strlen($string); $i++){
            if ($string[$i] == '-'){
                $i++;
                $param = $string[$i];
                $paramString = '';
                while(true){
                    $i++;
                    if ($string[$i] == ' ' && empty($paramString)){
                        continue;
                    } else if ($string[$i] == ' ' || $string[$i] == null){
                        break;
                    }
                    $paramString .= $string[$i];
                }
                if ($param == 'h'){
                    $retArr['host'] = $paramString;
                } else if ($param == 'u'){
                    $retArr['user'] = $paramString;
                } else if ($param == 'p' && ord($param) > 96){
                    $retArr['pass'] = $paramString;
                } else if ($param == 'P' && ord($param) < 91){
                    $retArr['port'] = $paramString;
                }
            }
        }
        $retArr['dbkey'] = "DB" . rand(1, 9999);
        return $retArr;

    }

}
