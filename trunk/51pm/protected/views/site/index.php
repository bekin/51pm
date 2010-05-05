<?php 
	$projects = Yii::app()->user->userRecord->projects;
?>      
                  
<div id="page-top">
	<div class="greet clear">
    <img src="<?php echo User::currentAvatar();?>" class="avatar"/>
  	<h3><?php echo Yii::app()->user->userRecord->userName;?></h3>
    <p class="gmeta">
    	<ul class="reminder">
    	<?php
    	        $hasticket = false; 
    			foreach ($projects as $p): 
    				$tc = Ticket::openTicketCountForUser(Yii::app()->user->userRecord->userId, $p); 
					if ($tc > 0) {
					       $hasticket = true;						
							echo '<li>'; 
    					echo Yii::t('app', 'open.ticket.reminder', 
    								array('{count}'=>$tc, 
    											'{project}'=>$p->projectName, 
    											'{url}'=>$this->createUrl('/ticket/list', array('project'=>$p->id,)), ));
							echo '</li>';     											
					} 
    			endforeach;
    			
    			if (!$hasticket) {
        			echo '<li>';
        			echo Yii::t('app', 'you.got.no.ticket'); 
        			echo '</li>';
    			}
    	 ?>
  	</ul>
  	</p>
	</div>
</div>
<?php 
    $id64 = base64_encode(Yii::app()->user->userRecord->company->id);
    $name64 = base64_encode(Yii::app()->user->userRecord->company->companyName); 
?>        
<div class="clear" id="main-content">      
	<div class="link-bar">
	  <a href="<?php echo $this->createUrl('/site/rss', array('company'=>$name64, 'stream'=>$id64,));  ?>" class="atom"><?php echo Yii::t('app', 'subscribe');?></a>
	</div>
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
								'{user}'=>$a->user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
								'{ticket}'=>$info['title'],
								'{ticketurl}'=> $this->createUrl('/ticket/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', ($a->activityType == 'create_ticket') ? 'ticket.created.by' : 'ticket.updated.by', $params); 
						$aclass = 'eticket'; 
						$aname = Yii::t('app', 'ticket');
						break;
					case 'create_milestone':
						$params = array(
								'{user}'=>$a->user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/milestone/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'milestone.created.by' , $params); 
						$aclass = 'emilestone'; 
						$aname = Yii::t('app', 'milestone');
						break;					

					case 'create_message':
						$params = array(
								'{user}'=>$a->user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'message.created.by' , $params); 
						$aclass = 'emessage'; 
						$aname = Yii::t('app', 'message');
						
						break;	
					case 'reply_message':
						$params = array(
								'{user}'=>$a->user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
								'{title}'=>$info['title'],
								'{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
							); 
						$desc = Yii::t('app', 'message.replied.by' , $params); 
						$aclass = 'ereply'; 
						$aname = Yii::t('app', 'reply');
						
						break;	
					case 'create_page':
						$params = array(
								'{user}'=>$a->user->userName,
								'{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
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
		<a href="#"><img src="<?php echo $a->user->avatarImage(); ?>" class="avatar" /></a>
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