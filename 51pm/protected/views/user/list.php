<div id="project-nav"><ul><li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'user.admin'); ?></a></li></ul></div>


<div id="page-top"></div>
<div id="main-content" class="clear">

<ul class="tabs clear" id="member-tabs" style="margin-top: 10px;">
	<li><a id="projecttab" class="active" href="#all"><?php echo Yii::t('app', 'user.permission'); ?></a></li>
	<li><a id="invitetab" class="" href="#all"><?php echo Yii::t('app', 'user.invite'); ?></a></li>
</ul>

<div style="" id="project">
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<ul id="members-list">
<?php 
	$_now = time(); 
	$projects = Project::model()->findProjects(Yii::app()->user->userRecord->companyId);
?>
<?php foreach($models as $n=>$model): ?>
	<li id="li_<?php echo $model->userId;?>" class="member <?php if($model->active==0){echo 'pending';} ?> clear">
		<img class="avatar" src="<?php echo Yii::app()->request->baseUrl; ?>/images/avatar.gif">
		<?php echo $model->email ?>
		<br>
		<?php if($model->active==0){ ?>
			<span class="pending-badge"><?php echo Yii::t('app', 'pending');  ?></span>
				<a href="javascript:;" onclick="javascript:delInvite('<?php echo $model->userId ?>', '<?php echo $model->email ?>');"><?php echo Yii::t('app', 'btn.cancel'); ?></a> 
			
			<span id="ri_pad_<?php echo $n;?>" class="help"><?php
			echo Yii::t('app', 'last.invited'); 
        	$ops = array();
        	if (LocaleManager::isChinese())
        	{
        		$ops['format'] = 'Y-m-d'; 
        	}
        	else
        	{
        		$ops['format'] = 'M d, Y'; 	
        	}
        	echo Time::timeAgoInWords($model->lastLogin, $ops);?></span>
			<a href="javascript:void(0);" class="resend" onclick="javascript:resendInvite('<?php echo $model->email ?>','<?php echo $n ?>');"><?php echo Yii::t('app', 'invite.resend'); ?></a>
		<?php }else if ($model->active == -1) { ?>
			
		<?php 
			}
			else
			{ 
				$asadmin = !(strpos($model->roles, 'ROLE_ADMIN') === false); 
		?>
			<span class="roles help"><?php echo $model->roles;?></span>
			<a href="#" class="projectlink" rel="<?php echo $model->userId;?>">&raquo; <?php echo Yii::t('app', 'member.of.projects'); ?></a> 		
			<div class="toggledlg">
				<form class="memberform" action="<?php echo $this->createUrl('/user/membership'); ?>" method="post">
				<input type="hidden" name="userId" value="<?php echo CHtml::encode($model->userId);?>" />
				<input type="hidden" name="companyId" value="<?php echo $model->companyId;?>" />
				<ul>
					<?php foreach ($projects as $p) { ?>
					<li><input type="checkbox" 
					<?php foreach ($model->committees as $c): if ($c->projectId == $p->id) { ?>
					checked="checked""
					<?php } endforeach; ?>
					name="member[]" value="<?php echo $p->id;?>"> <?php echo $p->projectName;?></li>
					<?php } ?>
				</ul>
				<input type="submit" class="set-membership-btn" value=" <?php echo Yii::t('app', 'btn.save');?> " >
				<div class="cleardiv"></div>
				</form>
			</div>
			
			<a class="set-perm-btn <?php if ($asadmin) echo 'admin'?>" href="#" rel="<?php echo $model->userId; ?>">
				<?php if (strpos($model->roles, 'ROLE_ADMIN') === false): ?>
		         <span class="expando">&raquo;</span> <?php echo Yii::t('app', 'set.as.admin');?>
		        <?php else: ?>
		        <span class="expando">&raquo; </span> <?php echo Yii::t('app', 'set.as.nonadmin');?>
		        <?php endif;?> 
		    </a>
		<?php } ?>
	</li>
<?php endforeach; ?>
</ul>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
</div>

<div style="display: none;" id="invite">
<form action="<?php echo $this->createUrl('/user/invite'); ?>" method="post">
<div style="margin: 0pt; padding: 0pt;"></div>

<div class="group">
<div id="invite-form">
<dl>
	<dt>
		<label for="emails"><?php echo Yii::t('app', 'invite.desc1');?></label>
		<p class="hint"><?php echo Yii::t('app', 'invite.desc2');?></p>
	</dt>
	<dd><input value="" class="big" id="emails" name="InviteForm[emails]" style="width: 70%;" type="text"></dd>
	<dt><label for="message"><?php echo Yii::t('app', 'invite.desc3');?></label>
	</dt>
	<dd><textarea cols="75" id="message" name="InviteForm[message]" rows="10"></textarea>
	</dd>
	<dt><label> 
		<input id="invite-account-member" name="InviteForm[privilege]" value="1" type="checkbox">
		<strong><?php echo Yii::t('app', 'invite.desc4');?></strong> </label>
		<p class="quiet"><?php echo Yii::t('app', 'invite.desc5');?></p>
	</dt>
	
</dl>
	<p id="btns" class="btns">
		<input name="commit" value="<?php echo Yii::t('app', 'invite.send');?>" type="submit">
	</p>
</div>
</div>
</form>
</div>

</div>
<script>
$(function() {
	$('#projecttab').click(function(){activate('project');});
	$('#invitetab').click(function(){activate('invite');});

	$('.projectlink').click(function() {
		$(this).parent().find('.toggledlg').toggle();

		return false;
	});

	$('form.memberform').ajaxForm({
		beforeSubmit: function(formData, jqForm, options) {
			jqForm.parent().toggle();
			return true;
		}		
	}); 
	
	$('.set-perm-btn').click(function() {
		$.ajax({
			 url: '<?php echo $this->createUrl('/user/toggleperm'); ?>',  
			 data: ({userid : $(this).attr('rel')}),
       type:'POST'
		}); 		
		
		if ($(this).hasClass('admin'))
		{
			$(this).removeClass('admin'); 
			$(this).html('<span class="expando">&raquo;</span> <?php echo Yii::t('app', 'set.as.admin');?>'); 
			$(this).parent().children('.roles').html('');
		}
		else {
			$(this).addClass('admin'); 
			$(this).html('<span class="expando">&raquo; </span> <?php echo Yii::t('app', 'set.as.nonadmin');?>'); 
			$(this).parent().children('.roles').html('ROLE_ADMIN');
		}	
		
	});
});
function deactive(){
	$('#projecttab').removeClass('active');
	$('#invitetab').removeClass('active');
	$('#project').hide();
	$('#invite').hide();
}
function activate(target){
	var tabid = '#' + target + 'tab';
	var divid = '#' + target;
	deactive();
	$(tabid).addClass('active');
	$(divid).show();	
}

function delInvite(uid, uemail) {
	if(uid!='' && confirm('<?php echo Yii::t('app', 'delete.invite.confirm');?>')) {
		$('#li_'+ uid).hide('slow');
		$.ajax({
		    url: '<?php echo $this->createUrl('/user/delinvite'); ?>',
		    data: ({email : uemail}),
	      	type:'POST'
		});		
	}
}

var lastindex = '';
function resendInvite(rowid,index){
	var u = '<?php echo $this->createUrl('/user/reinvite');?>?email=' + rowid;
	lastindex = index;
	$.ajax({
		url:u,
		type:'POST',
		async:false,		
		success: function(req){			
			$('#ri_pad_'+lastindex).html('<font color=red><?php echo Yii::t('app', 'reinvited');?></font>');
		}
	});
}

</script>
