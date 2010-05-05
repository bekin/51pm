<?php if (!$readonly) { ?>
<div id="action-nav">
<ul class="clear">
	<li><a href="<?php echo $this->createUrl('/milestone/create');?>"><?php echo Yii::t('app', 'milestone.create');?></a></li>
</ul>
</div>
<?php } ?>

<div id="main-content" class="clear">
	<div id="milestones">
		<div id="time-sensitive">
			<div id="upcoming">
			<h2><?php echo Yii::t('app', 'milestones');?></h2>
			<table cellspacing="0" cellpadding="0" class="milestone-list upcoming">
				<tbody>
				
					
					<?php if (!is_null($models)) {
						foreach ($models as $m):
					?>
					<tr class="milestone-item" id="ms_<?php echo $m->id;?>">
						<td class="lbl"><a class="ttl"	href="<?php echo $this->createUrl('/milestone/show', array('id'=>$m->id,));?>"><?php echo $m->title;?></a>
						<div><span class="due"> <span class="time-distance"><?php echo Time::timeAgoInWords($m->duedate);?></span>
						- <?php echo $m->duedate;?> - </span> <?php if (!$readonly) { ?><a href="<?php echo $this->createUrl('/milestone/update', array('id'=>$m->id,));?>"	class="move"><?php echo Yii::t('app', 'btn.edit');?></a><?php } ?></div>
			
						</td>
						<td class="remaining">
						
						<span class="label"><?php echo Yii::t('app', 'ticket.total');?></span>
						<span class="num"><?php if (isset($ticketTally[$m->id])) {echo $ticketTally[$m->id]; } else { echo '0';} ?></span> 
						</td>
						<td class="remaining">
						<span class="label"><?php echo Yii::t('app', 'ticket.opened');?></span>
						<span class="num"><?php if (isset($openTicketTally[$m->id])) {echo $openTicketTally[$m->id]; } else { echo '0';} ?></span> 
						</td>
						<td class="progress-bar">
						<div class="pbar-container">
						<div style="width: <?php if (isset($ticketTally[$m->id]) && isset($openTicketTally[$m->id])) {echo 100 - 100 * $openTicketTally[$m->id]/$ticketTally[$m->id]; } else { echo '0';} ?>%;" class="pbar"> </div>
						</div>
						</td>
					</tr>
					<?php endforeach; } ?>
					
				</tbody>
			</table>
			
			</div>
		</div>
	</div>

</div>
