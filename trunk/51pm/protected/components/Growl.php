<?php
class Growl 
{
	/**
	 * 
	 * @param $msg - a message key
	 * @return none
	 */
	public static function setMessageKey($msg) 
	{
		$m = Yii::t('app', $msg); 
		Yii::app()->session['growl'] = $m; 	
	}
	
	public static function setMessage($msg) 
    {
        Yii::app()->session['growl'] = $msg;  
    }
	
	public static function setPersisted()
	{
	   Yii::app()->session['persisted'] = true;
	}

    public static function isPersisted()
    {
       if (isset(Yii::app()->session['persisted'])) 
       {
        unset(Yii::app()->session['persisted']);
        return true;
       }
       
       return false;
    }
        	
	public static function getMessage() 
	{
		if (isset(Yii::app()->session['growl']))
		{
			$m = Yii::app()->session['growl'] ;
			unset(Yii::app()->session['growl']);
			return $m; 
		} 
		
		return null; 
	}
}