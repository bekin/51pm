<?php

class Milestone extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Milestone';
	}

	public function rules()
	{
		return array(
			array('title','length','max'=>100),
			array('createdBy','length','max'=>20),
			array('modifiedBy','length','max'=>20),
		);
	}

	public function relations()
	{
		return array(
			'project' => array(self::BELONGS_TO, 'Project', 'projectId'),
		);
	}

	protected function beforeValidate($scenario)	
	{		
		// duedate processing
		if ($this->duedate == '') 
		{
			$this->duedate = null; 
		}
		
		return CModel::beforeValidate($scenario);				
	}

	protected function beforeSave()	
	{
		if ($this->isNewRecord)
		{
			$this->createdBy = Yii::app()->user->userRecord->userId;
			$this->createdOn = new CDbExpression('NOW()');
		}
		
		$this->modifiedBy = Yii::app()->user->userRecord->userId;
		$this->modifiedOn = new CDbExpression('NOW()');
		
		$this->companyId = Yii::app()->user->userRecord->companyId;
		
		
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
			$dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';
			$ts = CDateTimeParser::parse($this->duedate, $dateTimeIncomeFormat); 
			if (LocaleManager::isChinese()) {
				$this->duedate = date('Y-m-d', $ts);
			}
			else {
				$this->duedate = date('m/d/Y', $ts);
			}			
		}
	}
		
	
	public function save($runValidation=true,$attributes=null) 
	{
		$model=Ticket::model();
		$succeeded = true;
		$isNew = $this->isNewRecord;
		$transaction=$model->dbConnection->beginTransaction();
		try
		{
			$succeeded = parent::save($runValidation, $attributes); 
			if ($succeeded && $isNew)
				$this->logActivity();
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			throw new CDbException($e->getMessage(), 0, $e);
		}
		
		return $succeeded;		
	}
	
	protected function logActivity()
	{
		$act = new Activity;
		$act->activityDate = $this->createdOn; 
		$act->activityType = Activity::TYPE_CREATE_MILESTONE; 
		$act->projectId = $this->projectId;
		$act->userId = $this->modifiedBy;
		$act->companyId = $this->companyId;
		$desc = array(
			'id'=>$this->id,
			'title'=>$this->title,
		);
		$act->activityDesc = serialize($desc);
		$act->save();		
	}
	
	public function getProjectMilestones($projectId)
	{
		$criteria=array(
			'condition'=>'projectId='.$projectId,
			'order'=>'id desc',
		);
		
		return $this->findAll($criteria);	
	}

	public function getCompanyMilestones($companyId)
	{
		$criteria=array(
			'condition'=>'companyId='.$companyId,
			'order'=>'id desc',
		);
		
		return $this->findAll($criteria);	
	}
	
	protected function afterDelete()
	{
		$sql = "update Ticket set milestoneId = 0 where milestoneId=" . $this->id;
		$model=Ticket::model();		
		$connection = $model->dbConnection;
		$command=$connection->createCommand($sql);
		$command->execute();	
			
		parent::afterDelete();
	}
	
}