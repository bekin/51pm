<div id="project-nav">
	<ul>
		<li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'companies'); ?></a></li>
		<li id="t-proj" class="submenu-tab"><a href="<?php echo $this->createUrl('/admin/users'); ?>"><?php echo Yii::t('app', 'users'); ?></a></li>
	</ul></div>


<div id="page-top"></div>

<div id="main-content" class="clear">
	<div class="group">
	<?php if ($company->isNewRecord) { ?>
	<h3><?php echo Yii::t('app', 'create.company'); ?></h3>
	<?php } else { ?>
	<h3><?php echo Yii::t('app', 'edit.company'); ?></h3>
	<?php }?>
	
	<div class="errorSummary" <?php if (!$company->hasErrors()) echo 'style="display:none;"'; ?>>
  	<?php if ($company->hasErrors()) {
  			echo Yii::t('app', 'validation.errors');
  		}
  	?>
  </div>	
	
  <form method="post" id="companyForm">
  	<div class="group-cnt">
    
    <dl>
      <dt><label for="companyName"><?php echo Yii::t('app', 'company.name'); ?></label></dt>
      <dd><input class="required" maxlength="100" type="text" size="30" name="Company[companyName]" id="companyName" class="big" value="<?php echo CHtml::encode($company->companyName); ?>" /></dd>      
      
      <dt><label for="externalWebsite"><?php echo Yii::t('app', 'company.url'); ?></label></dt>
      <dd><input class="url" maxlength="100" type="text" size="30" name="Company[externalWebsite]" id="externalWebsite" value="<?php echo CHtml::encode($company->externalWebsite); ?>" /></dd>      
      
      <dt><label for="email"><?php echo Yii::t('app', 'company.email'); ?></label></dt>
      <dd><input maxlength="30" type="text" size="30" name="Company[email]" id="email" value="<?php echo CHtml::encode($company->email); ?>" /></dd>      

      <dt><label for="phone"><?php echo Yii::t('app', 'company.phone'); ?></label></dt>
      <dd><input maxlength="20" type="text" size="30" name="Company[phone]" id="phone" value="<?php echo CHtml::encode($company->phone); ?>" /></dd>      

    </dl>
  </div>

	<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save'); ?>" name="commit" />			
		<?php echo Yii::t('app', 'or'); ?> <a href="<?php echo $this->createUrl('/admin'); ?>"><?php echo Yii::t('app', 'btn.cancel'); ?></a></p>
	</form>
	</div>
</div>

<script>
$(function() {
	$('#companyForm').validate(
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
				"Company[companyName]": {
					required: " ",
				},
				"Company[externalWebsite]": {
					URL: " ",
				}
			},
			debug:false		
		}
	); 	
	
	
	<?php 
  	foreach ($company->getErrors() as $attr=>$errors): 
  ?>
  	$('input[name="Company[<?php echo $attr; ?>]"]').addClass('error');
  
  <?php 	
  	endforeach;
  ?>	
});	
</script>
