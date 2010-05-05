<?php
class Invite extends CActiveRecord
{
	/*
	 * @var string $email
	 * @var string $uuid
	 * @var string $subject
	 * @var string $message
	 * @var string $invitedOn
	 * @var string $companyId
	 */

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Invite';
	}

	public function rules()
	{
		return array(
		);
	}

	public function relations()
	{
		return array(
		);
	}	
}