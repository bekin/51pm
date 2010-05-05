<div id="project-nav">
	<ul>
		<?php if (Yii::app()->user->userRecord->userId == $user->userId) { ?>
		<li id="t-proj" class="submenu-tab"><a href="<?php echo $this->createUrl('/user/myprofile'); ?>"><?php echo Yii::t('app', 'my.profile'); ?></a></li>
	<?php } ?>
		<li id="t-proj" class="submenu-tab selected"><a href="#"><?php echo Yii::t('app', 'my.activities'); ?></a></li>
	</ul>
</div>


<div id="page-top">
	<div class="greet clear">
    <a href="#"><img src="<?php echo $user->avatarImage();?>" class="avatar"/></a>
  	<h3><?php echo $user->userName;?><br><?php echo $user->email;?></h3>
	</div>
</div>

<div id="main-content" class="clear">
	<div id="events">
  	<ul class="data events">
		<?php foreach($activities as $a): 
				$ts = CDateTimeParser::parse($a->activityDate,'yyyy-MM-dd hh:mm:ss' );
				$dokens = getdate($ts);
				$info = unserialize($a->activityDesc);
				$desc = ''; 
				$aclass='';
				$aname = ''; 
				switch ($a->activityType) {
					case 'create_ticket':
					case 'update_ticket':
						$params = array(
								'{user}'=>$user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$user->userId, )),   
								'{ticket}'=>$info['title'],
								'{ticketurl}'=> $this->createUrl('/ticket/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', ($a->activityType == 'create_ticket') ? 'ticket.created.by' : 'ticket.updated.by', $params); 
						$aclass = 'eticket'; 
						$aname = Yii::t('app', 'ticket');
						break;
					case 'create_milestone':
						$params = array(
								'{user}'=>$user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$user->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/milestone/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'milestone.created.by' , $params); 
						$aclass = 'emilestone'; 
						$aname = Yii::t('app', 'milestone');
						break;					

					case 'create_message':
						$params = array(
								'{user}'=>$user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$user->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'message.created.by' , $params); 
						$aclass = 'emessage'; 
						$aname = Yii::t('app', 'message');
						
						break;	
					case 'reply_message':
						$params = array(
								'{user}'=>$user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$user->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'message.replied.by' , $params); 
						$aclass = 'ereply'; 
						$aname = Yii::t('app', 'reply');
						
						break;	
						
					case 'create_page':
						$params = array(
								'{user}'=>$user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$user->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/page/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'page.created.by' , $params); 
						$aclass = 'ereply'; 
						$aname = Yii::t('app', 'page');
						
						break;							
				}
		?>

		<li class="event clear">
		    <?php echo $desc; ?>
        <p class="day-break">
        	<?php if (LocaleManager::isChinese()): ?>
            <span class="day"><?php echo Yii::t('app', 'week.' . $dokens['wday']); ?></span>
            <span class="num"><?php echo $dokens['mon'] . Yii::t('app', 'month') . $dokens['mday'] . Yii::t('app', 'day')?></span>        	
        	<?php else: ?>
            <span class="day"><?php echo $dokens['weekday']?></span>
            <span class="num"><?php echo $dokens['month'] . ' ' . $dokens['mday']?></span>
          <?php endif; ?>  
        </p>
                
        <p class="emeta">
        <?php echo $info['project']; ?>
        </p>
       
       <span class="etype <?php echo $aclass; ?>"><?php echo $aname; ?></span>
		</li>	
		<?php endforeach; ?>	
		</ul>
	</div>
</div>
