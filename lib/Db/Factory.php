<?php
/**
 * 数据库工厂类，用户获取数据库Db_Pdo实例对象
 * Created by PhpStorm.
 * User: entorick11
 * Date: 15/6/25
 * Time: 上午11:09
 */
class Db_Factory{

    /**
     * 数据库Db_Pdo类集合
     * @var array
     */
    private static $_arrPdo;

    /**
     * 获取Db_Pdo实例
     * @param array $addrConf 数据库对应配置
     * array(
     *   'dbkey'  => '',
     *   'host' => '',
     *   'port' => '',
     *   'user' => '',
     *   'pass' => '',
     * )
     * @return Db_Pdo
     */
    public static function getInstance($addrConf){
        if (!is_array($addrConf) || empty($addrConf['dbkey'])){
            return null;
        }
        $objPdo = self::$_arrPdo[$addrConf['dbkey']];
        if (!is_a($objPdo, 'Db_Pdo')){
            $objPdo = new Db_Pdo($addrConf);
            self::$_arrPdo[$addrConf['dbkey']] = $objPdo;
        }
        return $objPdo;
    }
}