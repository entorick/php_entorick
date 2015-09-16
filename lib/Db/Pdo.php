<?php
require_once('Zend/Db/Adapter/Pdo/Mysql.php');
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 15/6/25
 * Time: 上午11:35
 */
class Db_Pdo extends Zend_Db_Adapter_Pdo_Mysql{
    /**
     * @param array|Zend_Config $addConf
     * array(
     *   'dbkey'  => '',
     *   'host' => '',
     *   'port' => '',
     *   'user' => '',
     *   'pass' => '',
     * )
     */
    public function __construct($addConf){
        $pdoConf = array(
            'username' => $addConf['user'],
            'password' => $addConf['pass'],
            'host' => $addConf['host'],
            'port' => $addConf['port'],
            'dbname' => '',
            'driver_options' => array(
                1002 => 'set names utf8',
            ),
        );
        parent::__construct($pdoConf);
    }
}