<?php
class EmailManager 
{
	/**
	 * 
	 * @param $email - mixed
	 * @return none
	 */
	public static function setEmail($email) 
	{
		Yii::app()->session['email'] = $email; 	
	}
	
	public static function peek() 
	{
		if (isset(Yii::app()->session['email']))
		{
			return true;
		}
		else {
			return false;
		}
	}
	public static function getEmail() 
	{
		if (isset(Yii::app()->session['email']))
		{
			$email = Yii::app()->session['email'] ;
			unset(Yii::app()->session['email']);
			return $email; 
		} 
		
		return null; 
	}
	
}