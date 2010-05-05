<?php

class MessageController extends _BaseController
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
	
	public $activemenu = 'messages';	
	
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
				'actions'=>array('create','update', 'show', 'list', 'comment'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'expression'=>$this->isAdmin(),				
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionShow()
	{
		$msg = $this->loadShortMsg(); 
		if ($msg->msgId) 
		{
			throw new CHttpException(404, 'The specified message could not be found.');
		}
		$comments = ShortMsg::model()->getComments($msg->id);
		$this->pageTitle = $msg->title;
		$this->currentProject = $this->loadProject($msg->projectId);
		Yii::app()->session['currentProject'] = $this->currentProject;
		$this->render('show',array('model'=>$msg, 'comments'=>$comments,));
	}

	public function actionCreate()
	{
		$model=new ShortMsg;
		if(isset($_POST['Message']))
		{
			$model->attributes=$_POST['Message'];
			$model->projectId = $_GET['project'];
			$model->projectName =  Yii::app()->session['currentProject']->projectName;
			if($model->save()) 
			{				
				if (isset($_POST['notify'])) 
				{
					@$this->mailMessage($model); 
				}
				$this->redirect(array('show','id'=>$model->id));
				return;	
			}
		}
		$this->pageTitle = Yii::t('app', 'new.message');
		$this->currentProject = $this->loadProject(); 
		$this->render('create',array('model'=>$model));
	}

	public function actionComment()
	{		
		if(isset($_POST['Comment']))
		{
			$model=new ShortMsg;
			$model->attributes=$_POST['Comment'];
			$model->projectName =  Yii::app()->session['currentProject']->projectName;
			if($model->saveComment()) 
			{				
				if (isset($_POST['notify'])) 
				{
					$parent = ShortMsg::model()->findbyPk($model->msgId); 
					$model->title = Yii::t('app', 'reply') . ': ' . $parent->title; 
					@$this->mailMessage($model); 
				}
				
				$this->redirect(array('show','id'=>$model->msgId));
				return;	
			}
		}
		
	}
	
	public function actionUpdate()
	{
		$model=$this->loadShortMsg();
		if(isset($_POST['Message']))
		{
			$model->attributes=$_POST['Message'];
			if($model->save()) 
			{				
				if (isset($_POST['notify']))
				{
					@$this->mailMessage($model); 
				}
				$this->redirect(array('show','id'=>$model->id));
				return;	
			}
		}
		
		$this->pageTitle = $model->title; 
		$this->currentProject = $this->loadProject(); 
		$this->render('create',array('model'=>$model));
	}

	private function mailMessage($msg)
	{		
		$project = Project::model()->findbyPk($msg->projectId);
		$to = array();
		foreach ($project->users as $u)
		{
			$to[] = $u->email; 
		}
		
		Yii::trace('sending message to: ' . implode(',', $to));
		MailEngine::mailnow(
		  array('name'=>Yii::app()->user->userRecord->userName, 'email'=>Yii::app()->user->userRecord->email, ), 
		      $to, $msg->title, $msg->msg); 		
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = new ShortMsg(); 
			$model->deleteMessage($_POST['id']); 
			$this->redirect(array('list', 'project'=>$_POST['project']));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$this->pageTitle = Yii::t('app', 'messages');
		$model = new ShortMsg; 
		if (isset($_GET['project']))
		{
			$messages = $model->getProjectMessages($_GET['project']);
		}
		else 
		{
			$messages = $model->getMessages(Yii::app()->user->userRecord->companyId);
			$this->menutype = 'company'; 
		}
		$this->render('list',
			array(
			'messages'=>$messages,
		));
	}

	public function loadShortMsg($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=ShortMsg::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	public function loadProject($id=null)
	{			
		return Project::model()->findbyPk($id!==null ? $id : $_GET['project']);	
	}
	
}
