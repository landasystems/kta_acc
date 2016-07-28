<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
require_once(dirname(__FILE__) . '/../../../common/config/tci.php'); // change this line for configuration
require_once(dirname(__FILE__) . '/../../../common/globals.php');
require_once(dirname(__FILE__) . '/../../../common/lib/yii/yii.php');

$config_app = require(dirname(__FILE__) . '/../../config/main.php'); // change this line for configuration
Yii::createWebApplication($config_app)->run();
?>
