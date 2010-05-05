<div id="project-nav">
	<ul>
		<li id="t-proj" class="submenu-tab"><a href="<?php echo $this->createUrl('/admin/companies'); ?>"><?php echo Yii::t('app', 'companies'); ?></a></li>
		<li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'users'); ?></a></li>
	</ul>
</div>
<div id="action-nav">
	<ul class="clear">            
	    <li><a href="<?php echo $this->createUrl('/admin/createUser', array('company'=>$user->companyId,)); ?>"><?php echo Yii::t('app', 'create.user'); ?></a></li>
    </ul>
</div>


<div id="page-top"></div>

<div id="main-content" class="clear">
	<div class="group">
	<?php if ($user->isNewRecord) { ?>
	<h3><?php echo Yii::t('app', 'create.user'); ?></h3>
	<?php } else { ?>
	<h3><?php echo Yii::t('app', 'edit.user'); ?></h3>
	<?php }?>
	
	<div class="errorSummary" <?php if (!$user->hasErrors()) echo 'style="display:none;"'; ?>>
  	<?php if ($user->hasErrors()) {
  			echo Yii::t('app', 'validation.errors');
  			echo ': '; 
  			echo Yii::t('app', 'duplicate.email');
  		}
  	?>
  </div>	
	
  <form method="post" id="userForm">
  	<div class="group-cnt">
    
    <dl>
      <dt><label><?php echo Yii::t('app', 'company.name'); ?>: <?php echo $company->companyName;?></label></dt>
      <dd></dd>      

      <dt><label for="email"><?php echo Yii::t('app', 'user.email'); ?></label></dt>
      <dd><input class="required email" maxlength="100" type="text" size="30" name="User[email]" id="email" class="big" value="<?php echo CHtml::encode($user->email); ?>" /></dd>      
	  <?php if ($user->isNewRecord) { ?>
	  <dt><label for="password"><?php echo Yii::t('app', 'password'); ?></label></dt>
	  <dd><input class="required" maxlength="40" type="text" size="30" name="User[password]" id="password" value="<?php echo CHtml::encode($user->password); ?>" /></dd>
      <?php } ?>
      <dt><label for="userName"><?php echo Yii::t('app', 'user.name'); ?></label></dt>
      <dd><input class="required" maxlength="100" type="text" size="30" name="User[userName]" id="userName" value="<?php echo CHtml::encode($user->userName); ?>" /></dd>      
      
      <dt><?php echo Yii::t('app', 'user.roles'); ?></dt>
      <dd>
		<input <?php 
			$pos = strpos($user->roles, 'ROLE_ADMIN');
			if ($pos === 0 || $pos > 0) echo 'checked="checked"';
		?> type="checkbox" name="role[]" value="ROLE_ADMIN"> ROLE_ADMIN      
      	<input <?php 
			$pos = strpos($user->roles, 'ROLE_SITE_ADMIN');
			if ($pos === 0 || $pos > 0) echo 'checked="checked"';
		?> type="checkbox" name="role[]" value="ROLE_SITE_ADMIN"> ROLE_SITE_ADMIN
      </dd>      


    </dl>
  </div>

	<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save'); ?>" name="commit" />			
		<?php echo Yii::t('app', 'or'); ?> <a href="<?php echo $this->createUrl('/admin'); ?>"><?php echo Yii::t('app', 'btn.cancel'); ?></a></p>
	</form>
	</div>
</div>

<script>
$(function() {
	$('#userForm').validate(
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
				"User[email]": {
					required: " ",
					email: " "
				},
				"User[userName]": {
					required: " ",
				},
				"User[password]": {
					required: " ",
				}
			},
			debug:false		
		}
	); 	
	
	
	<?php 
  	foreach ($user->getErrors() as $attr=>$errors): 
  ?>
  	$('input[name="User[<?php echo $attr; ?>]"]').addClass('error');
  
  <?php 	
  	endforeach;
  ?>	
});	
</script>
