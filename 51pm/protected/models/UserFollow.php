<?php

class UserFollow extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'UserFollow':
	 * @var string $follower
	 * @var string $follows
	 * @var string $startOn
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
		return 'UserFollow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('follower','length','max'=>100),
			array('follows','length','max'=>100),
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
			'follower' => 'Follower',
			'follows' => 'Follows',
			'startOn' => 'Start On',
		);
	}
}