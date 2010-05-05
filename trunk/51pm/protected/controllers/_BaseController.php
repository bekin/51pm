<?php
class _BaseController extends CController
{
	public $menutype = 'site';
	public $activemenu = 'dashboard';
	public $currentProject; 
	
	//we can populate some error msg or success msg.
	public $_params = array();
	
	//override the render method.
	public function render($view,$data=null,$return=false){
		Yii::app()->params['activemenu'] = $this->activemenu;
		if($data!=null){
			$data = array_merge($data,$this->_params);
		}else{
			$data = $this->_params;
		}
		parent::render($view,$data,$return);
	}
	
	public function isAdmin() 
	{
		
		if (Yii::app()->user->isGuest) return false;
		$pos = strpos(Yii::app()->user->userRecord->roles, 'ROLE_ADMIN');
		if ($pos === false) return false;
		return true;
	}
	
	public function isSiteAdmin() 
	{
		if (Yii::app()->user->isGuest) return false;
		$pos = strpos(Yii::app()->user->userRecord->roles, 'ROLE_SITE_ADMIN');
		if ($pos === false) return false;
		return true;
	}
	
}