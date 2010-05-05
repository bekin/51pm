<?php if (!empty(Yii::app()->session['currentProject'])):?>
<div id="action-nav">
	<ul class="clear">
		<li><a href="<?php echo $this->createUrl('/message/create', array('project'=>Yii::app()->session['currentProject']->id,)); ?>"><?php echo Yii::t('app', 'new.message');  ?></a></li>
    </ul>
</div>
<?php endif;?>
<div id="main-content" class="clear">
	<div class="dated-list">
		<div class="message-list">		
		<?php if (count($messages) == 0) {?>
		<div class="nada">
      	<p>
        	<?php echo Yii::t('app', 'no.message');?>
         </p>
    	</div>
		<?php } else { ?>
		<ul id="messages" class="messages">
		<?php foreach ($messages as $n=>$m) {
			$ts = CDateTimeParser::parse($m->createdOn,'yyyy-MM-dd hh:mm:ss' );
			$dokens = getdate($ts);
			?>
		<li class="msg <?php if (($n % 2) > 0) {echo 'shaded';}?> " id="message-<?php echo $m->id; ?>">
          <img src="<?php echo $m->poster->avatarImage(); ?>" class="avatar" />
          <h3><a href="<?php echo $this->createUrl('/message/show', array('id'=>$m->id)); ?>"><?php echo $m->title;?></a></h3>
          <div class="msg-meta">
            <?php echo Yii::t('app', 'posted.by');?>: <a href="#"><?php echo $m->poster->userName;?></a>
            |
            <?php echo Yii::t('app', 'posted.on');?>: 
            
            	<?php 
            	$inSeconds = strtotime($m->createdOn);
            	echo date(LocaleManager::isChinese()? 'Y-m-d':'M d, Y', $inSeconds);
				?> 
            | 
            <?php echo Yii::t('app', 'comments');?>: <?php echo $m->commentCount; ?> 
            |  
            <?php echo Yii::t('app', 'attachments');?>: <?php echo $m->attachmentCount; ?> 
          </div>
          <div class="desc"><div><p><?php echo $m->msg;?></p></div></div>
          
          <p class="day-break">
					<?php if (LocaleManager::isChinese()): ?>
            <span class="day"><?php echo Yii::t('app', 'week.' . $dokens['wday']); ?></span>
            <span class="num"><?php echo $dokens['mon'] . Yii::t('app', 'month') . $dokens['mday'] . Yii::t('app', 'day')?></span>        	
        	<?php else: ?>
            <span class="day"><?php echo $dokens['weekday']?></span>
            <span class="num"><?php echo $dokens['month'] . ' ' . $dokens['mday']?></span>
          <?php endif; ?>            	          	
          </p>
          </li>
		
		<?php } ?>
		</ul>		
		<?php }?>
		</div>
	</div>
</div>
