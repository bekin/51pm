<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/logoicon.png" type="image/gif" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/site.css" />  
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie6.css" />
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie7.css" />
<![endif]-->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js" type="text/javascript"></script>
<script>
$(function() {
	$('#reset-password-link').click(function() {
		$('#reset-password').show();
		return false;
	}); 

	$('#loginform').validate(
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
			"LoginForm[email]": {
				required: " ",
				email: " "
			},
			"LoginForm[password]": {
				required: " "
			}
	},
	debug:false		
	}
	); 	
		
});	
</script>
<title><?php echo $this->pageTitle; ?> - <?php echo Yii::app()->name;?></title>
</head>
<body style="background: rgb(238, 238, 238) none repeat scroll 0% 0%!important; text-align: center; font-family: Helvetica,Arial,sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: 1.5em; color: rgb(51, 51, 51);  }">
<div id="container">
	<div id="loginheader">
		<h1 id="logo"><a href="<?php echo Yii::app()->request->baseUrl; ?>"><?php echo Yii::app()->name;?></a></h1>
	</div>
	
<div id="logincontent">
<div id="signin">
	
  <h3><?php echo Yii::t('app', 'please.login'); ?></h3>
  
  <div class="errorSummary" <?php if (!$form->hasErrors()) echo 'style="display:none;"'; ?>>
  	<?php if ($form->hasErrors()) {
  			echo Yii::t('app', 'invalid.login');
  		}
  	?>
  </div>	
  
  <div class="box-content">    
    <form style="" action="<?php echo Yii::app()->request->baseUrl; ?>/site/login" id="loginform" method="post"><div style="margin: 0pt; padding: 0pt;"></div>
    	<dl>
			<dt><label for="email">Email</label></dt>
			<dd><input class="required email" value="" id="email" name="LoginForm[email]" value="<?php echo $form->email; ?>" tabindex="6" type="text"></dd>
			<dt><label for="password"><?php echo Yii::t('app', 'password'); ?></label> <a href="#" id="reset-password-link">(<?php echo Yii::t('app', 'password.forget'); ?>)</a></dt>
			<dd><input class="required" id="password" name="LoginForm[password]" tabindex="7" type="password"></dd>
			<dd><label><?php echo Yii::t('app', 'remember.me'); ?> <input checked="checked" id="rememberMe" name="LoginForm[rememberMe]" value="1" type="checkbox"></label></dd>
		</dl>
		<p class="btns"><input class="submit" 
			value="<?php echo Yii::t('app', 'sign.in');?>" tabindex="9" type="submit"></p>
	</form>
    
    <form style="overflow: visible; margin-top: 10px;display: none;" action="<?php echo $this->createUrl('/site/pwdreset'); ?>" id="reset-password" method="post">
    	<div style="margin: 0pt; padding: 0pt;"></div>
    	<p><?php echo Yii::t('app', 'password.reset.desc'); ?></p>
    	<p><input value="" class="big" id="reset_email" name="email" type="text"></p>
    	<p class="btns"><input class="submit" id="submit" value="<?php echo Yii::t('app', 'email.password');?>" tabindex="3" type="submit"></p>
    </form>
  </div>
  
</div>
</div>

</div>
</body>
</html>