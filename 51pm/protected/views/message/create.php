<div class="clear" id="main-content">
	<div class="group">
	<?php if ($model->isNewRecord) { ?>
	<h3><?php echo Yii::t('app', 'new.message'); ?></h3>
	<?php } else { ?>
	<h3><?php echo Yii::t('app', 'message.edit'); ?></h3>
	<?php }?>
	
   	<form method="post"  enctype="multipart/form-data">
   		<div class="group-cnt">
    
    <dl>
      <dt><label for="pageTitle"><?php echo Yii::t('app', 'message.title'); ?></label></dt>
      <dd><input type="text" size="30" name="Message[title]" id="messageTitle" class="big" value="<?php echo CHtml::encode($model->title); ?>" /></dd>      
      <dt><label for="msg"><?php echo Yii::t('app', 'message.body'); ?></label><br/><textarea rows="20" name="Message[msg]" id="msg" cols="40"><?php echo CHtml::encode($model->msg); ?></textarea></dt>
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
  	<div style="" id="select-watchers">
	    <div class="watch-block" id="watcher-form">
	      <div class="csshidden2" id="watcher-area">
	        <ul class="checkbox-list clear">
	            <li><label>
	            		<input type="checkbox" value="true" name="notify" checked="checked"/> <?php echo Yii::t('app', 'notify.all'); ?></label>
	            </li>
	        </ul>
	      </div>
	    </div>
	</div>
  </div>

	<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save'); ?>" name="commit" />			
		<?php echo Yii::t('app', 'or'); ?>
		<a href="<?php echo $this->createUrl('/message/list', array('project'=>$this->currentProject->id,)); ?>"><?php echo Yii::t('app', 'btn.cancel'); ?></a></p>
	</form>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />  
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />  
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js" type="text/javascript"></script>


<script>
$(document).ready(function()	{
   $('#msg').markItUp(mySettings);
   $('.add').click(function() {
			return Attachment.add('<?php echo Yii::t('app', 'attach.file'); ?>');	   	
   });
});
</script>