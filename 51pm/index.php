<?php
error_reporting(E_ALL ^ E_NOTICE);

// change the following paths if necessary
$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
// use third party components
// MAILER
require_once(dirname(__FILE__).'/protected/components/mailer/class.phpmailer.php');

Yii::createWebApplication($config)->run();
