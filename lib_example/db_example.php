<?php
require_once('../lib/init.php');


$conf = array(
    'dbkey' => 'xxx_db',
    'host' => '127.0.0.1',
    'user' => 'admin',
    'pass' => 'admin',
    'port' => '3306',
);

$dbObj = Db_Factory::getInstance($conf);
$dbObj->query("select * from xx")->fetchAll();


// other way

$conf = Db_Config::decodeMysqlString("mysql -h127.0.0.1 -P3306 -uadmin -padmin");
$dbObj = Db_Factory::getInstance($conf);


// other way

$conf = Db_Config::getByStr("mysql -h127.0.0.1 -P3306 -uadmin -padmin");
$dbObj = Db_Factory::getInstance($conf);


// final way
$dbObj = Db_Factory::getInstance(Db_Config::getByStr("mysql -h127.0.0.1 -P3306 -uadmin -padmin"));

// final way 2
$dbObj = Db_Factory::getInstance(Db_Config::getByConf("local", "./db.ini"));
