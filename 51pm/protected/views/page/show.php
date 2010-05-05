<?php if ($this->isAdmin()) {?>
<div id="action-nav">
	<ul class="clear">
	  <li class="destructive"><a class="deletelink" href="#"><?php echo Yii::t('app', 'delete.page'); ?></a></li>
  	  <li><a href="<?php echo $this->createUrl('/page/update', array('id'=>$model->id,)); ?>"><?php echo Yii::t('app', 'edit.page'); ?></a></li>
	</ul>
</div>
<?php } ?>
<div id="page-top">     
	<div style="display: none;" id="deletedlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm'); ?></div>
		<div id="osx-modal-data">
			<form method="post" id="page-delete-form" action="<?php echo $this->createUrl('/page/delete'); ?>">
				<input type="hidden" name="id" value="<?php echo $model->id; ?>" />
				
				<p><?php echo Yii::t('app', 'delete.page.confirm'); ?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm'); ?>" name="commit"/> <?php echo Yii::t('app', 'or'); ?> <a href="#" style="color:#3388BB!important;" class="simplemodal-close"><?php echo Yii::t('app', 'btn.cancel'); ?></a>
			</form>
		</div>
	</div>

	<div class="greet clear">
	  <h3><?php echo $model->title; ?></h3>
	  <p class="gmeta"><?php echo $model->modifiedOn; ?></p>
	</div>
</div>
        
<div class="clear" id="main-content">
	<div style="margin-top: 20px;" id="pbody">
      <div><p><?php echo $model->pageContent; ?></p></div>
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
			//position:['0',],
			overlayClose:true,
			//onOpen:OSX.open,
			onClose:OSX.close

			});
	});

});
</script>        