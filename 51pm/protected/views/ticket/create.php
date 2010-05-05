<div class="clear" id="main-content">
<div class="group">

<h3><?php echo Yii::t('app', 'ticket.create');?></h3>

<div class="errorSummary" <?php if (!$model->hasErrors()) echo 'style="display:none;"'; ?>>
    <?php if ($model->hasErrors()) {
            echo Yii::t('app', 'validation.errors');
        }
    ?>
</div>    

<form method="post" id="ticketform" enctype="multipart/form-data">	
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'update'=>false,
	)); ?>
</form>
<script>
$(document).ready(function()    {
    $('#ticketform').validate(
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
                "Ticket[title]": {
                    required: " "
                    
                },
                "Ticket[ticketDesc]": {
                    required: " "
                }                
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
</div>
</div>
