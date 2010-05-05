<div id="action-nav">
	<ul class="clear">
		<li><a href="<?php echo $this->createUrl('/message/update', array('id'=>$model->id,)); ?>"><?php echo Yii::t('app', 'message.edit'); ?></a></li>
		<li><a href="<?php echo $this->createUrl('/message/create', array('project'=>$currentProject->id,)); ?>"><?php echo Yii::t('app', 'new.message'); ?></a></li>
		<li class="destructive"><a class="deletelink" href="#"><?php echo Yii::t('app', 'message.delete'); ?></a></li>
    </ul>
</div>

<div id="page-top">
	<div style="display: none;" id="deletedlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm');?></div>
		<div id="osx-modal-data">
			<form method="post" id="page-delete-form" action="<?php echo $this->createUrl('/message/delete'); ?>">
				<input type="hidden" name="id" value="<?php echo $model->id; ?>" />
				<input type="hidden" name="project" value="<?php echo $model->projectId; ?>" />
				
				<p><?php echo Yii::t('app', 'delete.message.confirm');?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm');?>" name="commit"/> 
			  	<?php echo Yii::t('app', 'or'); ?>
			  	<a href="#" style="color:#3388BB!important;" class="simplemodal-close"><?php echo Yii::t('app', 'btn.cancel'); ?></a>
			</form>
		</div>
	</div>
	<div style="display: none;" id="delattdlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm');?></div>
		<div id="osx-modal-data">
			<form method="post" id="delattform" action="<?php echo $this->createUrl('/media/delete'); ?>">
				<input type="hidden" name="id" id="attachid" value="" />
				<input type="hidden" name="message" id="msgid" value="" />
				<input type="hidden" name="project" value="<?php echo $model->projectId; ?>" />
				
				<p><?php echo Yii::t('app', 'delete.attachment.confirm');?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm');?>" name="commit"/> 
			  	<?php echo Yii::t('app', 'or'); ?>
			  	<a href="#" style="color:#3388BB!important;" class="simplemodal-close"><?php echo Yii::t('app', 'btn.cancel'); ?></a>
			</form>
		</div>
	</div>
	<?php $poster = User::model()->findbyPk($model->createdBy); ?>
	<div class="greet">
	  <div class="gleft"><img  src="<?php echo $poster->avatarImage(); ?>" class="avatar"/></div>
	  <div class="gcnt">
	    <h2><?php echo $model->title; ?></h2>
	    <div style="margin-left: 5px;" class="gmeta">
	    	<?php echo Yii::t('app', 'posted.by');?>: <a href="#"><?php 	    		
	    		echo $poster->userName;?></a>
        |
        <?php echo Yii::t('app', 'posted.on');?>: 
      	<?php 
            	$inSeconds = strtotime($model->createdOn);
            	echo date(LocaleManager::isChinese()? 'Y-m-d':'M d, Y', $inSeconds);
				?> 	    	
	    </div>
	    <div class="gdesc"><div><p><?php echo $model->msg; ?></p></div></div>
	
	<?php if (count($model->attachments) > 0) {?>
	<div class="attachments" id="attbox_<?php echo $model->id;?>">
	  <ul class="attachment-list clear">
	    <?php foreach ($model->attachments as $a):?>
	    <li id="att_<?php echo $a->id;?>" class="attachment clear <?php if ($a->isImage) {echo 'aimg'; } ?>">
	    <a class="item" target="_blank" href="<?php echo $this->createUrl('/media/get', array('message'=>$a->messageId, 'project'=>$a->projectId, 'id'=>$a->id,)); ?>">
	     <?php echo $a->title;?>
	    </a>
	      <span class="file-size">
	      <?php 
	      	$size = $a->contentSize / 1000;
	      	echo number_format($size, 2) . ' KB';
	      ?>
              <a  href="#" class="xdel" rel="<?php echo $a->id . ' ' . $model->id . ' m'; ?>"><?php echo Yii::t('app', 'delete');?></a>
             </span>
	    </li>
	    <?php endforeach;?>
	    </ul>
	</div>
	<?php } ?>
	    
	  </div>
	</div>

