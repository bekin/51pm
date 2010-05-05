<form method="post" id="searchform" class="bulk-form" action="<?php echo $this->createUrl('/ticket/list'); ?>">
	<ul class="list-form half clear" style="position: relative;">
		<li>
			<label for="owner"> <?php echo Yii::t('app', 'owner');?></label>
					<select name="owner" id="owner">
							<option value=""></option>
							<?php 
									$project = Yii::app()->session['currentProject']; 
									$users = $project->users;
									foreach ($users as $u) : 
							?>
							<option value="<?php echo $u->userId; ?>"><?php echo $u->userName; ?></option>
							<?php endforeach; ?>
					</select>
		</li>
		<li  class="last"><label for="milestoneId"> <?php echo Yii::t('app', 'milestone');?></label> 
				<select	name="milestoneId" id="milestoneId">
				<option selected="selected" value=""></option>
				<?php $projectId = Yii::app()->session['currentProject']->id; 
					foreach (Milestone::model()->getProjectMilestones($projectId) as $m):?>
					<option value="<?php echo $m->id;?>"><?php echo $m->title;?></option>
				<?php endforeach;?>
		</select></li>
		<li><label for="ticketStatus"> <?php echo Yii::t('app', 'ticket.status');?></label> <select
				name="ticketStatus" id="ticketStatus">
				<option selected="selected" value=""></option>
				<?php $ss = Yii::app()->session['currentProject']->getStatuses(); 
					foreach ($ss as $s): ?>
						<option value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select></li>
		<li  class="last"><LABEL for=ticketPriority> <?php echo Yii::t('app', 'priority');?></LABEL> 
				<SELECT id="ticketPriority" name="ticketPriority">
					<option selected="selected" value=""></option>
					<OPTION value="high"><?php echo Yii::t('app', 'priority.high');?></OPTION> 
					<OPTION value="medium"><?php echo Yii::t('app', 'priority.medium');?></OPTION> 
					<OPTION value="low"><?php echo Yii::t('app', 'priority.low');?></OPTION></SELECT> 
			</li>
		<li><label for="duedate"><?php echo Yii::t('app', 'duedate');?></label> 
				<input name="duedate" id="duedate" ></li>
				
		<li  class="last"><label for="ticketType"> <?php echo Yii::t('app', 'ticket.type');?></label> 
			<select name="ticketType" id="ticketType">
			<option selected="selected" value=""></option>
				<?php $ss = Yii::app()->session['currentProject']->getTypes(); 
					foreach ($ss as $s): ?>
						<option value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select>
		</li>
		<li>
			<label><?php echo Yii::t('app', 'ticket.title');?>: </label>
			<input type="text" name="title"	 />
		</li>
				
		<li class="last">
			<label for="tags"> <?php echo Yii::t('app', 'tags');?>:</label>
			<input type="text" value=""  name="tags"  />		
		</li>
	</ul>	
	<div class="link-bar">
 	<input style="margin-right: 11px;" class="submit-button" type="submit" value="<?php echo Yii::t('app', 'search');?>" name="commit" id="btnbulk" /> 
 	</div>
</form>
