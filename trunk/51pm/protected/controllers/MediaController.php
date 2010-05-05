<?php

class MediaController extends _BaseController
{
	private function verify($id, $P)
	{
		$attach = Attachment::model()->findbyPk($id);	
		if ($attach->projectId != $P['project']) {
			Yii::log('The project does not match what is in the attachment', 'error');
			return null; 
		}
		
		// TODO: does the current user have access to this project
		
		
		if (isset($P['message']) && $attach->messageId != $P['message']) 
		{
			Yii::log('The message does not match what is the attachment', 'error');
			return null; 
		}

		if (isset($P['ticket']) && !empty($P['ticket']) && $attach->ticketId != $P['ticket']) 
		{
			Yii::log('The ticket does not match what is the attachment', 'error');
			return null; 
		}

		if (isset($P['ticketh']) && !empty($P['ticketh']) && $attach->ticketHistoryId != $P['ticketh']) 
		{
			Yii::log('The ticket history does not match what is the attachment', 'error');
			return null; 
		}
		
		return $attach;
		
	}
	
	public function actionDelete()
	{
		$attach = $this->verify($_POST['id'], $_POST);
		if (is_null($attach))	return; 
		
		$attach->delete();
		
		$fullPath = Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR.$attach->location;
		unlink($fullPath);
		$this->renderPartial('deljson');
	}
	
	public function actionGet() 
	{
		$attach = $this->verify($_GET['id'], $_GET);
		if (is_null($attach))	return; 
		
		$fullPath = Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR.$attach->location;
		
		if ($fd = fopen ($fullPath, "r")) {
		    $fsize = filesize($fullPath);
        header("Content-type: " . $attach->contentType); 
        // This line will force browser to show the download dialog box
        if ('application/octet-stream' == $attach->contentType) 
        {
        	header("Content-Disposition: attachment; filename=\"". $attach->fileName ."\"");
        }
		    header("Content-length: $fsize");
		    header("Cache-control: private"); 
		    while(!feof($fd)) {
		        $buffer = fread($fd, 2048);
		        echo $buffer;
		    }
		}
		fclose ($fd);		
	}
	
	public function actionEmail()
	{
		if(Yii::app()->request->isPostRequest)
		{
			
			$email = EmailManager::getEmail();
			if (is_null($email)) return;			
			
			$model=Ticket::model()->findbyPk($email['id']);	
			
			$to = array();
			$users = Yii::app()->session['currentProject']->users;
			if ($model->isNotifyAll()) {
				foreach ($users as $u) :
					$to[] = $u->email;
				endforeach;
			}
			else {
				$ids = $model->getNotifyList(); 
				foreach ($users as $u) :
					if (in_array($u->userId, $ids)) {
						$to[] = $u->email;
					}
				endforeach;
			}	
		
			$modifiedby = User::model()->findbyPk($model->modifiedBy);
			$params = array('{order}'=>$model->displayOrder, 
					  	'{by}'=>$modifiedby->userName,
						'{title}'=>$model->title,	);
			$body = '<h3>' . $model->title . '</h3>' . '<p>' . Yii::t('app', 'ticket.status') . ': ' . $model->ticketStatus . '</p>' .
						'<p>' . Yii::t('app', 'priority') . ': ' . Yii::t('app', 'priority.' . $model->ticketPriority) . '</p>'; 
		 
			if ($email['action'] == 'create')
			{
				$subject = Yii::t('app', 'subject.ticket.created', $params);
				$body = $body . '<p>' . $model->ticketDesc . '</p>';
			}
			else
			{
				$subject = Yii::t('app', 'subject.ticket.updated', $params);
				$h = TicketHistory::model()->findbyPk($email['history']);
				$body = $body . '<p>' . $h->comments. '</p>';
			}
			
			
			// no time limit, ingore errors
			@set_time_limit(0);
			@ignore_user_abort(TRUE);
			
			Yii::trace("sending ticket info: " . implode(',', $to));
			
			MailEngine::mailnow(
			     array('name'=>Yii::app()->params['fromname'], 'email'=>Yii::app()->params['from'], ), 
			     $to,
			     $subject,
			     $body);
		}	
	}
}