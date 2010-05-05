<?php if ($this->menutype == 'project') { ?>
<div id="project-nav">
	<ul>
		<li  class="submenu-tab <?php echo $this->activemenu == 'overview' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->createUrl('/project/show', array('id'=>Yii::app()->session['currentProject']->id,)); ?>" title=""><?php echo Yii::t('app', 'menu.overview');?></a>
        </li>
        <li class="submenu-tab  <?php echo $this->activemenu == 'tickets' ? ' selected' : ''?>" id="t-menu">
              <a href="<?php echo Yii::app()->createUrl('/ticket/list', array('project'=>Yii::app()->session['currentProject']->id,)); ?>"><?php echo Yii::t('app', 'menu.tickets');?></a>
		</li>
		<li  class="submenu-tab  <?php echo $this->activemenu == 'messages' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->createUrl('/message/list', array('project'=>Yii::app()->session['currentProject']->id,)); ?>"><?php echo Yii::t('app', 'menu.messages');?></a>
        </li>
		<li  class="submenu-tab  <?php echo $this->activemenu == 'milestones' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->createUrl('/milestone/list', array('project'=>Yii::app()->session['currentProject']->id,)); ?>" title=""><?php echo Yii::t('app', 'menu.milestone');?></a>
        </li>
		<li id="quick-search"><a title="<?php echo Yii::t('app', 'search');?>" href="#">Search</a></li>	
	</ul>
</div>
<?php } else { ?>

<div id="project-nav">
	<ul>
		<li  class="submenu-tab <?php echo $this->activemenu == 'dashboard' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->request->baseUrl; ?>/" ><?php echo Yii::t('app', 'menu.dashboard');?></a>
        </li>        
		<li  class="submenu-tab <?php echo $this->activemenu == 'messages' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->createUrl('/message/list'); ?>"><?php echo Yii::t('app', 'menu.messages');?></a>
        </li>
		<li  class="submenu-tab  <?php echo $this->activemenu == 'milestones' ? ' selected' : ''?>" id="t-proj">
        	<a href="<?php echo Yii::app()->createUrl('/milestone/list'); ?>" title=""><?php echo Yii::t('app', 'menu.milestone');?></a>
        </li>
	</ul>
</div>
<?php } ?>