<?php

class TicketTag extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'TicketTag';
	}

	public function rules()
	{
		return array(
			array('tag','length','max'=>40),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function getTags($projectId)
	{
		$sql = 'SELECT tag, count(*) as c FROM  TicketTag where projectId = ' . $projectId . ' group by tag';
		
		$connection = $this->dbConnection;
		$command=$connection->createCommand($sql);
		$reader = $command->query();
		
		$result = array();
		foreach($reader as $row)
		{
			$result[$row['tag']] = $row['c'];
		}
		
		return $result;			
	}
}