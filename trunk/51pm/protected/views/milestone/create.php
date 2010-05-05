<div id="action-nav">
	<ul class="clear"></ul>
</div>

<div class="clear" id="main-content">          
	<div class="group">
	  	<?php if ($model->isNewRecord) { ?>
		<h3><?php echo Yii::t('app', 'milestone.create');?></h3>
		<?php } else { ?>
		<h3><?php echo Yii::t('app', 'milestone.edit');?></h3>
		<?php }?>
	  
		<form method="post">		 
			<div class="group-cnt">
	  			<dl>
	    			<dt><label for="milestoneTitle"><?php echo Yii::t('app', 'milestone.title');?></label></dt>
	    			<dd><input type="text" size="30" name="Milestone[title]" id="milestoneTitle" class="big" value="<?php echo CHtml::encode($model->title);?>" /></dd>
	    			<dt><label for="milestoneDuedate"><?php echo Yii::t('app', 'milestone.duedate');?></label></dt>
	    			<dd><input type="text" size="30" name="Milestone[duedate]" id="duedate" value="<?php echo CHtml::encode($model->duedate);?>"/></dd>
	      				
				    <dt><label for="milestone_goals"><?php echo Yii::t('app', 'milestone.desc');?></label></dt>
	    			<dd><textarea rows="10" name="Milestone[milestoneDesc]" id="milestoneDesc" cols="80"><?php echo CHtml::encode($model->milestoneDesc);?></textarea>
	    			</dd>
	  			</dl>
			</div>
	  				<p class="btns"><input type="submit" value="<?php echo Yii::t('app', 'btn.save'); ?>" name="commit" />			
					<?php echo Yii::t('app', 'or'); ?>
					<a href="<?php echo $this->createUrl('/milestone/list', array('project'=>Yii::app()->session['currentProject']->id,)); ?>"><?php echo Yii::t('app', 'btn.cancel'); ?></a></p>
		</form>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/skins/markitup/style.css" />  
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/style.css" />  
<link
	rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/jquery.markitup.pack.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/markitup/sets/html/set.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datepicker.js" type="text/javascript"></script>


<script>
$(document).ready(function()	{
   $('#milestoneDesc').markItUp(mySettings);

	$('#duedate').DatePicker({
		<?php if (LocaleManager::isChinese()) { ?>
			format:'Y-m-d',
			locale: {
				days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
				daysShort: ["日", "一", "二", "三", "四", "五", "六", "日"],
				daysMin:   ["日", "一", "二", "三", "四", "五", "六", "日"],
				months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
				monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
				weekMin: '周'
			}, 
		<?php } else { ?>
			format:'m/d/Y',
		<?php }?>	
			date: $('#duedate').val(),
			current: $('#duedate').val(),
			starts: 1,
			calendars: 2,
			onBeforeShow: function(){
				var v = jQuery.trim($('#duedate').val());
				<?php if (LocaleManager::isChinese()) { ?>
				var dreg = /^\d{4}(\-|\/|\.)\d{1,2}\1\d{1,2}$/;
				<?php } else { ?>
				var dreg = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/;
				<?php }?>	 
				if (dreg.test(v)) {
					$('#duedate').DatePickerSetDate(v, true);
				}
			},
			onChange: function(formated, dates){
				$('#duedate').val(formated);			
			  $('#duedate').DatePickerHide();
				
			}
		});
	   
});
</script>