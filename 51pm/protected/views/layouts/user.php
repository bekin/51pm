<?php	include('_header.php'); ?>
<body class="firefox tickets-index">
<div id="container">
<div id="header" class="clear">
<ul id="sec-nav"></ul>

<div id="titles">
<h1>
	<a class="pname" href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo Yii::app()->user->userRecord->company->companyName; ?></a>		
</h1>
</div>

<div id="rheader">
	<div id="userbadge">
		<div class="ubody">
			<div id="ublinks">
	<a href="<?php echo $this->createUrl('/site/index'); ?>"><?php echo Yii::t('app', 'home'); ?></a>
	|
	<a href="<?php echo $this->createUrl('/site/logout'); ?>"><?php echo Yii::t('app', 'logout');?></a>
			</div></div></div>
</div>
</div>

<div id="content">
<div id="main">
	<?php echo $content; ?>
</div>

<div id="sbar">
	<span	class="sbar-btn"></span>
</div>
</div>
<?php	include('_footer.php'); ?>

