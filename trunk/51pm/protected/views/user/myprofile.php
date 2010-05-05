<div id="project-nav">
	<ul>
		<li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'my.profile'); ?></a></li>
		<li id="t-proj" class="submenu-tab"><a href="<?php echo $this->createUrl('/user/myactivities'); ?>"><?php echo Yii::t('app', 'my.activities'); ?></a></li>
	</ul>
</div>

<?php
	$user = Yii::app()->user->userRecord;
?>

<div id="page-top"></div>

<div id="main-content" class="clear">
	<div class="group">	
	<div class="errorSummary" <?php if (!$user->hasErrors()) echo 'style="display:none;"'; ?>>
  	<?php if ($user->hasErrors()) {
  			echo Yii::t('app', 'validation.errors');
  		}
  	?>
  </div>	

  <?php if ('now' == $_GET['activation']) { ?>
		<div class="reminder">
			<?php echo Yii::t('app', 'activation.reminder', array('{uid}'=>$user->email, '{pwd}'=>'lovepm88',)); ?>
		</div>
  <?php } ?>	
  <form method="post" id="profileForm"  enctype="multipart/form-data">
  	<div class="group-cnt">    
	    <table class="form-tbl" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
				    <th><label for="userName"><?php echo Yii::t('app', 'user.name'); ?></label></th>
				    <td><input type="text" class="required" value="<?php echo $user->userName;?>" size="30" name="User[userName]" id="userName"/></td>
				  </tr>				
					<tr>
				    <th><label for="email"><?php echo Yii::t('app', 'user.email'); ?></label></th>
				    <td><input type="text" class="required" value="<?php echo $user->email;?>" size="30" name="User[email]" id="email"/></td>
				  </tr>				
					<tr>
				    <th>
				    	<label for="avatar"><?php echo Yii::t('app', 'avatar'); ?></label></th>
				    <td>
				    	<?php if (empty($user->avatar)) {?>
				    	<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/avatar.gif" class="avatar" />
						<?php } else { 
							$avatar = str_replace("\\", '/', Yii::app()->params['avatarRoot'] . '/' . $user->avatar); 
						?>
							<img src="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo $avatar;?>" class="avatar" />
						<?php } ?>	
				    	<input type="file" size="30" name="avatar" id="avatar"/>
				    </td>
				  </tr>		
				  <tr>
				    <th><label for="chgpwd"><?php echo Yii::t('app', 'chgpwd'); ?></label></th>
				    <td>
				    	<p class="hint"><?php echo Yii::t('app', 'chgpwd.desc'); ?></p>
				    	<input type="password" value="" size="30" name="chgpwd" id="chgpwd"/></td>
				  </tr>				
				  <tr>
				    <th><label for="rechgpwd"><?php echo Yii::t('app', 'chgpwd.confirm'); ?></label></th>
				    <td>
				    	
				    	<input type="password" value="" size="30" name="rechgpwd" id="rechgpwd"/></td>
				  </tr>				
				  					  	
				  		
				</tbody>
			</table>	
		</div>

		<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save'); ?>" name="commit" />			
	</form>
	</div>
</div>

<script>
	$(function() {
	$('#profileForm').validate(
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
				"rechgpwd": {
					equalTo: "<?php echo Yii::t('app', 'pwd.no.match');?>"
				}
				
			},
			rules: {
				 rechgpwd: {
		      equalTo: "#chgpwd"
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