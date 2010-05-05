<?php

class ProjectCommittee extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'ProjectCommittee';
	}

	public function rules()
	{
		return array(
			array('role','length','max'=>100),
		);
	}

	public function relations()
	{
		return array(
			'user'=>array(self::HAS_ONE, 'User', 'userId'),
		);
	}

	public function deleteMembership($userId, $companyId)
	{
		$this->deleteAll('userId=:userId and companyId=:companyId', array(':userId'=>$userId, ':companyId'=>$companyId));	
	}
}