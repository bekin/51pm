<?php

class Company extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Company';
	}

	protected function beforeValidate($scenario)	
	{
		if ($this->isNewRecord)
		{
			$this->version = 1;
		}
		
		return CModel::beforeValidate($scenario);				
	}
	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('companyName','length','max'=>100),
			array('companyName', 'required'),
			array('siteUrl','length','max'=>100),
			array('externalWebsite','length','max'=>100),
			array('companyCode','length','max'=>40),
			array('email','length','max'=>30),
			array('phone','length','max'=>20),
			array('address','length','max'=>100),
			array('city','length','max'=>30),
			array('state','length','max'=>30),
			array('zip','length','max'=>10),
			array('contact','length','max'=>30),
			array('createdBy','length','max'=>20),
			array('modifiedBy','length','max'=>20),
			array('version', 'required'),
			array('version', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'projects' => array(self::HAS_MANY, 'Project', 'companyId'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'companyId'),
			'users' => array(self::HAS_MANY, 'User', 'companyId'),
		);
	}

	public function deleteCompany($id)
	{
		$model=Company::model();
		
		$connection = $model->dbConnection;
		$transaction=$connection->beginTransaction();
		$succeeded = true;
		try
		{	
			$tables = array('Invite', 'Page', 'ShortMsg', 'Milestone', 'Attachment', 
			'TicketNotification', 'TicketTag', 'Activity', 'TicketHistory', 'Ticket',			
			 'ProjectCommittee', 'ProjectCommittee', 'Project', 'User');
			
			foreach ($tables as $t):
				$sql = 'delete from '. $t . ' where companyId = ' . $id; 
				$command=$connection->createCommand($sql);
				$command->execute();				
			endforeach; 
			 

			$sql = 'delete from Company where id = ' . $id; 
			$command=$connection->createCommand($sql);
			$command->execute();	
			
		
			$transaction->commit();
		}
		catch(Exception $e)
		{
			Yii::log($e->__toString(), 'error');
			$transaction->rollBack();
			$succeeded = false;
		}
		
		return $succeeded;
	}
}