<?php

class Project extends CActiveRecord
{
	public static function getDfltOpenStatusesText()
	{
		$text =  
			Yii::t('app', 'status.new') . "\n" .
			Yii::t('app', 'status.open') ;

		return $text;	
	}
	
	public static function getDfltOpenStatuses()
	{
		return 
			array(
				Yii::t('app', 'status.new'),
				Yii::t('app', 'status.open'),
		); 
	}
	
	public function getOpenStatuses()
	{
		if (!isset($this->openStates) || is_null($this->openStates) || $this->openStates == '') 
		{	
			$statuses = self::getDfltOpenStatuses();
		}	
		else
		{
			$lines = explode("\n", $this->openStates);
			$statuses = array();
			foreach ($lines as $l):
				$statuses[] = trim($l);
			endforeach;
			
		}
		
		return $statuses;
	}

	
	public static function getDfltCloseStatusesText()
	{
		$text =  
			Yii::t('app', 'status.resolved') . "\n" .
			Yii::t('app', 'status.hold') . "\n" .
			Yii::t('app', 'status.invalid') ;

		return $text;	
	}
	
	public static function getDfltCloseStatuses()
	{
		return 
			array(
			Yii::t('app', 'status.resolved'),
			Yii::t('app', 'status.hold'),
			Yii::t('app', 'status.invalid'),
		); 
	}
	
	public function getCloseStatuses()
	{
		if (!isset($this->closeStates) || is_null($this->closeStates) || $this->closeStates == '') 
		{	
			$statuses = self::getDfltCloseStatuses();
		}	
		else
		{
			$lines = explode("\n", $this->closeStates);
			$statuses = array();
			foreach ($lines as $l):
				$statuses[] = trim($l);
			endforeach;
			
		}
		
		return $statuses;
	}
	
	
	public static function getDfltStatusesText()
	{
		$text =  
			Yii::t('app', 'status.new') . "/FF0000\n" .
			Yii::t('app', 'status.open') . "/0000FF\n" .
			Yii::t('app', 'status.resolved') . "/00FF00\n" .
			Yii::t('app', 'status.hold') . "/CC0000\n" .
			Yii::t('app', 'status.invalid') . "/FFFF00";

		return $text;	
	}
	
	public static function getDfltStatuses()
	{
		return 
			array(
				array('label'=>Yii::t('app', 'status.new'), 'color'=>'FF0000',),
				array('label'=>Yii::t('app', 'status.open'), 'color'=>'0000FF',),
				array('label'=>Yii::t('app', 'status.resolved'), 'color'=>'00FF00',),
				array('label'=>Yii::t('app', 'status.hold'), 'color'=>'CC0000',),
				array('label'=>Yii::t('app', 'status.invalid'), 'color'=>'FFFF00',),		
			); 
	}
	
	public function getStatuses()
	{
		if (!isset($this->ticketStatues) || is_null($this->ticketStatuses) || $this->ticketStatuses == '') 
		{	
			$statuses = self::getDfltStatuses();
		}	
		else
		{
			$lines = explode("\n", $this->ticketStatues);
			$statuses = array();
			foreach ($lines as $l):
				$pair = explode('/', trim($l)); 
				$statuses[] = array('label'=>$pair[0], 'color'=>$pair[1],);
			endforeach;
			
		}
		
		return $statuses;
	}

	public function getStatusColor($status)
	{
		foreach ($this->getStatuses() as $s): 
			if ($s['label'] == $status) 
			{
				return $s['color']; 
			}
		endforeach;
		return "333"; 
	}
	
	public static function getDfltTypesText()
	{
		$text =  
			Yii::t('app', 'type.bug') . "/FF0000\n" .
			Yii::t('app', 'type.task') . "/0000FF\n" .
			Yii::t('app', 'type.newfeature') . "/00FF00" ;

		return $text;	
	}
	
	public static function getDfltTypes()
	{
		return 
			array(
				array('label'=>Yii::t('app', 'type.bug'), 'color'=>'FF0000',),
				array('label'=>Yii::t('app', 'type.task'), 'color'=>'0000FF',),
				array('label'=>Yii::t('app', 'type.newfeature'), 'color'=>'00FF00',),
			); 
	}
	
	public function getTypes()
	{
		if (empty($this->ticketTypes)) 
		{	
			$types = self::getDfltTypes();
		}	
		else
		{
			$lines = explode("\n", $this->ticketTypes);
			$types = array();
			foreach ($lines as $l):
				$pair = explode('/', trim($l)); 
				$types[] = array('label'=>$pair[0], 'color'=>$pair[1],);
			endforeach;
			
		}
		
		return $types;
	}
	
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('projectName','length','max'=>100),
			array('projectType','length','max'=>50),
		);
	}

	/**
	 * 
	 */
	protected function beforeSave()	
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
		
		return CActiveRecord::beforeSave();				
	}
	
	/**
	 * Uncomment the following for optimistic locking
	 */
	/*
	public function update($attributes=null)
	{
		if($this->getIsNewRecord())
			throw new CDbException(Yii::t('yii','The active record cannot be updated because it is new.'));
		if($this->beforeSave())
		{
			Yii::trace(get_class($this).'.update()','system.db.ar.CActiveRecord');
			$attrs = $this->getAttributes($attributes);
			$rows = $this->updateByPk($this->getPrimaryKey(), $attrs, 'version=' . $attrs['version']);
			if ($rows < 1) 
			{
				throw new DbOptimisticLockingException('The record being updated is stale');
				
			}
			$this->afterSave();
			return true;
		}
		else
			return false;
	}
	*/
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'milestones' => array(self::HAS_MANY, 'Milestone', 'projectId'),
			'company' => array(self::BELONGS_TO, 'Company', 'companyId'),
			'users' => array(self::MANY_MANY, 'User', 'ProjectCommittee(projectId, userId)'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'projectId'),
			'tickethistories' => array(self::HAS_MANY, 'TicketHistory', 'projectId'),
			'tickettags' => array(self::HAS_MANY, 'TicketTag', 'projectId'),
			'committee'=>array(self::HAS_MANY, 'ProjectCommittee', 'projectId', ), 
		);
	}
	
	
	public function findProjects($companyId, $limit=100)
	{
		$criteria=array(
			'condition'=>'companyId=' .$companyId,
			'order'=>'id DESC',
			'limit'=>$limit,
		);
		return $this->findAll($criteria);
	}

	/**
	* Update the ticket count for a project
	* @return updated ticket count
	*
	*/	
	public function updateTicketCount($projectId)
	{
		$model=Project::model();	
		$sql = 'update Project set tickets = tickets + 1 where id = ' .  $projectId;
		$connection = $model->dbConnection;  
		$command=$connection->createCommand($sql);
		$command->execute();		
		
		$sql = 'select tickets from Project where id = ' .  $projectId;
		$command=$connection->createCommand($sql);
		return $command->queryScalar(); 

	}	
}
