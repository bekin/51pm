<?php 
		$projectId = Yii::app()->session['currentProject']->id; 
		$milestones = Milestone::model()->getProjectMilestones($projectId); 
?>
<div id="quick-search-bar">
    	<label for="n"><?php echo Yii::t('app', 'ticket.goto'); ?>:</label> <input style="width:100px!important;" type="search" value="" name="n" id="n"/>            
  		<?php echo Yii::t('app', 'or'); ?>
  		<label><?php echo Yii::t('app', 'show.me'); ?>:</label> 
  			<select name="filter" id="filter">
  			<option value="">...</option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'all',)); ?>"><?php echo Yii::t('app', 'ticket.all'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'today',)); ?>"><?php echo Yii::t('app', 'ticket.today'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'mywatch',)); ?>"><?php echo Yii::t('app', 'ticket.mywatch'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'my',)); ?>"><?php echo Yii::t('app', 'ticket.my'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'reportedbyme',)); ?>"><?php echo Yii::t('app', 'ticket.reported.by.me'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'overdue',)); ?>"><?php echo Yii::t('app', 'ticket.overdued'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'open',)); ?>"><?php echo Yii::t('app', 'ticket.opened'); ?></option>
				<option value="<?php echo $this->createUrl('/ticket/list', array('s'=>'close',)); ?>"><?php echo Yii::t('app', 'ticket.closed'); ?></option>
			</select>	
</div>

<div class="clear" id="main-content">
  <div class="sentence">
    <div id="search-sentence"><?php 
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
    	echo Yii::t('app', 'ticket.count.and.current', 
    				array('{count}'=>$paging->itemCount, 
    		  		'{start}'=>$start,
    				'{end}'=>$end, 
    		  	));?>
    <?php if (!empty($desc)) { echo '(', $desc, ')'; } ?>		  	
    </div>
    
    <div style="display: none;" id="all-tickets">
      <p>
      	<?php echo Yii::t('app', 'ticket.select.count');?><span id="all-tickets-count">0</span>. 
      </p>
    </div>
    
    <a href="/ticket/csv" class="csv">CSV</a>
  </div>



	<div class="data-list hidden" id="ticket-list-wrapper">
    <table cellspacing="0" cellpadding="0" class="data issues">
	<thead>
      <tr>       
        <th><input type="checkbox" id="alltoggle"/></th>
        <th> </th>
        <th style="width: 25px; text-align: center;" class="hfirst"><a rel="<?php if ($paging->sort == 'id') {echo $paging->ascdesc;} ?>" href="#" class="ordersort">#<?php if ($paging->sort == 'id') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
        <th><a rel="<?php if ($paging->sort == 'status') {echo $paging->ascdesc;} ?>" href="#" class="statussort"><?php echo Yii::t('app', 'ticket.status'); ?><?php if ($paging->sort == 'status') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
        <th><a rel="<?php if ($paging->sort == 'title') {echo $paging->ascdesc;} ?>" href="#" class="titlesort"><?php echo Yii::t('app', 'ticket.title'); ?><?php if ($paging->sort == 'title') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
        <th><a rel="<?php if ($paging->sort == 'duedate') {echo $paging->ascdesc;} ?>" href="#" class="duedatesort"><?php echo Yii::t('app', 'duedate.short'); ?><?php if ($paging->sort == 'duedate') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
        <th><a rel="<?php if ($paging->sort == 'owner') {echo $paging->ascdesc;} ?>" href="#" class="ownersort"><?php echo Yii::t('app', 'owner'); ?><?php if ($paging->sort == 'owner') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
        <th class="hlast"><a rel="<?php if ($paging->sort == 'age') {echo $paging->ascdesc;} ?>" href="#" class="agesort"><?php echo Yii::t('app', 'age'); ?><?php if ($paging->sort == 'age') { if ($paging->ascdesc == 'asc') { echo ' <span class="asc">&uarr;</span>'; } else {echo ' <span class="desc">&darr;</span>';} }  ?></a></th>
      </tr>
    </thead>
    <tbody id="open-tickets">
	<?php if (!is_null($paging->results)) {
		function findM($mid, $milestones)
		{
			if (!is_null($milestones) && count($milestones) > 0 && $mid != 0) 
			{
				foreach ($milestones as $m):
					if ($mid == $m->id) return $m->title;		
				endforeach;
			}

			return '';
		}
	?>
    <?php foreach($paging->results as $t):?>
     <tr id="ticket-<?php echo $t->id;?>" title="<?php echo CHtml::encode($t->ticketDesc);?>">
        <td><input type="checkbox" value="<?php echo $t->id;?>" name="edit[]" id="chx_<?php echo $t->id;?>" class="edit-flag"/></td>
        <td><span title="<?php echo Yii::t('app', 'priority');?>: <?php echo Yii::t('app', 'priority'.'.'.$t->ticketPriority);?>" class="tprio-icon tprio-icon-<?php echo $t->ticketPriority;?>"> </span></td>
        <td style="text-align: center;" class="tnum"><a href="<?php echo $this->createUrl('/ticket/show', array('id'=>$t->id,)); ?>"><?php echo $t->displayOrder;?></a></td>
        <td class="ttstate"><span style="color: rgb(170, 170, 170);" class="tstate"><?php echo $t->ticketStatus;?></span></td>
        <td class="issue st-open"><a href="<?php echo $this->createUrl('/ticket/show', array('id'=>$t->id,)); ?>"><?php echo $t->title;?></a></td>
        <td><?php 
        if ($t->isOverdue) 
        {
        	echo '<font color="red">';	
        }
        echo $t->duedate;
        if ($t->isOverdue) 
        {
        	echo '</font>';	
        }
        
        ?></td>
        <td><a href="<?php echo $this->createUrl('/user/show', array('id'=>$t->owner,)); ?>"><?php echo $t->ownerinfo->userName;?></a></td>
        <td class="date"><?php 
        	$ops = array();
        	if (LocaleManager::isChinese())
        	{
        		$ops['format'] = 'Y-m-d'; 
        	}
        	else
        	{
        		$ops['format'] = 'M d, Y'; 	
        	}
        	echo Time::timeAgoInWords($t->createdOn, $ops);?></td>
      </tr>    
    <?php endforeach;?>
    <?php } ?>
        </tbody>
  </table>
  
