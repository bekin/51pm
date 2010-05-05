<?php if ($this->isAdmin()) { ?>
<div id="action-nav">
	<ul class="clear">            
		<li><a href="<?php echo $this->createUrl('/milestone/update', array('id'=>$model->id, ));?>"><?php echo  Yii::t('app', 'milestone.edit');?></a></li>
		<li><a href="<?php echo $this->createUrl('/milestone/create');?>"><?php echo Yii::t('app', 'milestone.create');?></a></li>
		<li class="destructive"><a class="deletelink" href="#"><?php echo Yii::t('app', 'milestone.delete');?></a></li>
	</ul>
</div>
<?php } ?>

<div id="page-top">
	<div style="display: none;" id="deletedlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'milestone.delete.confirm');?></div>
		<div id="osx-modal-data">
			<form method="post" id="page-delete-form" action="<?php echo $this->createUrl('/milestone/delete'); ?>">
				<input type="hidden" name="id" value="<?php echo $model->id; ?>" />
				<input type="hidden" name="project" value="<?php echo $model->projectId; ?>" />
				
				<p><?php echo Yii::t('app', 'milestone.delete.confirm');?>?</p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm');?>" name="commit"/> or <a href="#" style="color:#3388BB!important;" class="simplemodal-close">cancel</a>
			</form>
		</div>
	</div>

	<div class="greet clear">
	  <h2><?php echo Yii::app()->session['currentProject']->projectName?>: <?php echo $model->title;?></h2>
	  <p class="gmeta">
	        <?php echo Yii::t('app', 'milestone.duedate'), ': ', $model->duedate;?> | 
	        <?php echo Yii::t('app', 'ticket.opened'), ': ', Ticket::openTicketCountForMilestone($model->id, Yii::app()->session['currentProject']);?>  
	   </p>
	</div>
</div>
<div class="clear" id="main-content">
	  <div class="gdesc">
	    <div><p><?php echo $model->milestoneDesc;?></p></div>
	  </div>

</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.simplemodal.js" type="text/javascript"></script>
<script>
$(document).ready(function () {
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
});
</script>        