<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'51PM',

	'timezone'=>'America/Chicago',

	'sourceLanguage'=>'en_us',
	'language'=>'zh_cn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.controllers._BaseController',
		'application.models.DbOptimisticLockingException',
		'application.models.ResultPage',
	),

	// application components
	'components'=>array(
		/*'errorHandler'=>array(
       'errorAction'=>'site/error',
    ),*/

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info, trace',
					'maxFileSize'=>5000,
					'maxLogFiles'=>30,
				),
			),
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			// specify the login URL here
			'loginUrl'=>array('site/login'),
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array (
				'auth'=>'site/auth',
				'login'=>'site/login',
				'logout'=>'site/logout',
				'rss'=>'site/rss',
				'project/update/<id:\d+>'=>'project/update',
				'project/<id:\d+>'=>'project/show',
		
				'page/<id:\d+>'=>'page/show',
				'page/update/<id:\d+>'=>'page/update',

				'messages'=>'message/list',		
				'message/<id:\d+>'=>'message/show',
				'message/update/<id:\d+>'=>'message/update',

				'milestones'=>'milestone/list',		
				'milestone/<id:\d+>'=>'milestone/show',
				'milestone/update/<id:\d+>'=>'milestone/update',
		
				'ticket/list/<project:\d+>'=>'ticket/list',		
				'ticket/<id:\d+>'=>'ticket/show',
				'ticket/update/<id:\d+>'=>'ticket/update',
				'ticket/edit/<id:\d+>'=>'ticket/edit',
		
				'user/<id:\d+>'=>'user/show',
			),
		),
		'db'=>array(
			'connectionString'=>'mysql:host=localhost;dbname=phoenix',
			'username'=>'lead',
			'password'=>'pass5188',
			'charset'=>'utf8',
			'enableParamLogging'=>true
		),
		'session'=>array(
			'autoStart'=>true,
		),
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// file storage location
		'contentRoot'=>'protected' . DIRECTORY_SEPARATOR . 'content',
	
		// avatar root: must be an public-accessible directory
		'avatarRoot'=>'avatar',
		
		// parameters for email
		'adminEmail'=>'admin@yourcompany.com', 
		'adminName'=>'Administrator', 
		'from'=>'lead.phoenix@dllead.com',
		'fromname'=>'phoenix.lead.com',

		'mailEngine'=>'smtp', 
		'username'=>'lead.phoenix@dllead.com',
		'password'=>'pass5188',
		'smtp_host'=>'smtp.gmail.com',
		'smtp_port'=>465,
	
		// default active menu show
		'activemenu'=>'site',
	),
);