</div>

<?php if ($paging->pageCount > 1) { ?>

<?php
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

<?php // we want display tag if the url is tag related
	if (isset($_GET['tag']))
	{
		$action = $this->createUrl('/ticket/list', array('tag'=>$_GET['tag']));
	}
	else 
	{
		$action = $this->createUrl('/ticket/list');
	}
?>
<form id="pageform" method="post" action="<?php echo $action; ?>">			
	<input name="count" type="hidden" value="<?php echo $paging->itemCount; ?>" />
	<input name="page"  id="page" type="hidden" value="<?php echo $paging->currentPage; ?>" />
	<input name="sort" id="sort" type="hidden" value="<?php echo $paging->sort; ?>" />
	<input name="ascdesc" id="ascdesc" type="hidden" value="<?php echo $paging->ascdesc; ?>" />
	<input name="conds"  id="conds" type="hidden" value="<?php $conds = serialize($conditions); echo CHtml::encode($conds);?>" />
</form>	
<form id="csvform" target="_blank" method="post" action="<?php echo $this->createUrl('/ticket/csv'); ?>"><input name="count" type="hidden" value="<?php echo $paging->itemCount; ?>" /><input name="conds" id="csvconds" type="hidden" value="" /></form>	

</div>

<div class="floatbox-wrap" style="display:none;" id="bulkops">
		<div class="floatbox-item-wrapper">
			<div style="display: block;padding-left:12px;padding-right:12px;" class="floatbox-item notice">
<?php // start of BULKEDIT FORM: REDBOOKLAB?>
<h3><?php echo Yii::t('app', 'you.selected');?>: <span id="counter" style="color:green;"></span></h3>
<form method="post" id="bulkform" class="bulk-form" action="<?php echo $this->createUrl('/ticket/bulk'); ?>">
	<input name="count" type="hidden" value="<?php echo $paging->itemCount; ?>" />
	<input name="page"  type="hidden" value="<?php echo $paging->currentPage; ?>" />
	<input name="conds" type="hidden" value="<?php $conds = serialize($conditions); echo CHtml::encode($conds);?>" />
	<input name="sort" type="hidden" value="<?php echo $paging->sort; ?>" />
	<input name="ascdesc" type="hidden" value="<?php echo $paging->ascdesc; ?>" />
	<input type="hidden" name="ids" id="ids" value="" />
	<ul class="list-form clear" style="position: relative;">
		<li>
			<label for="owner"><input type="checkbox" value="1" checked="" name="updateOwner" style="width:auto!important;" /> <?php echo Yii::t('app', 'owner');?></label>
					<select name="owner" id="owner">
							<option value=""><?php echo Yii::t('app', 'none');?></option>
							<?php 
									$project = Yii::app()->session['currentProject']; 
									$users = $project->users;
									foreach ($users as $u) : 
							?>
							<option value="<?php echo $u->userId; ?>"><?php echo $u->userName; ?></option>
							<?php endforeach; ?>
					</select>
		</li>
		<li><label for="milestoneId"><input type="checkbox" value="1" checked="" name="updateMilestoneId" style="width:auto!important;" /> <?php echo Yii::t('app', 'milestone');?></label> 
				<select	name="milestoneId" id="milestoneId">
				<option selected="selected" value="0"><?php echo Yii::t('app', 'none');?></option>
				<?php  
					foreach ($milestones as $m):?>
					<option value="<?php echo $m->id;?>"><?php echo $m->title;?></option>
				<?php endforeach;?>
		</select></li>
		<li><label for="ticketStatus"><input type="checkbox" value="1" checked="" name="updateTicketStatus" style="width:auto!important;" /> <?php echo Yii::t('app', 'ticket.status');?></label> <select
				name="ticketStatus" id="ticketStatus">
				<?php $ss = Yii::app()->session['currentProject']->getStatuses(); 
					foreach ($ss as $s): ?>
						<option value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select></li>
		<li><LABEL for=ticketPriority><input type="checkbox" value="1" checked="" name="updateTicketPriority" style="width:auto!important;" /> <?php echo Yii::t('app', 'priority');?></LABEL> 
				<SELECT id="ticketPriority" name="ticketPriority">
					<OPTION value="high"><?php echo Yii::t('app', 'priority.high');?></OPTION> 
					<OPTION value="medium"><?php echo Yii::t('app', 'priority.medium');?></OPTION> 
					<OPTION value="low"><?php echo Yii::t('app', 'priority.low');?></OPTION></SELECT> 
			</li>
		<li><label for="duedate"><input type="checkbox" value="1" checked="" name="updateDuedate" style="width:auto!important;" /> <?php echo Yii::t('app', 'duedate');?></label> 
				<input name="duedate" id="duedate" ></li>
				
		<li><label for="ticketType"><input type="checkbox" value="1" checked="" name="updateTicketType" style="width:auto!important;" /> <?php echo Yii::t('app', 'ticket.type');?></label> 
			<select name="ticketType" id="ticketType">
				<?php $ss = Yii::app()->session['currentProject']->getTypes(); 
					foreach ($ss as $s): ?>
						<option value="<?php echo $s['label']; ?>"><?php echo $s['label']; ?></option>
						
				<?php 	
					endforeach;
				?>
			</select></li>
		
		<li>
			<label for="tags"><input type="checkbox" value="1" checked="checked" name="updateTags" style="width:auto!important;" /> <?php echo Yii::t('app', 'tags');?>:</label>
			<input type="text" value=""  name="tags" id="tags"  />		
		</li>
	</ul>	
<p style="clear: left;" class="btns">
   	<input type="submit" value="<?php echo Yii::t('app', 'bulk.update');?>" name="commit" id="btnbulk" /> 
</p>
</form>
<?php // end of BULKEDIT FORM: REDBOOKLAB
 ?>			
			</div>
		</div>
</div>

<script>
var inited = false;
function gotopage(p) {
	$("#page").val(p);
	$("#pageform").submit();	
}
function sortpage(ad, by) {
	$('#ascdesc').val((ad == 'asc') ? 'desc' : 'asc');
	$('#sort').val(by);
	$("#pageform").submit();
}
function bulkedit() {
	var len = $('input.edit-flag:checked').length;
	if ( len > 0) {
		$('#counter').html(len);
		if (!inited) { 
			floatbox.init({
				targetid: 'bulkops',
				//anchorele: 'anchorele',
				orientation: 2,
				position: [205, 100],
				fadeduration: [1000, 1000],
				frequency: 0.95
				//hideafter: 150000
			});
			inited = true;
		}
		else {
			floatbox.show('bulkops');
		}
			 
	}
	else {
		floatbox.hide('bulkops'); 
		inited = false;

	}	
}
$(document).ready(function()	{
	$(".edit-flag").click(bulkedit);

	$("#alltoggle").click(function() {
		$(".edit-flag").attr("checked", this.checked);
		bulkedit();	
	});
	
	$('#n').change(function() {
			window.location = '<?php echo $this->createUrl('/ticket/display'); ?>?n=' + this.value;
		}); 
	$('#filter').change(function() {
			window.location = this.value;
		}); 
		
	 $('#n').keyup(function(e) {
      if(e.keyCode == 13) {
			window.location = '<?php echo $this->createUrl('/ticket/display'); ?>?n=' + this.value;
      }
   });	

	$(".csv").click(function() {
		$("#csvconds").val($("#conds").val());
		$("#csvform").submit();
		return false;  
	});    
	
	$("#bulkform").submit(function() {
		var ids = new Array();
		$('input.edit-flag:checked').each(function(i) {
				ids.push(this.value); 
		}); 
		$('#ids').val(ids.join(',')); 
		return true;
	});

	$('.ordersort').click(function() {
		sortpage(this.rel, 'id'); 
		return false;
	});
	$('.statussort').click(function() {
		sortpage(this.rel, 'status');
		return false;
	});
	$('.duedatesort').click(function() {
		sortpage(this.rel, 'duedate');
		return false;
	});
	$('.titlesort').click(function() {
		sortpage(this.rel, 'title');
		return false;
	});
	$('.ownersort').click(function() {
		sortpage(this.rel, 'owner');
		return false;
	});
	$('.agesort').click(function() {
		sortpage(this.rel, 'id');
		return false;
	});
		
});


</script>