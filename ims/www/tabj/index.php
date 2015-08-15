<?php
require_once(dirname(__FILE__).'/../../../common/config/tabj.php'); // change this line for configuration
require_once(dirname(__FILE__) . '/../../../common/globals.php');
require_once(dirname(__FILE__).'/../../../common/lib/yii/yii.php');

$config_app=require(dirname(__FILE__).'/../../config/main.php'); // change this line for configuration
Yii::createWebApplication($config_app)->run();
?>
