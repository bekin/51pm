<?php
class PageList extends CWidget 
{
	public $type = 0;
	
	public function getPages() 
	{
		if ($this->type == Page::ACCOUNT_PAGE)
			return Page::model()->findPages(Yii::app()->user->userRecord->companyId, $this->type);
		else
			return Page::model()->findProjectPages(Yii::app()->user->userRecord->companyId, Yii::app()->session['currentProject']->id);
		
	}
	
	public function run()
	{
		$this->render('pageList');
	}
}