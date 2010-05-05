<?php

class ProjectController extends _BaseController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public $menutype = 'project';
	
	public $currentProject; 
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

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
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionShow()
	{
		$this->activemenu = 'overview'; 
		$this->currentProject = $this->loadProject();
		// store current project into session
		Yii::app()->session['currentProject'] = $this->currentProject;
		$this->pageTitle = $this->currentProject->projectName;
		$activities = Activity::model()->getProjectActivities($this->currentProject->id); 		
		$this->render('show',array('project'=>$this->currentProject, 'activities'=>$activities,));
	}

	public function actionCreate()
	{
		$model=new Project;
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if($model->save())
			{
				Yii::app()->session['jitMessage'] = 'Your project was successfully created! Now invite some members to get started.'; 
				$this->redirect(array('/user/list'));
			}
		}

		$this->activemenu = '';
		$this->menutype = 'company';
		$this->pageTitle = Yii::t('app', 'create.project'); 
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadProject();
		if(isset($_POST['Project']))
		{
			$model->attributes=$_POST['Project'];
			if (isset($_POST['generateActivity']))
			{
				$model->attributes['generateActivity'] = 1;
			}
			else 
			{
				$model->attributes['generateActivity'] = 0;
			}
			if($model->save())
			{
				$this->redirect(array('show','id'=>$model->id));
			}
		}
		Yii::log('good to go', 'error');
		if (is_null($model->ticketTypes))
		{
			$model->ticketTypes = Project::getDfltTypesText();
		}
		if (is_null($model->ticketStatuses))
		{
			$model->ticketStatuses = Project::getDfltStatusesText();
		}
		if (is_null($model->openStates))
		{
			$model->openStates = Project::getDfltOpenStatusesText();
		}
		if (is_null($model->closeStates))
		{
			$model->closeStates = Project::getDfltCloseStatusesText();
		}
		$this->activemenu = '';
		$this->pageTitle = $model->projectName;
		$this->render('create',array('model'=>$model));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadProject()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
		
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadProject($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Project::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
