	<div class="page-form" id="ticket-form" style="position: relative;">
		<?php if (!$update) {?>
		<dl id="ticket-title" style="" class="form lbl-inline clear">
			<dt><label for="ticket_name"><?php echo Yii::t('app', 'ticket.title');?>: </label></dt>
			<dd><input type="text" class="required" tabindex="1" size="25" name="Ticket[title]"	id="ticketTitle" /></dd>
		</dl>
		<dl class="form">
			<dt><label for="ticket_body"> <?php echo Yii::t('app', 'ticket.desc');?>  </label></dt>
			<dd><textarea tabindex="4" class="required" style="height:200px!important;" rows="15" name="Ticket[ticketDesc]" id="ticketDesc" cols="40" ></textarea>
		    </dd>
		</dl>
		<?php } else { ?>
		<dl class="form">
			<dt><label for="ticket_body"> <?php echo Yii::t('app', 'ticket.comment');?>  </label></dt>
			<dd><textarea tabindex="4"  style="height:150px!important;" rows="10" name="Ticket[ticketDesc]" id="ticketDesc" cols="40" ></textarea>
	    </dd>
		</dl>			
		<?php } ?>	
		<ul style="position: relative;" class="list-form triple clear">
			<li><label for="owner"><?php echo Yii::t('app', 'owner');?></label> 
				<select	tabindex="5" name="Ticket[owner]"	id="owner">
				
				<?php 
						$project = Yii::app()->session['currentProject']; 
						$users = $project->users;
						foreach ($users as $u) : 
				?>
				<option <?php if ($u->userId == $model->owner) {echo 'selected="selected"';}  ?> value="<?php echo $u->userId; ?>"><?php echo $u->userName; ?></option>
				<?php endforeach; ?>
				
				
			</select></li>
		
			<li><label for="milestoneId"><?php echo Yii::t('app', 'milestone');?></label> 
				<select	tabindex="6" name="Ticket[milestoneId]" id="milestoneId">
				<option selected="selected" value="0"><?php echo Yii::t('app', 'none');?></option>
				<?php $projectId = Yii::app()->session['currentProject']->id; 
					foreach (Milestone::model()->getProjectMilestones($projectId) as $m):?>
					<option <?php if ($model->milestoneId == $m->id){echo 'selected=""';}?> value="<?php echo $m->id;?>"><?php echo $m->title;?></option>
				<?php endforeach;?>
			</select></li>
				
			<li class="last"><label for="ticketStatus"><?php echo Yii::t('app', 'ticket.status');?></label> <select
				tabindex="7" name="Ticket[ticketStatus]" id="ticketStatus">
				<?php $ss = Yii::app()->session['currentProject']->getStatuses(); 
					foreach ($ss as $s): ?>
						<option <?php if ($model->ticketStatus == $s['label']) { echo 'selected=""'; }?> value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select></li>
			
			
			<li><LABEL for=ticketPriority><?php echo Yii::t('app', 'priority');?></LABEL> 
				<SELECT id="ticketPriority" tabIndex="8" name=Ticket[ticketPriority]>
					<OPTION value="high"><?php echo Yii::t('app', 'priority.high');?></OPTION> 
					<OPTION <?php if ($model->ticketPriority == 'medium') {echo 'selected=""';}?> value="medium"><?php echo Yii::t('app', 'priority.medium');?></OPTION> 
					<OPTION <?php if ($model->ticketPriority == 'low') {echo 'selected=""';}?> value="low"><?php echo Yii::t('app', 'priority.low');?></OPTION></SELECT> 
			</LI>
			
			<li><label for="duedate"><?php echo Yii::t('app', 'duedate');?></label> 
				<input	tabindex="9" name="Ticket[duedate]" id="duedate" value="<?php echo $model->duedate;?>"></li>
				
			<li class="last"><label for="ticketStatus"><?php echo Yii::t('app', 'ticket.type');?></label> 
				<select	tabindex="10" name="Ticket[ticketType]" id="ticketType">
				<?php $ss = Yii::app()->session['currentProject']->getTypes(); 
					foreach ($ss as $s): ?>
						<option <?php if ($model->ticketType == $s['label']) { echo 'selected=""'; }?> value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select></li>
		</ul>
		<dl id="taggings" class="form lbl-inline clear">
			<dt><label for="tags"><?php echo Yii::t('app', 'tags');?>:</label></dt>
			<dd>
			<input type="text" value="<?php echo CHtml::encode($model->tags); ?>" tabindex="12" name="tags[]" class="tag_box" id="tags"  />
			</dd>
		</dl>


    <dl class="form optional">
    	<dt style="display: none;"></dt>
	    <dd id="attach-field">
	      <ul id="attach-fields">
	        <li id="attachment-1">
	          <label for="model_attachment"><?php echo Yii::t('app', 'attach.file'); ?> <span class="quiet">[50MB limit]</span>: </label>
	          <input type="file" tabindex="9" size="30" name="attachment[]" id="message_attachment"/> 
	          <a href="#" class="add">(+)</a>
	        </li>
	      </ul>
	    </dd>
  	</dl>

		<?php if ($update) { ?>
		<dl id="ticket-title" class="form lbl-inline clear" style="display:none;">
		  <dt><label for="ticket_name"><?php echo Yii::t('app', 'change.title'); ?>: </label></dt>
		  <dd><input type="text"  value="<?php echo CHtml::encode($model->title); ?>" tabindex="10" size="25" name="Ticket[title]" id="ticketTitle"/>
		  		<input type="hidden" name="id" value="<?php echo $model->id; ?>" />	
		  </dd>
		</dl>
	<?php } ?>
	
	<div style="display: none;" id="select-watchers">
		<div class="watch-block" id="watcher-form">
			<p><strong><?php echo Yii::t('app', 'notify.by.email'); ?></strong></p>
			<div class="csshidden2" id="watcher-area">
				<p class="quiet"><?php echo Yii::t('app', 'notify.desc'); ?></p>
				<ul class="checkbox-list clear">
					<li class="everybody"><label><input type="checkbox" value="_all_"
						tabindex="10" <?php if ($model->isNotifyAll()) {echo 'checked="checked" '; } ?> name="notifyall" id="notifyall" />
						<?php echo Yii::t('app', 'notify.all'); ?></label></li>
						
					<li class="or"><strong><?php echo Yii::t('app', 'or'); ?></strong> <?php echo Yii::t('app', 'choose.members'); ?>:</li>

					<?php	
					$nl = $model->getNotifyList(); 
					foreach ($users as $u) : ?>
						<li><label><input type="checkbox" value="<?php echo $u->userId; ?>"
						  <?php if (in_array($u->userId, $nl)) {echo 'checked=""'; } ?>
							name="notify[]" class="watcher" />
							<?php echo $u->userName; ?></label></li>						
					<?php endforeach; ?>	
						
				</ul>
			</div>
		</div>
	</div>
	
	
	<ul class="optional-tasks">
		<?php if ($update)  {?>
		<li><a tabindex="11" title="Change Title" class="changeTitle" href="#ticket-title"><?php echo Yii::t('app', 'change.title'); ?>…</a></li>
		<?php } ?>
		<?php 
			$asadmin = !(strpos(Yii::app()->user->userRecord->roles, 'ROLE_ADMIN') === false); 
			if ($asadmin  || !$update || $model->createdBy == Yii::app()->user->userRecord->userId) { 
				?>
		<li><a tabindex="12" href="#select-watchers" class="changeNotify"><?php echo Yii::t('app', 'change.notification'); ?>…</a></li>
		<?php } ?>
	</ul>
	</div>
	<p style="clear: left;" class="btns">
		<?php if ($update) { ?>
			<input type="submit" value="<?php echo Yii::t('app', 'btn.update');?>" tabindex="8" name="commit" /> 
    <?php } else { ?>			
    	<input type="submit" value="<?php echo Yii::t('app', 'btn.create');?>" tabindex="8" name="commit" /> 
    	<?php echo Yii::t('app', 'or'); ?>
 		  <a href="<?php echo $this->createUrl('/ticket/list'); ?>"><?php echo Yii::t('app', 'btn.cancel');?></a></p>
    <?php } ?>	

