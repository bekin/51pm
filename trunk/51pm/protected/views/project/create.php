<div class="clear" id="main-content">

<div class="group">
<?php if ($model->isNewRecord) { ?>
<h3><?php echo Yii::t('app', 'create.project');?></h3>
<?php } else { ?>
<h3><?php echo Yii::t('app', 'edit.project');?></h3>
<?php }?>
<form method="post" id="new_project" class="new_project">
<?php echo CHtml::errorSummary($model); ?>
<table cellspacing="0" cellpadding="0" class="form-tbl">
	<tbody>
		<tr>
			<th><label for="projectName"><?php echo Yii::t('app', 'project.name');?></label></th>
			<td>
				<input type="text" size="30" name="Project[projectName]" value="<?php echo CHtml::encode($model->projectName); ?>"	id="projectName" class="big" />
			</td>
		</tr>
		<tr>
			<th><label for="projectDesc"><?php echo Yii::t('app', 'project.desc');?></label></th>
			<td><textarea rows="10" name="Project[projectDesc]"	id="projectDesc" cols="55"><?php echo CHtml::encode($model->projectDesc); ?></textarea>
			</td>
		</tr>
		<tr style="display:none;">
			<th><label for="project_archived"><?php echo Yii::t('app', 'project.type');?></label></th>
			<td>
			<ul class="radioed">
				<li><input type="radio" value="private" name="Project[projectType]" <?php echo ($model->projectType == 'private' || $model->isNewRecord) ? 'checked=""' : ''; ?> />
				<h4><label for="project_access_private">Private project</label></h4>
				<p class="hint">This projects is only viewable and editable by its
				members.</p>
				</li>
				<li><input type="radio" value="public" name="Project[projectType]" <?php echo ($model->projectType == 'public') ? 'checked=""' : ''; ?> />
				<h4><label for="project_access_public">Public project</label></h4>
				<p class="hint">This project's tickets and milestones can be viewed
				by anyone. Additionally, people can register and add new tickets.</p>
				</li>
			</ul>
			</td>
		</tr>

		
		<?php if (!$model->isNewRecord) {?>
		<tr>
    		<th><label for="dfltAssigned"><?php echo Yii::t('app', 'project.default.owner');?></label></th>
    		<td>
      		<select name="Project[dfltAssigned]" id="dfltAssigned">
      			<option value=""><?php echo Yii::t('app', 'no.default');?></option>
      			<?php foreach ($model->users as $u): ?>
						<option <?php if ($model->dfltAssigned == $u->userId){echo 'selected=""';}?>  value="<?php echo $u->userId; ?>"><?php echo $u->email; ?></option>
						<?php endforeach; ?>	
							</select>
      			<p class="hint"><?php echo Yii::t('app', 'project.default.owner.desc');?></p>
    		</td>
		</tr>

		<tr>
    		<th><label for="dlftMilestone"><?php echo Yii::t('app', 'project.default.milestone');?></label></th>
    		<td>
      			<select name="Project[dlftMilestone]" id="dlftMilestone">
      			<option value="0"><?php echo Yii::t('app', 'no.default');?></option>
      			
      			<?php foreach (Milestone::model()->getProjectMilestones($model->id) as $m):?>
							<option <?php if ($model->dlftMilestone == $m->id){echo 'selected=""';}?> value="<?php echo $m->id;?>"><?php echo $m->title;?></option>
						<?php endforeach;?>
      			      			
						</select>
      			<p class="hint"><?php echo Yii::t('app', 'project.default.milestone.desc');?></p>
    		</td>
  		</tr>

		<tr>
    		<th><label for="tickettypes"><?php echo Yii::t('app', 'project.ticket.types');?></label></th>
    		<td>
      			<textarea style="font-family: monospace; font-size: 92%;" rows="10" name="Project[ticketTypes]" id="tickettypes" cols="65"><?php echo CHtml::encode($model->ticketTypes); ?></textarea>
      			<p class="hint"><?php echo Yii::t('app', 'project.ticket.types.desc');?></p>
    		</td>
		</tr>

		<tr>
    		<th><label for="ticketStatuses"><?php echo Yii::t('app', 'project.ticket.statuses');?></label></th>
    		<td>
      			<textarea style="font-family: monospace; font-size: 92%;" rows="10" name="Project[ticketStatuses]" id="ticketStatuses" cols="65"><?php echo CHtml::encode($model->ticketStatuses); ?></textarea>
      			<p class="hint"><?php echo Yii::t('app', 'project.ticket.statuses.desc');?></p>
    		</td>
		</tr>

		<tr>
    		<th><label for="project_open_states"><?php echo Yii::t('app', 'project.ticket.openstates');?></label></th>
    		<td>
      			<textarea style="font-family: monospace; font-size: 92%;" rows="10" name="Project[openStates]" id="projectOpenStates" cols="65"><?php echo CHtml::encode($model->openStates); ?></textarea>
      			<p class="hint"><?php echo Yii::t('app', 'project.ticket.openstates.desc');?></p>
    		</td>
		</tr>
		<tr>
    		<th><label for="project_closed_states"><?php echo Yii::t('app', 'project.ticket.closestates');?></label></th>
    		<td>
    			<textarea style="font-family: monospace; font-size: 92%;" rows="10" name="Project[closeStates]" id="projectCloseStates" cols="65"><?php echo CHtml::encode($model->closeStates); ?></textarea>
      			<p class="hint"><?php echo Yii::t('app', 'project.ticket.closestates.desc');?></p>
    		</td>
  		</tr>  	
		<?php } ?>
	</tbody>
</table>

<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save');?>" name="commit" />		
<?php echo Yii::t('app', 'or');?> 
<?php if ($model->isNewRecord) { ?>
<a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo Yii::t('app', 'btn.cancel');?></a></p>
<?php } else { ?>
<a href="<?php echo $this->createUrl('/project/show', array('id'=>$model->id,)); ?>"><?php echo Yii::t('app', 'btn.cancel');?></a></p>	
<?php } ?>	
</form>

</div>
</div>
