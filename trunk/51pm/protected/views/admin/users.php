<div id="project-nav">
	<ul>
		<li id="t-proj" class="submenu-tab"><a href="<?php echo $this->createUrl('/admin/companies'); ?>"><?php echo Yii::t('app', 'companies'); ?></a></li>
		<li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'users'); ?></a></li>
	</ul>
</div>
<div id="action-nav">
	<ul class="clear">            
	    <li><a href="<?php echo $this->createUrl('/admin/createUser', array('company'=>$_GET['company'],)); ?>"><?php echo Yii::t('app', 'create.user'); ?></a></li>
    </ul>
</div>


<div id="page-top">
	<div style="display: none;" id="deletedlg">
		<div id="osx-modal-title"><?php echo Yii::t('app', 'delete.confirm'); ?></div>
		<div id="osx-modal-data">
			<form method="post" id="page-delete-form" action="<?php echo $this->createUrl('/admin/deleteUser'); ?>">
				<input type="hidden" name="id" value="0" id="delid" />
				
				<p><?php echo Yii::t('app', 'delete.user.confirm'); ?></p>
			  	
			  	<input type="submit" value="<?php echo Yii::t('app', 'btn.confirm'); ?>" name="commit"/> <?php echo Yii::t('app', 'or');?> <a href="#" style="color:#3388BB!important;" class="simplemodal-close"><?php echo Yii::t('app', 'btn.cancel'); ?></a>
			</form>
		</div>
	</div>
</div>
<div id="main-content" class="clear">
	<div class="sentence"><div id="search-sentence"><?php
		$company = Company::model()->findbyPk($_GET['company']); 
		$start = ($paging->currentPage * $paging->pageSize + 1);
		if ($start > $paging->itemCount)
		{
			$start =  $paging->itemCount;
		} 
    	$end = ($paging->currentPage + 1) * $paging->pageSize;
    	if ($end > $paging->itemCount)
    	{
    		$end = $paging->itemCount;
    	} 
    	echo  $company->companyName . ': ';	  	
    	echo Yii::t('app', 'user.count.and.current', 
    				array('{count}'=>$paging->itemCount, 
    		  		'{start}'=>$start,
    				'{end}'=>$end, 
    		  	));
    	?>
  </div></div>

	<div class="data-list hidden" id="ticket-list-wrapper">
	    <table cellspacing="0" cellpadding="0" class="data issues">
			<thead>
	      <tr>               
	        <th>
	        </th>
	        <th><?php echo Yii::t('app', 'user.email'); ?></th>
			<th><?php echo Yii::t('app', 'user.name'); ?></th>
			<th><?php echo Yii::t('app', 'user.roles'); ?></th>
	      </tr>
	    </thead>
			<?php if (is_null($paging->results)): 	?>
			<tr>
				<td colspan="4"><?php echo Yii::t('app', 'no.user'); ?></td>
			</tr>
			<?php else: ?>
			
			<?php foreach($paging->results as $c):?>
			<tr>
		        <td>
		   			<a class="dellink" href="#" rel="<?php echo $c->userId;?>"><?php echo Yii::t('app', 'delete'); ?></a>	   			        	        
		        </td>
		        <td><a href="<?php echo $this->createUrl('/admin/editUser', array('id'=>$c->userId,)); ?>"><?php echo $c->email;?></a></td>					
				<td><?php echo $c->userName; ?></td>
				<td><?php echo $c->roles; ?></td>
			</tr>			
			<?php endforeach; ?>
			
			<?php endif; ?>
		</table>		
	</div>


<?php if ($paging->pageCount > 1) { 
	$start = $paging->currentPage - 4;
	if ($start < 0) 
	{
		$start = 0; 
	}
	$end = $start + 10; 
	if ($end > $paging->pageCount) 
	{
		$end = $paging->pageCount;
		$start = $end - 10; 
		if ($start < 0) 
		{
			$start = 0;	
		}
	}
?>

<div class="pagination">
	<?php if ($paging->currentPage > 0) { ?>
			<a class="prev_page" href="javascript:gotopage(<?php echo ($paging->currentPage - 1); ?>)">« Previous</a>
	<?php } ?>	
	
	<?php // what's the start page 
		if ($start > 0) { echo '<a href="javascript:gotopage(0)">1</a>';  }
		if ($start > 1) { echo '<a href="javascript:gotopage(1)">2</a>';  }
		if ($start >= 3) { echo ' ... ';  }
	?>

	<?php for ($idx = $start; $idx < $end; ++$idx) { 
			if ($idx != $paging->currentPage) {	
	?>
		<a href="javascript:gotopage(<?php echo ($idx); ?>)"><?php echo ($idx + 1); ?></a>
	<?php
	}
	else {
	?>
			<span class="current"><?php echo ($idx + 1); ?></span>	
	<?php
	}}
	?>	
	<?php 
		if (($paging->pageCount - 3) >= $end) {echo ' ... '; }
		if (($paging->pageCount - 1) > $end) { echo '<a href="javascript:gotopage(' . ($paging->pageCount - 2) . ')">' . ($paging->pageCount - 1) . ' </a>'; }
		if (($paging->pageCount) > $end) { echo '<a href="javascript:gotopage(' . ($paging->pageCount - 1) . ')">' . $paging->pageCount . ' </a>';  }
	?>

	<?php if ($paging->currentPage < ($paging->pageCount - 1)) { ?>
		<a class="next_page" href="javascript:gotopage(<?php echo ($paging->currentPage + 1); ?>)">Next »</a>
	<?php } ?>	
</div>
<?php } ?>


<form id="pageform" method="post">			
	<input name="count" type="hidden" value="<?php echo $paging->itemCount; ?>" />
	<input name="page"  id="page" type="hidden" value="<?php echo $paging->currentPage; ?>" />
	<input name="sort" id="sort" type="hidden" value="<?php echo $paging->sort; ?>" />
	<input name="ascdesc" id="ascdesc" type="hidden" value="<?php echo $paging->ascdesc; ?>" />
</form>	

</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.simplemodal.js" type="text/javascript"></script>
<script>
function gotopage(p) {
	$("#page").val(p);
	$("#pageform").submit();	
}
$(document).ready(function () {
	$('a.dellink').click(function (e) {
		e.preventDefault();
		$('#delid').val($(this).attr('rel'));
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