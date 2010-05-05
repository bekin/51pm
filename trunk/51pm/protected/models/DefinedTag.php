<?php

class DefinedTag extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'DefinedTag':
	 * @var integer $projectId
	 * @var string $tag
	 * @var string $createdBy
	 * @var string $createdOn
	 */

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
		return 'DefinedTag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('tag','length','max'=>40),
			array('createdBy','length','max'=>20),
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
			'projectId' => 'Project',
			'tag' => 'Tag',
			'createdBy' => 'Created By',
			'createdOn' => 'Created On',
		);
	}
}