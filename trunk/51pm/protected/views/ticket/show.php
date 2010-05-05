<div id="action-nav">
<ul class="clear">
	<?php if ($this->isAdmin() || $model->createdBy == Yii::app()->user->userRecord->userId) { ?>
	<li><a href="<?php echo $this->createUrl('/ticket/edit', array('id'=>$model->id, )); ?>"><?php echo Yii::t('app', 'edit.ticket'); ?></a></li>
	<?php } ?>
	<?php if ($this->isAdmin()) { ?>
	<li class="destructive"><a class="deletelink" href="#"><?php echo Yii::t('app', 'delete.ticket'); ?></a></li>
<?php } ?>
</ul>
</div>

<div id="page-top">
	<div style="display: none;" id="deletedlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm'); ?></div>
		<div id="osx-modal-data">
			<form method="post" id="page-delete-form" action="<?php echo $this->createUrl('/ticket/delete'); ?>">
				<input type="hidden" name="id" value="<?php echo $model->id; ?>" />
				<input type="hidden" name="project" value="<?php echo $model->projectId; ?>" />
				
				<p><?php echo Yii::t('app', 'delete.ticket.confirm'); ?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm'); ?>" name="commit"/> <?php echo Yii::t('app', 'or'); ?> <a href="#" style="color:#3388BB!important;" class="simplemodal-close"><?php echo Yii::t('app', 'btn.cancel'); ?></a>
			</form>
		</div>
	</div>

	<div style="display: none;" id="delattdlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm');?></div>
		<div id="osx-modal-data">
			<form method="post" id="delattform" action="<?php echo $this->createUrl('/media/delete'); ?>">
				<input type="hidden" name="id" id="attachid" value="" />
				<input type="hidden" name="ticket" id="ticketid" value="" />
				<input type="hidden" name="ticketh" id="tickethid" value="" />
				<input type="hidden" name="project" value="<?php echo $model->projectId; ?>" />
				
				<p><?php echo Yii::t('app', 'delete.attachment.confirm');?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm');?>" name="commit"/> or <a href="#" style="color:#3388BB!important;" class="simplemodal-close">cancel</a>
			</form>
		</div>
	</div>


	<div class="greet clear">
		<div class="ticket-meta clear"><span class="ticketnum clear"> <a
			href="#" onclick="return false;">#<?php echo $model->displayOrder; ?></a> </span> <span
			style="color: #<?php echo Yii::app()->session['currentProject']->getStatusColor($model->ticketStatus); ?>;" class="tstate"><?php echo $model->ticketStatus; ?></span>
		</div>
		<div class="gleft">
		<?php $creator = User::model()->findbyPk($model->createdBy); ?>
		<img src="<?php echo $creator->avatarImage(); ?>" class="avatar"/>
		</div>
		<div class="gcnt">
		<h2><?php echo $model->title;?></h2>
		<?php $creator = User::model()->findbyPk($model->createdBy); ?>
		<p class="date"><?php echo Yii::t('app', 'reported.by');?> <a href="#"><?php 	echo $creator->userName; ?></a> 
		| 
		<?php echo $model->createdOn;?>
		</p>
		<div class="greet-cnt">
		<div>
		<p><?php echo $model->ticketDesc;?></p>
		</div>
	</div>
		
	<!-- REDBOOKLAB -->
	<?php if (count($model->attachments) > 0) {?>
	<div class="attachments" id="attbox_<?php echo $model->id;?>">
	  <ul class="attachment-list clear">
	    <?php foreach ($model->attachments as $a):?>
	    <li id="att_<?php echo $a->id;?>" class="attachment clear <?php if ($a->isImage) {echo 'aimg'; } ?>">
 	    <a class="item" target="_blank" href="<?php echo $this->createUrl('/media/get', array('ticket'=>$a->ticketId, 'project'=>$a->projectId, 'id'=>$a->id,)); ?>">
	
	     <?php echo $a->title;?>
	    </a>
	      <span class="file-size">
	      <?php 
	      	$size = $a->contentSize / 1000;
	      	echo number_format($size, 2) . ' KB';
	      ?>
	          <a  href="#" class="xdel" rel="<?php echo $a->id . ' ' . $model->id . ' t'; ?>"><?php echo Yii::t('app', 'delete');?></a>
             </span>
	    </li>
	    <?php endforeach;?>
	    </ul>
	</div>
	<?php } ?>
	<!-- REDBOOKLAB -->
			
		</div>
	</div>

	<?php if (!Yii::app()->user->isGuest) {
		$iamwatching = $model->isNotifyAll() 
				|| !(strpos($model->notifications, Yii::app()->user->userRecord->userId) === false); 
	?>
	<div class="gbar clear">
    <ul class="clear">
        <li id="watch-ticket">
          <form class="clear">
            <input type="button" id="iwatch" value="<?php if ($iamwatching) {echo Yii::t('app', 'stop.watch.ticket');} else {echo Yii::t('app', 'watch.ticket');} ?>"  	name="watch-ticket" class="button positive"/>
          </form>
        </li>
    </ul>
  </div>
<?php } ?>
</div>