</div>


<div class="clear" id="main-content">          
	<ul id="msg-comments" style="margin-top: 30px;" class="messages comments">
	  <?php foreach ($comments as $c):?>	
	  <li id="message-<?php echo $c->id;?>" class="response shaded">
	    <div class="tleft"><img src="<?php echo $c->poster->avatarImage(); ?>" class="avatar" /></div>
	    <div class="tcnt">
	      <h4>
	        <?php echo $c->poster->userName; ?>
	        <span class="event-date">
	        <?php 
            	$inSeconds = strtotime($c->createdOn);
            	echo date(LocaleManager::isChinese()? 'Y-m-d':'M d, Y', $inSeconds);
					?> 	 
	        </span>
	      </h4>
	      <div class="desc"><div><p><?php echo $c->msg;?></p></div></div>

	<!-- REDBOOKLAB -->
	<?php if (count($c->attachments) > 0) {?>
	<div class="attachments" id="attbox_<?php echo $c->id;?>">
	  <ul class="attachment-list clear">
	    <?php foreach ($c->attachments as $a):?>
	    <li id="att_<?php echo $a->id;?>" class="attachment clear <?php if ($a->isImage) {echo 'aimg'; } ?>">
	    <a class="item" target="_blank" href="<?php echo $this->createUrl('/media/get', array('message'=>$a->messageId, 'project'=>$a->projectId, 'id'=>$a->id,)); ?>">

	     <?php echo $a->title;?>
	    </a>
	      <span class="file-size">
	      <?php 
	      	$size = $a->contentSize / 1000;
	      	echo number_format($size, 2) . ' KB';
	      ?>
              <a href="#" class="xdel" rel="<?php echo $a->id . ' ' . $c->id . ' m';?>"><?php echo Yii::t('app', 'delete');?></a>
             </span>
	    </li>
	    <?php endforeach;?>
	    </ul>
	</div>
	<?php } ?>
	<!-- REDBOOKLAB -->

	      
	    </div>
	  <a class="edit" href="<?php echo $this->createUrl('/message/update', array('id'=>$c->id,)); ?>"><?php echo Yii::t('app', 'btn.edit'); ?></a></li>
	  <?php endforeach; ?>
	</ul>

	<!-- REDBOOKLAB -->
	<form method="post"  enctype="multipart/form-data" action="<?php echo $this->createUrl('/message/comment'); ?>">
	<input type="hidden" name="Comment[msgId]" value="<?php echo $model->id; ?>" />
	<input type="hidden" name="Comment[projectId]" value="<?php echo $model->projectId; ?>" />
	<input type="hidden" name="Comment[title]" value="<?php echo CHtml::encode($model->title); ?>" /> 
	<div class="group">
	    <h3><?php echo Yii::t('app', 'response');  ?></h3>
	    <div class="group-cnt">
	    <dl>
	      <dd>
	      <textarea style="height:150px!important;" rows="5" name="Comment[msg]" id="msg" cols="40"></textarea></dd>
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
		    
		    <dt style="display: none;"></dt>
		    <dd>
 				<ul class="checkbox-list clear">
	            <li><label>
	            		<input type="checkbox" value="true" name="notify" checked="checked"/> <?php echo Yii::t('app', 'notify.all'); ?></label>
	            </li>
	     </ul>		    
		    </dd>
	    </dl>
	    
	    </div>
	  </div>
	  	<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save');?>" name="commit" /></p>
	  
	</form>
</div>
<div id="dummy"></div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />  
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />  
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js" type="text/javascript"></script>
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
			//position:['0',],
			overlayClose:true,
			//onOpen:OSX.open,
			onClose:OSX.close

			});
	});

	$('#msg').markItUp(mySettings);
	$('.add').click(function() {
		   	return Attachment.add('<?php echo Yii::t('app', 'attach.file'); ?>');	   	
	 });

	Attachment.delsetup();
	
	$('.xdel').click(function(e) {
		Attachment.delatt(e);
	}); 
});
</script>        