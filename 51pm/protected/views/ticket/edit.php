<div class="clear" id="main-content">
	<div class="group">
		<h3><?php echo Yii::t('app', 'edit.ticket');?></h3>
		<?php echo CHtml::errorSummary($model); ?>
		<form method="post">	
			<div class="page-form" id="ticket-form" style="position: relative;">
				<dl id="ticket-title" style="" class="form lbl-inline clear">
					<dt><?php echo Yii::t('app', 'ticket.title');?>: </dt>
					<dd><?php echo $model->title;?></dd>
				</dl>
				<dl class="form">
					<dt><label for="ticket_body"> <?php echo Yii::t('app', 'ticket.desc');?>  </label></dt>
					<dd><textarea tabindex="4" style="height:200px!important;" rows="15" name="ticketDesc" id="ticketDesc" cols="40" ><?php echo CHtml::encode($model->ticketDesc);?></textarea>
			    </dd>
				</dl>

			<p style="clear: left;" class="btns">
			<input type="submit" value="<?php echo Yii::t('app', 'btn.update');?>" tabindex="8" name="commit" /> 
    	<?php echo Yii::t('app', 'or'); ?>
 		  <a href="<?php echo $this->createUrl('/ticket/show', array('id'=>$model->id,)); ?>"><?php echo Yii::t('app', 'btn.cancel');?></a>
 		  </p>

			</div>
		</form>
	</div>
</div>


<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />
<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js"
	type="text/javascript"></script>
<script
	src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js"
	type="text/javascript"></script>


<script>
$(document).ready(function()	{
   $('#ticketDesc').markItUp(mySettings);
});
</script>
