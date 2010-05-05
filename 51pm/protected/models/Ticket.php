<?php

class Ticket extends CActiveRecord
{
	public $projectName;

	private $saveTags = true;

	private $saveNotifications = true;
	
	public $isOverdue = false;
	
	public $currentHistory; 
		
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Ticket';
	}

	public function rules()
	{
		return array(
			array('title','length','max'=>100),
			array('ticketStatus','length','max'=>20),
			array('ticketPriority','length','max'=>20),
			array('owner','length','max'=>40),
			array('version', 'required'),
			array('version, milestoneId', 'numerical', 'integerOnly'=>true),
		);
	}

	public function relations()
	{
		return array(
			'project' => array(self::BELONGS_TO, 'Project', 'projectId'),
			'company' => array(self::BELONGS_TO, 'Company', 'companyId'),
			'tickethistories' => array(self::HAS_MANY, 'Tickethistory', 'ticketId'),
			'users' => array(self::MANY_MANY, 'User', 'ticketnotification(ticketId, userId)'),
			'tickettags' => array(self::HAS_MANY, 'Tickettag', 'ticketId'),
			'attachments'=>array(self::HAS_MANY, 'Attachment', 'ticketId'),
			'ownerinfo' => array(self::BELONGS_TO, 'User', 'owner'),
		);
	}
	
	public function isNotifyAll()
	{
		return $this->isNewRecord || $this->notifications == '_all_'; 
	}
	
	public function getNotifyList()
	{
		if (!$this->isNotifyAll())
			return explode(',', $this->notifications);
		else
			return array();
	}

	protected function beforeValidate($scenario)	
	{
		if ($this->isNewRecord)
		{
			$this->createdBy = Yii::app()->user->userRecord->userId;
			$this->createdOn = new CDbExpression('NOW()');
			$this->version = 1;
		}
		$this->modifiedBy = Yii::app()->user->userRecord->userId;
		$this->modifiedOn = new CDbExpression('NOW()');
		
		$this->companyId = Yii::app()->user->userRecord->companyId;
		
		// duedate processing
		if ($this->duedate == '') 
		{
			$this->duedate = null; 
		}
		
		return CModel::beforeValidate($scenario);				
	}
	
	protected function beforeSave()	
	{
		if (!is_null($this->duedate)) 
		{
			$dateTimeOutcomeFormat = 'Y-m-d H:i:s';
			
			// duedate in db is stored as "datetime"
			if (LocaleManager::isChinese()) {
				$ts = CDateTimeParser::parse($this->duedate, 'yyyy-MM-dd' );	
			}
			else {
				$ts = CDateTimeParser::parse($this->duedate, 'MM/dd/yyyy' );	
			}
			
			$this->duedate = date($dateTimeOutcomeFormat, $ts); 
		}
		return parent::beforeSave(); 
	}
	
	protected function afterFind()
	{
		parent::afterFind();
		if (!is_null($this->duedate))
		{
			$today = getdate(); 
			$normalized = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);
						
			$dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';
			$ts = CDateTimeParser::parse($this->duedate, $dateTimeIncomeFormat); 
			
			if ($ts < $normalized)
			{
				$this->isOverdue = true;					
			}
			
			if (LocaleManager::isChinese()) {
				$this->duedate = date('Y-m-d', $ts);
			}
			else {
				$this->duedate = date('m/d/Y', $ts);
			}			
		}
	}
	
	public function toggleWatch($id, $userId)
	{
		$ticket = $this->findbyPk($id); 
		$iamwatching = false;
		$watchers = array(); 
		if ($ticket->isNotifyAll())
		{
			$project = Yii::app()->session['currentProject']; 
			$users = $project->users;	
			$iamwatching = true;
			foreach ($users as $u):
				if ($u->userId != $userId) 
				{
					$watchers[] = $u->userId; 	
				}
			endforeach;
		}
		else
		{			
			$watchers = explode(',', $ticket->notifications); 
			for ($i = 0; $i < count($watchers); $i++)
			{
				if ($watchers[$i] == $userId)
				{
					$iamwatching = true;
					array_splice($watchers, $i, 1);
					break;
				}
			}			
		}
		
		if ($iamwatching)
		{
			$tn = TicketNotification::model();
			$tn->deleteAll('ticketId=' . $ticket->id . ' and userId=\'' . $userId . '\'');			
		}
		else 
		{
			$watchers[] = $userId; 	
			$notify = new TicketNotification;
			$notify->userId = $userId;
			$notify->ticketId = $ticket->id;
			$notify->projectId = $ticket->projectId;
			$notify->companyId = $ticket->companyId; 							
			$notify->save();
		}
		
		$ticket->notifications = implode(',', $watchers); 
		$ticket->bareSave(); 
	}
	
	public function updateTicket($incoming)
	{
		$changes = array();
		if (!is_null($incoming->title) && $incoming->title != $this->title)
		{
			$changes['title'] = array($this->title, $incoming->title); 
			$this->title = $incoming->title;
		}
		
		if ($incoming->tags != $this->tags)
		{
			$changes['tags'] = array($this->tags, $incoming->tags);
			$this->tags = $incoming->tags;
		}
		else
		{
			$this->saveTags = false;				
		}
		
		if ($incoming->notifications != $this->notifications)
		{
			$changes['notifications'] = array($this->notifications, $incoming->notifications);
			$this->notifications = $incoming->notifications;
		}
		else
		{
			$this->saveNotifications = false;				
		}
		
		if ($incoming->ticketStatus != $this->ticketStatus)
		{
			$changes['ticketStatus'] = array($this->ticketStatus, $incoming->ticketStatus);
			$this->ticketStatus = $incoming->ticketStatus;
		}
				
		if ($incoming->ticketPriority != $this->ticketPriority)
		{
			$changes['ticketPriority'] = array($this->ticketPriority, $incoming->ticketPriority);
			$this->ticketPriority = $incoming->ticketPriority;
		}	
		
		if ($incoming->owner != $this->owner)
		{
			$changes['owner'] = array($this->owner, $incoming->owner); 
			$this->owner = $incoming->owner;		
		}
	
		if ($incoming->milestoneId != $this->milestoneId)
		{
			$changes['milestoneId'] = array($this->milestoneId, $incoming->milestoneId);
			$this->milestoneId = $incoming->milestoneId; 	
		}

		if ($incoming->ticketType != $this->ticketType)
		{
			$changes['ticketType'] = array($this->ticketType, $incoming->ticketType); 
			$this->ticketType = $incoming->ticketType; 
		}
	
		if ($incoming->duedate != $this->duedate) 
		{
			$changes['duedate'] = array($this->duedate, $incoming->duedate); 
			$this->duedate = $incoming->duedate;
		}
	
		if ($incoming->est != $this->est)
		{
			$changes['est'] = array($this->est, $incoming->est); 
			$this->est = $incoming->est;	
		}
	
		// generate a history record
		$th = new TicketHistory;
		$th->historyDate = new CDbExpression('NOW()');
		$th->historyDesc = serialize($changes);
		$th->comments = $incoming->ticketDesc;
		$th->userId = Yii::app()->user->userRecord->userId;
		$th->projectId = $this->projectId; 
		$th->ticketId = $this->id;
		$th->companyId = $this->companyId;
		$th->save(); 
		$this->currentHistory = $th->id;
		
		return $this->save();
	}
	
	public function bareSave($runValidation=true,$attributes=null)
	{
		return parent::save($runValidation, $attributes); 
	}
	
	public function save($runValidation=true,$attributes=null) 
	{
		$model=Ticket::model();
		$succeeded = true;
		$isNew = $this->isNewRecord;
		$transaction=$model->dbConnection->beginTransaction();
		try
		{
			if ($isNew)
			{
				// determine the display order	
				$this->displayOrder = Project::model()->updateTicketCount($this->projectId); 				
			}
			
			$succeeded = parent::save($runValidation, $attributes); 
			if ($succeeded)
			{
				// REDBOOKLAB
				// Save tags
				if ($this->saveTags) 
				{
					if (!$isNew)
					{
						$tt = TicketTag::model();
						$tt->deleteAll('ticketId=' . $this->id . ' and projectId=' . $this->projectId);
					}
					
					if (!is_null($this->tags))
					{
						$tags = explode(',', $this->tags);
						foreach ($tags as $t):
							$tag = new TicketTag;
							$tag->tag = $t;
							$tag->ticketId = $this->id;
							$tag->projectId = $this->projectId;
							$tag->companyId = $this->companyId;
							$tag->save();
						
						endforeach;
					}
				}
				
				//REDBOOKLAB
				// save notifications
				if ($this->saveNotifications) 
				{
					if (!$isNew)
					{
						$tn = TicketNotification::model();
						$tn->deleteAll('ticketId=' . $this->id . ' and projectId=' . $this->projectId);
					}
					
					if (!empty($this->notifications))
					{
						if ($this->notifications == '_all_') 
						{
							$project = Yii::app()->session['currentProject']; 
							$users = $project->users;
							$ns = array(); 
							foreach ($users as $u) : 
								$ns[] = $u->userId;
							endforeach;
						}
						else
						{
							$ns = explode(',', $this->notifications);
						}
						
						foreach ($ns as $n):
							$notify = new TicketNotification;
							$notify->userId = $n;
							$notify->ticketId = $this->id;
							$notify->projectId = $this->projectId;
							$notify->companyId = $this->companyId; 							
							$notify->save();
						
						endforeach;
					}
				}
				
				// TODO: refactor the following to be a separate function
				$count = 0; 			 
				$attaches = $_FILES['attachment'];
				for ($i = 0; $i < count($attaches); $i++) 
				{
					if (isset($attaches['tmp_name'][$i]) && $attaches['error'][$i] == 0)
					{
						$a = new Attachment;
						$name =  basename( $attaches['name'][$i]);
						$a->projectId = $this->projectId;						
						$a->fileName = $name;
						$a->companyId = $this->companyId;
						
						$idx = strrpos ( $name, '.');
						if (idx > 0) 
						{
							$a->title = substr($name, 0, idx);
						}
						else 
						{
							$a->title = $name;
						}
						$contentType = $attaches['type'][$i]; 
						$a->contentType = $contentType;
						$a->contentSize = $attaches['size'][$i];				
						if (strpos($contentType, 'image') === false)
						{
							$a->isImage = 0;
						}
						else 
						{
							$a->isImage = 1;	
						}
						
						$a->createdBy = $this->createdBy;
						$a->createdOn = $this->createdOn; 
						if ($isNew)
						{
							$a->ticketId = $this->id;
						}	
						else 
						{
							$a->ticketHistoryId = $this->currentHistory;	
						}
						$dates = getdate();
						
						$file_path = $dates['year']. DIRECTORY_SEPARATOR.
									$dates['mon']. DIRECTORY_SEPARATOR.
									$dates['mday']. DIRECTORY_SEPARATOR.
									$this->projectId; 
						$target_path = 
									//dirname(Yii::app()->basePath).
									Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR. $file_path;
									
						chdir(dirname(Yii::app()->basePath));			
						Utils::recursive_mkdir($target_path);
						$uuid = Utils::uuid();							
						$target_path = $target_path	. DIRECTORY_SEPARATOR.	$uuid; 
						
						$a->location = $file_path . DIRECTORY_SEPARATOR . $uuid;			
						$a->save();
	 					
						move_uploaded_file($attaches['tmp_name'][$i], $target_path);
						
						$count++;
					}
				
				}

				if ($count > 0)
				{
					$sql = 'update Ticket set attachmentCount = attachmentCount + '. $count . 
						' where id = ' .  $this->id;
					$connection = $model->dbConnection;  
					$command=$connection->createCommand($sql);
					$command->execute();	
				}
			}
			
			$this->logActivity($isNew ? Activity::TYPE_CREATE_TICKET : Activity::TYPE_UPDATE_TICKET);
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			throw new CDbException($e->getMessage(), 0, $e);
		}
		
		return $succeeded;
		
	}
	
	protected function logActivity($type)
	{
		$act = new Activity;
		$act->activityDate = $this->modifiedOn; 
		$act->activityType = $type; 
		$act->ticketId = $this->id; 
		$act->projectId = $this->projectId;
		$act->userId = $this->modifiedBy;
		$act->companyId = $this->companyId;
		$desc = array(
			'id'=>$this->id,
			'title'=>$this->title,
			'project'=>$this->projectName,
			'status'=>$this->ticketStatus,
		);
		$act->activityDesc = serialize($desc);
		$act->save();		
	}

	public function deleteTicket($id)
	{	
		$model=Ticket::model();
		
		$connection = $model->dbConnection;
		$transaction=$connection->beginTransaction();
		try
		{
			
$sql = <<< END
select location from Attachment where ticketId = :id or ticketHistoryId in 
				(select id from TicketHistory where ticketId = :ticketId)						
END;
		
			$command=$connection->createCommand($sql);
			$command->bindParam(":id", $id, PDO::PARAM_INT);
			$command->bindParam(":ticketId", $id, PDO::PARAM_INT);
			$cols = $command->queryColumn();	
			
			foreach ($cols as $loc) {
				unlink(Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR. $loc);
			}
			
$sql = <<< END
delete from Attachment where ticketId = :id or ticketHistoryId in 
				(select id from TicketHistory where ticketId = :ticketId)						
END;
			
			$command=$connection->createCommand($sql);
			$command->bindParam(":id", $id, PDO::PARAM_INT);
			$command->bindParam(":ticketId", $id, PDO::PARAM_INT);
			$command->execute();	
			
			$sql = 'delete from TicketTag where ticketId = ' . $id; 
			$command=$connection->createCommand($sql);
			$command->execute();	

			$sql = 'delete from Activity where ticketId = ' . $id;			  
			$command=$connection->createCommand($sql);
			$command->execute();	

			
			$sql = 'delete from Ticket where id = ' . $id;			  
			$command=$connection->createCommand($sql);
			$command->execute();	
		
			$transaction->commit();
		}
		catch(Exception $e)
		{
			Yii::log($e->__toString(), 'error');
			$transaction->rollBack();
		}
		
		return $succeeded;
		
	}
	
	private function formatCriteria($conditions)
	{
		$where = 'Ticket.companyId = ' . Yii::app()->user->userRecord->companyId;
		$params = array(); 
		if (isset($conditions['projectId']))
		{
			$where = $where . ' and projectId = ' . $conditions['projectId']; 			
		}
		if (isset($conditions['ticketStatus']))
		{
			$where = $where . ' and ticketStatus in (' . implode(',', $conditions['ticketStatus']) . ')'; 			
		}
		if (isset($conditions['owner']))
		{
			$where = $where . ' and owner = :owner'; 			
			$params[':owner'] = $conditions['owner']; 
		}
		if (isset($conditions['watcher']))
		{
			$where = $where . ' and (notifications = \'_all_\' or notifications like :watcher)'; 			
			$params[':watcher'] = $conditions['watcher']; 
		}
		if (isset($conditions['createdOn']) && 'today' == $conditions['createdOn'])
		{
			$dateTimeOutcomeFormat = 'Y-m-d';						
			$ymd = date($dateTimeOutcomeFormat, time()); 
			
			$where = $where . ' and Ticket.createdOn >= \'' . $ymd . ' 00:00:00\''; 			
			$where = $where . ' and Ticket.createdOn <= \'' . $ymd . ' 23:59:59\''; 			
		}
		if (isset($conditions['createdBy']))
		{
			$where = $where . ' and Ticket.createdBy = :createdBy'; 			
			$params[':createdBy'] = $conditions['createdBy']; 
		}
		if (isset($conditions['milestoneId']))
		{
			$where = $where . ' and milestoneId = :milestoneId'; 			
			$params[':milestoneId'] = $conditions['milestoneId']; 
		}
		if (isset($conditions['ticketPriority']))
		{
			$where = $where . ' and ticketPriority = :ticketPriority'; 			
			$params[':ticketPriority'] = $conditions['ticketPriority']; 
		}
		if (isset($conditions['ticketType']))
		{
			$where = $where . ' and ticketType = :ticketType'; 			
			$params[':ticketType'] = $conditions['ticketType']; 
		}
		if (isset($conditions['duedate']))
		{
			$dateTimeOutcomeFormat = 'Y-m-d';
			
			// duedate in db is stored as "datetime"
			if (LocaleManager::isChinese()) {
				$ts = CDateTimeParser::parse($conditions['duedate'], 'yyyy-MM-dd' );	
			}
			else {
				$ts = CDateTimeParser::parse($conditions['duedate'], 'MM/dd/yyyy' );	
			}
			
			$ymd = date($dateTimeOutcomeFormat, $ts); 
			$where = $where . ' and duedate >= \'' . $ymd . ' 00:00:00\''; 			
			$where = $where . ' and duedate <= \'' . $ymd . ' 23:59:59\''; 						
		}
		if (isset($conditions['overdue']))
		{
			$ymd = date('Y-m-d'); 
			$where = $where . ' and duedate < \'' . $ymd . ' 00:00:00\''; 						
		}
		if (isset($conditions['title']))
		{
			$where = $where . ' and title like :title'; 			
			$params[':title'] = '%' . $conditions['title'] . '%'; 
		}
		if (isset($conditions['tags']))
		{
			$tags = preg_split("/[\s,]+/", $conditions['tags']); 
			foreach ($tags as $n=>$t)
			{
				$where = $where . ' and tags like :tags' . $n; 			
				$params[':tags' . $n] = '%' . $t . '%';
			}			
		}	
		if (isset($conditions['tag']))
		{
			$where = $where . ' and tags like :tag'; 			
			$params[':tag'] = '%' . $conditions['tag'] . '%';
		}	
		
		return array($where, $params); 
	}
		

	/**
	 * 
	 * @param $conditions an array of serach conditions
	 * @param $paging an instance of CPagination
	 * @return an array of ticket records
	 */
	public function getTickets($needCount, $conditions, $paging)
	{
		$sortcols = array(
			'id'=>'id', 
			'title'=>'title', 
			'status'=>'ticketStatus',
			'owner'=>'owner',
			'duedate'=>'duedate',
			'age'=>'id'); 
		$wp = $this->formatCriteria($conditions);
		Yii::log('ticket search condition : ' . $wp[0], 'error'); 
		if ($needCount) 
		{
			$paging->itemCount = $this->count($wp[0], $wp[1]); //$this->getTicketCount($where);	
		}
		
		if ($paging->itemCount > 0)
		{
			$criteria = array (
				'condition'=>$wp[0],
				'order'=>$sortcols[$paging->sort] . ' ' . $paging->ascdesc,
				'limit'=>$paging->pageSize,
				'offset'=>$paging->currentPage * $paging->pageSize,
				'params'=>$wp[1],
			); 
			$tickets = $this->with('ownerinfo')->findAll($criteria);
			$paging->setResults($tickets);	
		}
		
		return $paging;
	}
	
	public function getTicketCount($where)
	{
		$sql = "select count(*) from Ticket where " . $where;
		Yii::log($sql, 'info');
		$model=Ticket::model();		
		$connection = $model->dbConnection;
		$command=$connection->createCommand($sql);
		return $command->queryScalar();			
	}
	
	public static function openTicketCountForMilestone($id, $project)
	{
		$s = Utils::quoted($project->getOpenStatuses());
		$where = ' milestoneId=' . $id . ' and ticketStatus in (' . implode(',', $s) . ')';
		return self::getTicketCount($where);
	}
	
	public static function openTicketCountForUser($userId, $project)
	{
		$s = Utils::quoted($project->getOpenStatuses());
		$where = ' owner=\'' . $userId . '\' and ticketStatus in (' . implode(',', $s) . ')';
		return self::getTicketCount($where);
	}
	
	
	public static function getTicketTallyForMilestones($projectId) 
	{
		$sql = 'select milestoneId as mid, count(*) as c from Ticket where projectId = ' . $projectId . ' group by milestoneId';		
		$model=Ticket::model();		
		$connection = $model->dbConnection;
		$command=$connection->createCommand($sql);
		$reader = $command->query();
		
		$result = array();
		foreach($reader as $row)
		{
			$result[$row['mid']] = $row['c'];
		}
		
		return $result;	
	}	
	
	public static function getOpenTicketTallyForMilestones($project) 
	{
		$s = Utils::quoted($project->getOpenStatuses());
		$sql = 'select milestoneId as mid, count(*) as c from Ticket where projectId = ' . 
				$project->id . 
				' and ticketStatus in (' . implode(',', $s) . ')' .
				' group by milestoneId';		
		$model=Ticket::model();		
		$connection = $model->dbConnection;
		$command=$connection->createCommand($sql);
		$reader = $command->query();
		
		$result = array();
		foreach($reader as $row)
		{
			$result[$row['mid']] = $row['c'];
		}
		
		return $result;
	}	
	
	public static function bulkUpdate()
	{
		$ids = $_POST['ids']; 
		if (empty($ids)) 
		{
			Yii::log('Empty bulk update request. ', 'error'); 
			return; 
		}
		$projectId = Yii::app()->session['currentProject']->id;
		$sql = array('update Ticket set'); 
		$needUpdate = false;
		if (isset($_POST['updateOwner']))
		{
			$needUpdate = true;
			$sql[] = ' owner=:owner'; 
		}
		if (isset($_POST['updateMilestoneId']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' milestoneId=:milestoneId'; 
		}
		if (isset($_POST['updateTicketStatus']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' ticketStatus=:ticketStatus'; 
		}
		if (isset($_POST['updateTicketPriority']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' ticketPriority=:ticketPriority'; 
		}
		if (isset($_POST['updateTicketType']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' ticketType=:ticketType'; 
		}
		if (isset($_POST['updateDuedate']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' duedate=:duedate'; 
		}
		if (isset($_POST['updateTags']))
		{
			if ($needUpdate) 
			{
				$sql[] = ', '; 
			}
			$needUpdate = true;
			$sql[] = ' tags=:tags'; 
		}


		if (!$needUpdate) {
			return; 
		}

		$model=Ticket::model();		
		$connection = $model->dbConnection;
		$sql_s = implode('', $sql) . ' where projectId=' . $projectId . ' and id in (' . $ids . ')';
		Yii::log('bulkupdate='.$sql_s, 'error');
		$command=$connection->createCommand($sql_s);
		
		if (isset($_POST['updateOwner']))
		{
			$command->bindParam(":owner", $_POST['owner'], PDO::PARAM_STR);
		}
		if (isset($_POST['updateMilestoneId']))
		{
			$command->bindParam(":milestoneId", $_POST['milestoneId'], PDO::PARAM_INT);
		}
		if (isset($_POST['updateTicketStatus']))
		{
			$command->bindParam(":ticketStatus", $_POST['ticketStatus'], PDO::PARAM_STR);
		}
		if (isset($_POST['updateTicketPriority']))
		{
			$command->bindParam(":ticketPriority", $_POST['ticketPriority'], PDO::PARAM_STR);
		}
		if (isset($_POST['updateTicketType']))
		{
			$command->bindParam(":ticketType", $_POST['ticketType'], PDO::PARAM_STR);
		}
		if (isset($_POST['updateDuedate']))
		{
			$dateTimeOutcomeFormat = 'Y-m-d H:i:s';
			
			if (LocaleManager::isChinese()) {
				$ts = CDateTimeParser::parse($_POST['duedate'], 'yyyy-MM-dd' );	
			}
			else {
				$ts = CDateTimeParser::parse($_POST['duedate'], 'MM/dd/yyyy' );	
			}
			
			$duedate = date($dateTimeOutcomeFormat, $ts); 
			
			$command->bindParam(":duedate", $duedate, PDO::PARAM_STR);
		}
		if (isset($_POST['updateTags']))
		{
			$tags = preg_split("/[\s,]+/", $_POST['tags']); 
			$command->bindParam(":tags", implode(',', $tags), PDO::PARAM_STR);
		}

		$command->execute();		
		
		if (isset($_POST['updateTags']))
		{
			
			$tt = TicketTag::model();
			$tt->deleteAll('ticketId in (' . $ids . ') and projectId=' . $projectId);
			
			$tids = preg_split('/[\s,]+/', $ids); 		
								
			foreach ($tids as $id):					
				foreach ($tags as $t):
						$tag = new TicketTag;
						$tag->tag = $t;
						$tag->ticketId = $id;
						$tag->projectId = $projectId;
								
						$tag->save();						
				endforeach;
			endforeach;	
		}
	}
	
	public function getTicketByDisplayOrder($projectId, $displayOrder)
	{
			return $this->find('projectId=:projectId and displayOrder=:displayOrder', 
				array(':projectId'=>$projectId, ':displayOrder'=>$displayOrder, ));
	}
	
	public function getShortcutTicketCount($s)
	{
		$conditions = array();
		$conditions['projectId'] = Yii::app()->session['currentProject']->id;
		if ($s == 'my') 
		{
			$conditions['owner'] = Yii::app()->user->userRecord->userId;	
			$st = Yii::app()->session['currentProject']->getOpenStatuses();
			$conditions['ticketStatus'] = Utils::quoted($st);
		}
		else if ($s == 'mywatch')
		{
			$conditions['watcher'] = Yii::app()->user->userRecord->userId;			
		} 
		else if ($s == 'open')
		{
			$s = Yii::app()->session['currentProject']->getOpenStatuses();
			$conditions['ticketStatus'] = Utils::quoted($s);				
		}
		else if ($s == 'today')
		{
			$conditions['createdOn'] = 'today';
		} 
		$wp = $this->formatCriteria($conditions);
		return $this->count($wp[0], $wp[1]); 
	}
	
	public function updateTicketDesc($desc)
	{
		$connection = $this->dbConnection;
		$sql = 'update Ticket set ticketDesc = :desc where id = ' . $this->id;
		$command=$connection->createCommand($sql);
		$command->bindParam(":desc", $desc, PDO::PARAM_STR);
		$command->execute();				
		
		
		$th = new TicketHistory;
		$th->historyDate = new CDbExpression('NOW()');
		$changes = array('ticketDesc'=>'change',);
		$th->historyDesc = serialize($changes);
		$th->userId = Yii::app()->user->userRecord->userId;
		$th->projectId = $this->projectId; 
		$th->ticketId = $this->id;
		$th->companyId = Yii::app()->user->userRecord->companyId;
		$th->save(); 
	}
}