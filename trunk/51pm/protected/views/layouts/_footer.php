<div id="footer">
	<div id="footer-cnt">
	<a href="#" onclick="return false;" style="float:right;display:block;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logoicon.png"/></a>
	<ul id="ftr-links">
		<li><a	href="#"><strong><?php echo Yii::t('app', 'f.contactus'); ?></strong></a></li>
		<li><a	href="#"><strong><?php echo Yii::t('app', 'f.support'); ?></strong></a></li>
		<li><a	href="#"><strong><?php echo Yii::t('app', 'f.blog'); ?></strong></a></li>
		<li><a	href="#"><strong><?php echo Yii::t('app', 'f.license'); ?></strong></a></li>	
	</ul>
	<p><?php echo Yii::t('app', 'f.copyright'); ?></p>
	
	</div>
</div>


<div id="growl" class="top-right growl" style="display:none;">
	<div class="growl-notification"></div>
	<div style="display: block;" class="growl-notification ui-state-highlight ui-corner-all default">
		<div id="growlclose" class="close">X</div>
		<div class="header" id="growlHeader"></div>
		<div class="message" id="growlMsg"></div>
	</div>
</div>

<div id="tsearch" style="display:none;">
	<div class="calloutUp">
		<div class="calloutUp2">
		</div>
	</div>
	<div class="divContainerUp" id="tsearchcond"></div>
</div>	
<script>
var searchloaded = false;
$(document).ready(function () {
	$("#projects").sexyCombo({
		emptyText: "<?php echo Yii::t('app', 'choose.a.project');?>",
		autoFill: true,
		changeCallback: function() {
			var p = this.hidden.val();
			if (p != '0') 
				window.location = '<?php echo Yii::app()->request->baseUrl; ?>/project/' + p;
		}, 
	});

	$("#quick-search").click(function() {
		if (!searchloaded) {
			$("#tsearchcond").load("<?php echo $this->createUrl('/ticket/search'); ?>");
			searchloaded = true;
		}
				
		if( $('#tsearch').is(':visible') ) {
			floatbox.hide('tsearch'); 
		}
		else {
			var p = $('#quick-search').offset();		
			floatbox.init({
				targetid: 'tsearch',
				orientation: 1,
				position: [p.left - 195, p.top + 20],
				fadeduration: [1000, 1000],
				frequency: 0.95
			});
		}
		return false;
	});
	
	<?php 
	    $m = Growl::getMessage();
	    $persisted = Growl::isPersisted();  
		if (!is_null($m)) {
	?>
		$('#growlMsg').html('<?php echo $m;?>'); 
		$('#growl').show();
		
	    <?php if (!$persisted) { ?>   
		setTimeout(function(){
			$('#growl').animate({opacity:0}, 1000, function(){$('#growl').hide();});
		}, 5000);
	    <?php } ?>
	
	
		$("#growlclose").click(function(){$('#growl').animate({opacity:0}, 1000, function(){$('#growl').hide();});} ); 
	<?php }	?>
});
</script>
</body>
</html>
