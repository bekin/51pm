<?php

class Page extends CActiveRecord
{
	const ACCOUNT_PAGE = 0; 
	const PROJECT_PAGE = 1;
	
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
		return 'Page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title','length','max'=>100),
			array('title,pageContent', 'required'),
			array('displayOrder', 'numerical', 'integerOnly'=>true),
			
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Title',
			'pageContent' => 'Page Content',
			'createdBy' => 'Created By',
			'modifiedBy' => 'Modified By',
			'modifiedOn' => 'Modified On',
			'createdOn' => 'Created On',
		);
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
		
		return CActiveRecord::beforeSave();				
	}
	
	public function findPages($companyId, $type) 
	{
		$criteria=array(
			'condition'=>'companyId='.$companyId.' and pageType='.$type,
			'order'=>'displayOrder, title',
		);
		
		return $this->findAll($criteria);		
	}


	public function findProjectPages($companyId, $projectId) 
	{
		$criteria=array(
			'condition'=>'companyId='.$companyId.' and projectId='.$projectId,
			'order'=>'displayOrder, title',
		);
		
		return $this->findAll($criteria);		
	}
}