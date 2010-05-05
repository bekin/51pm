<?php

class TicketHistory extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'TicketHistory';
	}

	public function rules()
	{
		return array(
			array('historyType','length','max'=>20),
		);
	}

	public function relations()
	{
		return array(
			'attachments'=>array(self::HAS_MANY, 'Attachment', 'ticketHistoryId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId', 'alias'=>'u'),
		);
	}

	public function getHistories($ticketId)
	{
		$criteria=array(
			'condition'=>'ticketId='.$ticketId,
		);
		
		return $this->with('attachments', 'user')->findAll($criteria);	
	}

}