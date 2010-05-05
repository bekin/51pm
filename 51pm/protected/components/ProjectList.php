<?php
class ProjectList extends CWidget 
{
	public function getProjects() 
	{
		return Project::model()->findProjects(Yii::app()->user->userRecord->companyId); 
	}
	
	public function run()
	{
		$this->render('projectList');
	}
}