<div id="main-content" class="clear">
	<div id="ticket">
		<div class="changes">	
		<?php if (count($histories) > 0) { ?>			
			<h3><?php echo Yii::t('app', 'ticket.comments.changes');?></h3>
			<ul class="info">
				<?php foreach ($histories as $n=>$h): ?>				
				<li class="tticket clear <?php if ($n != (count($histories) - 1)) {echo 'shaded'; } ?>" style="clear: left;">
					<div class="tleft"><img src="<?php echo $h->user->avatarImage(); ?>" class="avatar"/></div>
					<div class="tcnt">
          <h4><a href="#"><?php echo $h->user->userName; ?></a> <span class="event-date"><a href="#" onclick="return false;"><?php echo $h->historyDate; ?></a></span></h4>
          
          
        	<?php if (!is_null($h->historyDesc)) { $changes = unserialize($h->historyDesc);  ?>
          <ul class="ticket-changes">       
          	<?php foreach ($changes as $k=>$c):   	
          		$msg = '';
          		$params = array('{old}'=>$c[0], '{new}'=>$c[1],);
							switch ($k) {
								case 'title': 
									$msg = Yii::t('app', 'title.change', $params);
									break; 
								case 'owner': 
									$msg = Yii::t('app', 'owner.change', $params);
									break; 
								case 'ticketStatus': 
									$msg = Yii::t('app', 'ticketStatus.change', $params);
									break; 
								case 'ticketPriority': 
									$msg = Yii::t('app', 'ticketPriority.change', $params);
									break; 
								case 'ticketType': 
									$msg = Yii::t('app', 'ticketType.change', $params);
									break; 
								case 'tags': 
									$msg = Yii::t('app', 'tags.change', $params);
									break; 
								case 'milestoneId': 
									$msg = Yii::t('app', 'milestoneId.change', $params);
									break; 
								case 'duedate': 
									$msg = Yii::t('app', 'duedate.change', $params);
									break; 
								case 'ticketDesc': 
									$msg = Yii::t('app', 'ticketdesc.change');
									break; 
							}
							echo '<li>'.$msg.'</li>'; 
						endforeach; ?>
          </ul>
        	<?php } ?>
          
          <div style="clear: left;" class="desc"><div><p><?php echo $h->comments; ?></p></div></div>
                  
				  <!-- REDBOOKLAB -->
					<?php if (count($h->attachments) > 0) {?>
					<div class="attachments" id="attbox_<?php echo $h->id;?>">
					  <ul class="attachment-list clear">
					    <?php foreach ($h->attachments as $a):?>
					    <li id="att_<?php echo $a->id;?>" class="attachment clear <?php if ($a->isImage) {echo 'aimg'; } ?>">
		     	    <a class="item" target="_blank" href="<?php echo $this->createUrl('/media/get', array('ticketh'=>$a->ticketHistoryId, 'project'=>$a->projectId, 'id'=>$a->id,)); ?>">
	
					     <?php echo $a->title;?>
					    </a>
					      <span class="file-size">
					      <?php 
					      	$size = $a->contentSize / 1000;
					      	echo number_format($size, 2) . ' KB';
					      ?>
					          <a  href="#" class="xdel" rel="<?php echo $a->id . ' ' . $h->id . ' h'; ?>"><?php echo Yii::t('app', 'delete');?></a>
				             </span>
					    </li>
					    <?php endforeach;?>
					    </ul>
					</div>
					<?php } ?>
					<!-- REDBOOKLAB -->
                  
        </div>
					
				</li>	
				<?php endforeach; ?>
			</ul>	
		<?php } ?>
		</div>
		
		<h3><?php echo Yii::t('app', 'ticket.update'); ?></h3>
		<form method="post" enctype="multipart/form-data" action="<?php echo $this->createUrl('/ticket/update'); ?>">
			<?php echo $this->renderPartial('_form', array(
				'model'=>$model,
				'update'=>true,
			)); ?>
		</form>	
	</div>			
</div>

<?php 
	if (EmailManager::peek())
	{
?>	
	<form target="_emailFrame" id="emailform" name="emailform" method="post" action="<?php echo $this->createUrl('/media/email');?>">
	<input type="hidden" name="hash" value="2addeiii-ddsd" /></form><iframe id="_emailFrame" name="_emailFrame" style="width:0px; height:0px; border: 0px" src="about:blank"></iframe>
	<script>
	$(document).ready(function() {
		$('#emailform').submit();
	});
	</script>
<?php } ?>