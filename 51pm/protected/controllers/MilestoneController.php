<?php

class MilestoneController extends _BaseController
{
	const PAGE_SIZE=10;

	public $defaultAction='list';

	public $menutype = 'project';
	
	public $activemenu = 'milestones';	
	
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'expression'=>$this->isAdmin(),
			),			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionShow()
	{
		$m = $this->loadMilestone();
		if (!isset(Yii::app()->session['currentProject'])) 
		{
			$currentProject = Project::model()->findbyPk($m->projectId);
			Yii::app()->session['currentProject'] = $currentProject;
		}
		$this->pageTitle = $m->title;
		$this->render('show',array('model'=>$m));
	}

	public function actionCreate()
	{
		$model=new Milestone;
		if(isset($_POST['Milestone']))
		{

			$model->attributes=$_POST['Milestone'];
			$model->projectId = Yii::app()->session['currentProject']->id;
			if($model->save())
			{
				$this->redirect(array('show','id'=>$model->id));
			}
		
		}
		$this->pageTitle = Yii::t('app', 'milestone.create');
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate()
	{
		$model=$this->loadMilestone();
		if(isset($_POST['Milestone']))
		{
			$model->attributes=$_POST['Milestone'];
			if($model->save())
			{
				$this->redirect(array('show','id'=>$model->id));
			}
		}
		$this->pageTitle = $model->title;
		$this->render('create',array('model'=>$model));
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadMilestone($_POST['id'])->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionList()
	{
		$this->pageTitle = Yii::t('app', 'milestone');
		$readonly = false;
		if (isset(Yii::app()->session['currentProject'])) {
			$projectId = Yii::app()->session['currentProject']->id;		
			$models=Milestone::model()->getProjectMilestones($projectId);
			
			$ticketTally = Ticket::getTicketTallyForMilestones($projectId);
			$openTicketTally = Ticket::getOpenTicketTallyForMilestones(Yii::app()->session['currentProject']);
		}
		else 
		{
			$models=Milestone::model()->getCompanyMilestones(Yii::app()->user->userRecord->companyId);
			$ticketTally = array();
			$openTicketTally = array();
			foreach (Yii::app()->user->userRecord->projects as $p):
				$ticketTally += Ticket::getTicketTallyForMilestones($p->id);
				$openTicketTally += Ticket::getOpenTicketTallyForMilestones($p);
			endforeach;			
			$readonly = true;	
			$this->menutype = 'company'; 
		}
		
		$this->render('list',
			array('models'=>$models, 
				  'ticketTally'=>$ticketTally, 
				  'openTicketTally'=>$openTicketTally,
				  'readonly'=>$readonly,));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadMilestone($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Milestone::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
