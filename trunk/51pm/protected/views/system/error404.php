<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon"
	href="<?php echo Yii::app()->request->baseUrl; ?>/images/logoicon.png"
	type="image/gif" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/site.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/combo.css" />
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie6.css" />
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie7.css" />
<![endif]-->
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.3.2.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.form.js"
	type="text/javascript"></script>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combo.js"
	type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"
	type="text/javascript"></script>
<title><?php echo Yii::t('app', '404'); ?> - <?php echo Yii::app()->name;?></title>
</head>

<body class="firefox tickets-index">
<div id="container">
<div id="header" class="clear">
<div id="titles">
<h1>
	<a class="pname" href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo Yii::app()->user->userRecord->company->companyName; ?></a>		
</h1>
</div>

</div>

<div id="content">
<div id="main-content" class="clear">
	<h2 class="error404"><?php echo Yii::t('app', 'error.404'); ?></h4>
	<div>
	<?php echo Yii::t('app', 'error.404.desc'); ?>
	</div>
	
	<div style="margin:30px;font-size:14px;">
		<a href="<?php echo Yii::app()->request->baseUrl; ?>">=&gt;<?php echo Yii::t('app', 'back.to.home'); ?></a>
	</div>
</div>
</div>
</div>
	
<?php	
	$sharedFooter=dirname(__FILE__).'/../layouts/_footer.php';
	require_once($sharedFooter); 
 ?>