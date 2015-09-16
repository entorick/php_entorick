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
}
