<?php	include('_header.php'); ?>
<body class="firefox tickets-index">
<div id="container">
<div id="header" class="clear">
<ul id="sec-nav"></ul>
<div id="titles">
<h1>
	<a class="pname" href="#" onclick="return false;"><?php echo Yii::t('app', 'system.admin');  ?></a>		
</h1>
</div>
<div id="rheader">
	<div id="userbadge">
		<div class="ubody">
			<div id="ublinks">
	<a href="<?php echo $this->createUrl('/site/index'); ?>">Home</a>
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
	<span	class="sbar-btn"><a
	href="<?php echo $this->createUrl('/admin/createCompany'); ?>">
	<img
	alt="New-ticket"
	src="<?php echo Yii::app()->request->baseUrl; ?>/images/new-ticket.png" />
<?php echo Yii::t('app', 'create.company');?></a></span>

	

</div>
</div>
<?php	include('_footer.php'); ?>

