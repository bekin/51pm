<?php

class SiteController extends _BaseController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image
			// this is used by the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xEBF4FB,
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// It seems that the index action is beyond control of the "accessRules", so here we go ....
		if (Yii::app()->user->isGuest) 
		{
			$this->actionLogin();
		}
		else 
		{
			Yii::app()->session['currentProject'] = null;
			$this->pageTitle = Yii::t('app', 'site.title'); 
			$activities = Activity::model()->getCompanyActivities(Yii::app()->user->userRecord->companyId); 
			$this->render('index', array('activities'=>$activities,));
		}
	}

	public function actionError()
	{
		$this->render('error');
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$form=new LoginForm;
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$form->attributes=$_POST['LoginForm'];
			// validate user input and redirect to previous page if valid
			if($form->validate())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->pageTitle =  Yii::t('app', 'please.login'); 
		$this->renderPartial('login',array('form'=>$form));
	}

	/**
	 * Logout the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('login'));
	}
	
	public function actionPwdreset()
	{
		// get the user record by email
		if (isset($_POST['email'])) {
			$record=user::model()->findByAttributes(array('email'=>$_POST['email']));	
			if ($record != null) 
			{
					$tp = new Text_Password();
					$pwd =  $tp->create(8, 'unpronounceable');

					$record->password = md5($pwd);
					$record->save();
					
					// email pwd
					$this->mailPwd($pwd, $record->email, $record->userName);
			}
		}
		$this->actionLogin();
	}
	
	private function mailPwd($pwd, $email, $userName)
	{
		$subject = Yii::t('app', 'password.reset'); 
		$msg = Yii::t('app', 'password.reset.msg', array('name'=>$userName, 'pwd'=>$pwd)); 

        MailEngine::mailnow(
          array('name'=>Yii::app()->params['adminName'], 'email'=>Yii::app()->params['adminEmail'], ), 
              array($email), $subject,$msg);         
		
	}
	
	public function actionRss()
	{
	   $id = base64_decode ($_GET['stream']);
	   $name = base64_decode ($_GET['company']); 
	   $company = Company::model()->findbyPk($id);
	   if ($name != $company->companyName)
	   {
	       throw new CHttpException(404,'The requested page does not exist.');
	   } 
	   if (isset($_GET['project']))
	   {
	       $pid = base64_decode ($_GET['project']);
	       $activities = Activity::model()->getProjectActivities($pid);
	   }
	   else 
	   {
            $activities = Activity::model()->getCompanyActivities($id);
       } 
       $this->renderPartial('rss2', array('activities'=>$activities, 'company'=>$company));
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('login','logout', 'contact', 'pwdreset', 'rss'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			
		);
	}
		
}