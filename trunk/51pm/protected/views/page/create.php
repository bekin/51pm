<div class="clear" id="main-content">
	<div class="group">
	<?php if ($model->isNewRecord) { ?>
	<h3><?php echo Yii::t('app', 'create.page');?></h3>
	<?php } else { ?>
	<h3><?php echo Yii::t('app', 'edit.page');?></h3>
	<?php }?>
	
	<div class="errorSummary" <?php if (!$model->hasErrors()) echo 'style="display:none;"'; ?>>
  	<?php if ($model->hasErrors()) {
  			echo Yii::t('app', 'validation.errors');
  		}
  	?>
  </div>	
	
   	<form method="post" id="pageform">
   		<div class="group-cnt">
    
    <dl>
      <dt><label for="pageTitle"><?php echo Yii::t('app', 'page.title');?></label></dt>
      <dd><input type="text" size="30" name="Page[title]" id="pageTitle" class="big required" value="<?php echo CHtml::encode($model->title); ?>" /></dd>      
      <dt><label for="pagebody"><?php echo Yii::t('app', 'page.body');?></label><br/>
      	<textarea rows="20" class="required" name="Page[pageContent]" id="pageContent" cols="40"><?php echo CHtml::encode($model->pageContent); ?></textarea></dt>
      <dt><?php echo Yii::t('app', 'display.order'); ?>: <input class="number" size="3" name="Page[displayOrder]" id="displayOrder" value="<?php echo $model->displayOrder; ?>" /></dt>		
    </dl>
  </div>

	<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save');?>" name="commit" />
		<?php if (isset(Yii::app()->session['currentProject'])) {
				$action = $this->createUrl('/project/show', array('id'=>Yii::app()->session['currentProject']->id));			
			}
			else
			{
				$action = Yii::app()->request->baseUrl; 
			}
		?>			
		<?php echo Yii::t('app', 'or');?> <a href="<?php echo $action; ?>"><?php echo Yii::t('app', 'btn.cancel');?></a></p>
	</form>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />  
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />  
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js" type="text/javascript"></script>


<script>
$(document).ready(function()	{
   $('#pageContent').markItUp(mySettings);
   
   
   $('#pageform').validate(
		{
			invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				$("div.errorSummary").show();				
				$("div.errorSummary").html('<?php echo Yii::t('app', 'validation.errors'); ?>'); 
			} 
			else {
				 $("div.errorSummary").hide();
			}
			}, 
			onkeyup: false,
			submitHandler: function(form) {
				$("div.errorSummary").hide();
				 form.submit();
	
				return true;
			},
			messages: {
				"Page[title]": {
					required: " "
					
				},
				"Page[pageContent]": {
					required: " "
				},
				"Page[displayOrder]": {
					number: " "
				},
			},
			debug:false		
		}
	); 	
	
	
	<?php 
  	foreach ($model->getErrors() as $attr=>$errors): 
  ?>
  	$('input[name="Page[<?php echo $attr; ?>]"]').addClass('error');
  
  <?php 	
  	endforeach;
  ?>	
   
});
</script>