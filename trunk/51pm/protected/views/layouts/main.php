<?php	include('_header.php'); ?>
<body class="firefox tickets-index">
<div id="container">
<div id="header" class="clear">
<ul id="sec-nav">
	<?php if ($this->isAdmin()) { ?>
	<li><a href="<?php echo Yii::app()->request->baseUrl; ?>/user"><?php echo Yii::t('app', 'user.admin'); ?></a></li>
	<?php } ?>
	<?php if ($this->isSiteAdmin()) { ?>
	<li><a href="<?php echo $this->createUrl('/admin/companies'); ?>"><?php echo Yii::t('app', 'system.admin'); ?></a></li>
	<?php } ?>
</ul>
<div id="titles">
<h1>
	<?php if (empty(Yii::app()->session['currentProject']) || $this->menutype == 'none') { ?>
	<a class="pname" href="<?php echo Yii::app()->request->baseUrl; ?>/"><?php echo Yii::app()->user->userRecord->company->companyName; ?></a>		
	<?php } else { ?>		
	<strong id="account-name"><a href="<?php echo Yii::app()->request->baseUrl; ?>/"><?php echo Yii::app()->user->userRecord->company->companyName; ?></a></strong> 
	<a	href="<?php echo $this->createUrl('/project/show', array('id'=>Yii::app()->session['currentProject']->id,)); ?>" class="pname"><?php echo Yii::app()->session['currentProject']->projectName;?></a>
<?php } ?>		
</h1>
</div>


<div id="rheader">
	<div id="userbadge"><a href="#"><img class="avatar"	src="<?php echo User::currentAvatar(); ?>" /></a>
		<div class="ubody">
			<div id="ublinks">
			<a href="<?php echo $this->createUrl('/user/myprofile');  ?>"><?php echo Yii::t('app', 'my.profile'); ?></a>
			|	
			<a href="<?php echo $this->createUrl('/site/logout'); ?>"><?php echo Yii::t('app', 'logout');?></a> 
		</div>
		<strong><?php echo Yii::app()->user->userRecord->userName; ?></strong>
		<select id="projects">
			<option value="0"></option>
			<?php $projects = Yii::app()->user->userRecord->projects;			
			
			foreach ($projects as $p):
			?>
			<option value="<?php echo $p->id;?>"><?php echo $p->projectName;?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
</div>
</div>

<div id="content">
<div id="main">
	<?php 
		if ($this->menutype != 'none') {
		$this->widget('MainMenu', 
					array('menutype'=>$this->menutype,
				  'activemenu'=>$this->activemenu,
				  'currentProject'=>$this->currentProject,		
	)); } ?> 
	<?php echo $content; ?>
</div>

<div id="sbar"><?php if ($this->menutype == 'project') {?> 
	<span	class="sbar-btn"><a	href="<?php echo $this->createUrl('/ticket/create', array('project'=>$this->currentProject->id,)); ?>">
		<img	src="<?php echo Yii::app()->request->baseUrl; ?>/images/new-ticket.png" /><?php echo Yii::t('app', 'ticket.create');?></a>
	</span>
	<div class="proj-desc">
		<p><?php echo $this->currentProject->projectDesc;?></p>
	</div>

<div class="sblock bin-block" id="private-bin-block">
		<h3><?php echo Yii::t('app', 'my.tickets'); ?></h3>
		<ul class="stacked wbadges sortable" id="user-ticket-bins">
			<li class="bin"><a href="<?php echo $this->createUrl('/ticket/list', array('s'=>'mywatch',)); ?>"><span class="badge"><?php echo Ticket::model()->getShortcutTicketCount('mywatch'); ?></span> <?php echo Yii::t('app', 'ticket.mywatch'); ?></a></li>
			<li class="bin"><a href="<?php echo $this->createUrl('/ticket/list', array('s'=>'my',)); ?>"><span class="badge"><?php echo Ticket::model()->getShortcutTicketCount('my'); ?></span> <?php echo Yii::t('app', 'ticket.my'); ?></a></li>
		</ul>
</div>

<div class="sblock bin-block" id="shared-bin-block">
	<h3><?php echo Yii::t('app', 'ticket.overview');?></h3>
	<ul class="stacked wbadges sortable">
		<li class="bin shared"><a href="<?php echo $this->createUrl('/ticket/list', array('s'=>'all',)); ?>"><span class="badge"><?php echo Ticket::model()->getShortcutTicketCount('all'); ?></span><?php echo Yii::t('app', 'ticket.all');?></a></li>
		<li class="bin shared"><a href="<?php echo $this->createUrl('/ticket/list', array('s'=>'open',)); ?>"><span class="badge"><?php echo Ticket::model()->getShortcutTicketCount('open'); ?></span><?php echo Yii::t('app', 'ticket.opened');?></a></li>
		<li class="bin shared"><a href="<?php echo $this->createUrl('/ticket/list', array('s'=>'today',)); ?>"><span class="badge"><?php echo Ticket::model()->getShortcutTicketCount('today'); ?></span><?php echo Yii::t('app', 'ticket.today');?></a></li>
	</ul>
</div>

<div class="sblock clear"><?php $this->widget('PageList', array('type'=>1,));  ?>
</div>

<?php $this->widget('TagCloud'); ?> 
<?php } else { ?> 
<span	class="sbar-btn"><a	href="<?php echo $this->createUrl('/project/create'); ?>"
	style="margin-top: 3px; margin-bottom: 9px"><img alt="New-project"
	class="plus-sign" src="<?php echo Yii::app()->request->baseUrl; ?>/images/new-project.png" /><?php echo Yii::t('app', 'create.project'); ?></a></span>

<div class="sblock">
	<h3><?php echo Yii::t('app', 'projects');?></h3>
	 <ul class="stacked wbadges" id="projects">
	 <?php foreach($projects as $project): ?> 
	 <li class="proj"><a href="<?php echo Yii::app()->createUrl('/project/show', array('id'=>$project->id,)); ?>"><span class="badge"><?php echo $project->tickets; ?></span><?php echo $project->projectName;?></a></li> 
	<?php endforeach; ?>
	</ul>
</div>

<div class="sblock clear"><?php $this->widget('PageList', array('type'=>0,));  ?>
</div>
<?php }?></div>
</div>
<?php	include('_footer.php'); ?>

