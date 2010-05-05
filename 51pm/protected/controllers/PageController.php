<?php

class PageController extends _BaseController
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
		
	public function __construct($id,$module=null)
	{
		parent::__construct($id, $module);
		$this->activemenu = '';	
	}
	

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'show'),
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

	/**
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$page = $this->loadPage(); 
		$this->pageTitle = $page->title;
		
		if ($page->pageType == Page::PROJECT_PAGE && $page->projectId > 0)
		{				
			$this->menutype = 'project'; 
			Yii::app()->session['currentProject'] = Project::model()->findbyPk($page->projectId); 
		}
		$this->render('show',array('model'=>$page));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new Page;
		$model->pageType = Page::ACCOUNT_PAGE; 
		$model->displayOrder = 0; 
		if (isset($_GET['type']))
		{
			$model->pageType = 	$_GET['type']; 
			$model->projectId = Yii::app()->session['currentProject']->id;
		}
		
		$this->pageTitle = Yii::t('app', 'create.page'); 
		$this->edit($model);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadPage();
		$this->pageTitle = $model->title;
		$this->edit($model);
	}
	
	protected function edit($model)
	{
		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			$needLog = $model->isNewRecord;
			if($model->save())
			{
				if ($needLog)
				{
					$this->logActivity($model); 						
				}
				$this->redirect(array('show','id'=>$model->id));
				
			}
		}
		if ($model->pageType == Page::PROJECT_PAGE)
		{
				$this->menutype = 'project'; 
		}
		$this->render('create',array('model'=>$model));		
	}

	
	protected function logActivity($page)
	{
		$act = new Activity;
		$act->activityDate =  new CDbExpression('NOW()'); 
		$act->activityType = Activity::TYPE_CREATE_PAGE; 
		$projectName = '';
		if ($page->pageType == Page::PROJECT_PAGE && $page->projectId > 0)
		{	
			$act->projectId = $page->projectId;
			$projectName = Yii::app()->session['currentProject']->projectName;
		}
		$act->userId = $page->createdBy;
		$act->companyId = $page->companyId;
		$desc = array(
			'id'=>$page->id,
			'title'=>$page->title,
			'project'=>$projectName,
		);
		$act->activityDesc = serialize($desc);
		$act->save();		
	}
	
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadPage($_POST['id'])->delete();
			$this->redirect(array('/site/index'));			
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function loadPage($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Page::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
