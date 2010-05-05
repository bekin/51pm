<?php
class Activity extends CActiveRecord
{
	const TYPE_CREATE_TICKET = "create_ticket";
	const TYPE_UPDATE_TICKET = "update_ticket";
	const TYPE_CREATE_MESSAGE = "create_message";
	const TYPE_REPLY_MESSAGE = "reply_message";
	const TYPE_CREATE_MILESTONE = "create_milestone";
	const TYPE_CREATE_PAGE = "create_page";
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Activity';
	}

	public function rules()
	{
		return array(
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'userId', 'alias'=>'u'),
		);
	}	
	
	public function getProjectActivities($projectId)
	{
		$criteria=array(
			'condition'=>'projectId='.$projectId,
			'limit'=>200,
			'order'=>'id desc',
		);
		
		return $this->with('user')->findAll($criteria);	
	}

	public function getCompanyActivities($companyId)
	{
		$criteria=array(
			'condition'=>'Activity.companyId='.$companyId,
			'limit'=>200,
			'order'=>'id desc',
		);
		
		return $this->with('user')
				->findAll($criteria);	
	}

	public function getUserActivities($userId)
	{
		$criteria=array(
			'condition'=>'userId='.$userId,
			'order'=>'id desc',
		);
		
		return $this->findAll($criteria);	
	}

}