<div id="dummy"></div>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />
<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />
<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js"
	type="text/javascript"></script>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js"
	type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.tagbox.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.simplemodal.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker.js" type="text/javascript"></script>


<script>
$(document).ready(function()	{
   $('#ticketDesc').markItUp(mySettings);
   $('.tag_box').tag_box();
   $('.add').click(function() {
	   	return Attachment.add('<?php echo Yii::t('app', 'attach.file'); ?>');	   	
   });
   $('.changeTitle').click(function() {
   	$('#ticket-title').toggle();
   	return false;
  }); 

   $('.changeNotify').click(function() {
   	$('#select-watchers').toggle();
   	return false;
  }); 

	$('a.deletelink').click(function (e) {
		e.preventDefault();
		$('#deletedlg').modal({
			overlayId: 'osx-overlay',
			containerId: 'osx-container',
			closeHTML: '<div class="close"><a href="#" class="simplemodal-close">x</a></div>',
			minHeight:100,
			opacity:65, 
			overlayClose:true,
			onClose:OSX.close
			});
	});


	$('#duedate').DatePicker({
	<?php if (LocaleManager::isChinese()) { ?>
		format:'Y-m-d',
		locale: {
			days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
			daysShort: ["日", "一", "二", "三", "四", "五", "六", "日"],
			daysMin:   ["日", "一", "二", "三", "四", "五", "六", "日"],
			months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
			weekMin: '周'
		}, 
	<?php } else { ?>
		format:'m/d/Y',
	<?php }?>	
		date: $('#duedate').val(),
		current: $('#duedate').val(),
		starts: 1,
		calendars: 2,
		onBeforeShow: function(){
			var v = jQuery.trim($('#duedate').val());
			<?php if (LocaleManager::isChinese()) { ?>
			var dreg = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;
			<?php } else { ?>
			var dreg = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/;
			<?php }?>	 
			if (dreg.test(v)) {
				$('#duedate').DatePickerSetDate(v, true);
			}
		},
		onChange: function(formated, dates){
			$('#duedate').val(formated);			
		  $('#duedate').DatePickerHide();
			
		}
	});

	Attachment.delsetup();
	
	$('.xdel').click(function(e) {
		Attachment.delatt(e);
	}); 

	$('#notifyall').click(function() {
		if (this.checked) {
				$(".watcher").attr("checked", false);
		}
	}); 
	$('.watcher').click(function() {
		if (this.checked) {
				$('#notifyall').attr("checked", false);
		}
	});
	
	<?php if (!$model->isNewRecord) { ?>
	$('#iwatch').click(function()  {
		var t1 = '<?php echo Yii::t('app', 'watch.ticket'); ?>'; 
		var t2 = '<?php echo Yii::t('app', 'stop.watch.ticket'); ?>'; 
		if ($(this).val() == t1) {
			$(this).val(t2); 
		}
		else {
			$(this).val(t1); 
		}
		$.ajax({
			 url: '<?php echo $this->createUrl('/ticket/watch'); ?>',  
			 data: ({id : <?php echo $model->id; ?>}),
       type:'POST'
		}); 	
		
	});
	
<?php } ?> 
});
</script>
