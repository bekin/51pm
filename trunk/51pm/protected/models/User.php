<?php

class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('userId','length','max'=>100),
			array('userName','length','max'=>100),
			array('roles','length','max'=>200),
			array('tagline','length','max'=>100),
			array('password','length','max'=>40),
			array('avatar','length','max'=>200),
			array('jobTitle','length','max'=>100),
			array('email','length','max'=>100),
			array('emailPreference','length','max'=>100),
			array('website','length','max'=>100),
			array('lastIp','length','max'=>20),
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
			'activities' => array(self::HAS_MANY, 'Activity', 'userId'),
			'committees' => array(self::HAS_MANY, 'ProjectCommittee', 'userId'),
			'projects' => array(self::MANY_MANY, 'Project', 'ProjectCommittee(userId, projectId)'),
			'tickethistories' => array(self::HAS_MANY, 'Tickethistory', 'userId'),
			'tickets' => array(self::MANY_MANY, 'Ticket', 'TicketNotification(ticketId, userId)'),
			'company' => array(self::BELONGS_TO, 'Company', 'companyId'),
		);
	}

	public function getUsers($userIds)
	{
		$count = count($userIds);
		if ($count == 0) return array();
		 
		$params = array();
		$where = 'userId in (';
		for ($i = 0; $i < $count; $i++)
		{
			if ($i > 0) {
				$where = $where . ','; 				
			}	
			
			$pn = ':u' . $i;
			$where = $where . $pn; 
			$params[$pn] = $userIds[$i];
		}
		$where = $where . ')'; 
		
		$criteria = array (
				'condition'=>$where,
				'params'=>$params,
			); 
		return $this->findAll($criteria);			
	}


	protected function beforeSave()	
	{
		if ($this->isNewRecord) 
		{
			$this->password = md5($this->password);
		}
		return parent::beforeSave(); 
	}
	
	protected function beforeValidate($scenario)	
	{
		if ($this->isNewRecord)
		{
			$this->version = 1;
		}
		
		return CModel::beforeValidate($scenario);				
	}
	
	public function avatarImage()
	{
		if (empty($this->avatar)) 
		{
			return Yii::app()->request->baseUrl . '/images/avatar.gif'; 
		}
		else 
		{
			$n_avatar = str_replace("\\", '/', Yii::app()->params['avatarRoot'] . '/' . $this->avatar); 
			
			return Yii::app()->request->baseUrl . '/' . $n_avatar; 
		}
								
	}
	
	public static function currentAvatar()
	{
		return Yii::app()->user->userRecord->avatarImage();
	}
}