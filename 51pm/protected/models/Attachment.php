<?php

class Attachment extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'Attachment';
	}

	public function rules()
	{
		return array(
			array('fileName','length','max'=>100),
			array('title','length','max'=>100),
			array('contentType','length','max'=>50),
			array('location','length','max'=>200),
			array('createdBy','length','max'=>20),
			array('modifiedBy','length','max'=>20),
			array('ticketId, projectId, contentSize, isImage', 'numerical', 'integerOnly'=>true),
		);
	}

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
	
	protected function afterDelete()
	{
		$fullPath = Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR.$this->location;
		unlink($fullPath);
		parent::afterDelete();
	}
	
}