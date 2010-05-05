<?php

class UserIdentity extends CUserIdentity
{
	private $_name;
	
	public function authenticate()
	{ 
		$record=User::model()->findByAttributes(array('email'=>$this->username));

		if($record===null || !$record->active)
		{		
            $this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		
        else if($record->password!==md5($this->password))
        {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
        else
        {
            $this->_name=$record->userName;
            $this->setState('userRecord', $record);
         
         		Yii::log($this->_name . ' has logged in ');
            
            //loading some roles
            //$this->setState('title', $record->title);
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode;
	}
	
	public function getName()
    {
        return $this->_name;
    }
}