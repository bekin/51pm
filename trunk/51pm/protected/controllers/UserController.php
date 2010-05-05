<?php

class UserController extends _BaseController
{
	const PAGE_SIZE=10;
	const default_pwd = 'lovepm88';

	public $menutype = 'none';

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
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

	public function accessRules()
	{
		return array(
			array('allow', 
					'actions'=>array('accept','test',),
					'users'=>array('*'),
			),
			array('allow', 
					'actions'=>array('list', 'invite','activity','delete', 'delinvite', 'reinvite', 'toggleperm', 'membership',),
					'expression'=>$this->isAdmin(),	
			),
			array('allow', 
					'actions'=>array('create','update',),
					'expression'=>$this->isSiteAdmin(),	
			),
			array('allow', 
				'actions'=>array('myprofile','show','myactivities',),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
					'users'=>array('*'),
			),
		);
	}
	
	public function actionMyprofile()
	{
		$user = Yii::app()->user->userRecord;
		$this->layout = 'user';
		
		if (Yii::app()->request->isPostRequest)
		{
			$needsave = $this->processAvatar($user); 
			$user->attributes=$_POST['User'];
			if (!empty($_POST['chgpwd']) && $_POST['chgpwd'] == $_POST['rechgpwd'])
			{
				$user->password = md5($_POST['chgpwd']);
			}
			$user->save();
			//Yii::app()->user->userRecord = $user;
		}
		
		$this->pageTitle = Yii::t('app', 'my.profile'); 
		$this->render('myprofile',array('user'=>$user));
	}
	
	private function showUserActivities($user)
	{
		$this->layout = 'user';
		$this->pageTitle = Yii::t('app', 'my.activities'); 
		$activities = Activity::model()->getUserActivities($user->userId); 
		$this->render('useractivities',array('user'=>$user, 'activities'=>$activities,));		
	}

	public function actionMyactivities() 
	{
		$user = Yii::app()->user->userRecord;
		$this->showUserActivities($user); 
	}
	
	public function actionShow()
	{
		$this->showUserActivities(User::model()->findbyPk($_GET['id']));
	}

	private function processAvatar($user)
	{
		$avatar = $_FILES['avatar'];
		$contentType = $avatar['type']; 
		$isimage = strpos($contentType, 'image'); 
		if ($isimage === false)
		{			
			Yii::log('Invalid Image file', 'error'); 
			return false;
		}

		Yii::log('Saving avatar', 'error'); 
		if (!empty($user->avatar))
		{
				@unlink( Yii::app()->params['avatarRoot'] . DIRECTORY_SEPARATOR . $user->avatar); 
		}

		$dates = getdate();						
		$file_path = $dates['year']. DIRECTORY_SEPARATOR.
									$dates['mon']. DIRECTORY_SEPARATOR.
									$dates['mday']; 
		$target_path = Yii::app()->params['avatarRoot'] . DIRECTORY_SEPARATOR. $file_path;
									
		chdir(dirname(Yii::app()->basePath));			
		Utils::recursive_mkdir($target_path);							

		$uuid = Utils::uuid();
		$name = basename( $avatar['name']); 		
		$ext = Utils::fileExt($name); 		
		$target_path = $target_path	. DIRECTORY_SEPARATOR.	$uuid; 
		$location = $file_path . DIRECTORY_SEPARATOR . $uuid;			
		if (!empty($ext)) 
		{
			$target_path = $target_path	.	'.' . $ext;
			$location = $location . '.' . $ext;
		}
		move_uploaded_file($avatar['tmp_name'], $target_path);						
		new Img2Thumb($target_path, 40, 40, $target_path, 0, 255, 255, 255);
								
		
		$user->avatar = $location; 
		
		return true; 		
	}

	public function actionCreate()
	{
		$model=new User;
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			//$pwd = $model->password;
			//$model->password = md5($pwd);

			if($model->save())
			$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadUser();
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->password!=''){
				$pwd = $model->password;
				$model->password = md5($pwd);
			}
			$model->save();
			//$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('update',array('model'=>$model));
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			User::model()->findbyPk($_POST['userid'])->delete();
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	
	public function actionDelinvite()
	{
		User::model()->deleteAllByAttributes(array('active'=>0, 'email'=>$_POST['email']));
		Invite::model()->deleteAllByAttributes(array('email'=>$_POST['email']));		
	}
	
	public function actionMembership()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$userId = $_POST['userId'];
			$companyId = $_POST['companyId'];
			$members = $_POST['member']; 
			if (Yii::app()->user->userRecord->companyId == $companyId)
			{
				ProjectCommittee::model()->deleteMembership($userId, $companyId);
				foreach ($members as $m):
					$pc = new ProjectCommittee;
					$pc->projectId = $m; 
					$pc->userId = $userId; 
					$pc->companyId = $companyId;
					$pc->save();
				endforeach;
			}
		}
		else 
		throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionToggleperm()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$user = User::model()->findbyPk($_POST['userid']);
			if (empty($user->roles)) 
			{
				$user->roles = 'ROLE_ADMIN'; 
			}
			else 
			{
				$roles = explode(',', $user->roles); 
				$newroles = array();
				
				$in_it = false;
				
				foreach ($roles as $r) 
				{
					if ($r == 'ROLE_ADMIN')
					{
						$in_it = true;
					}
					else 
					{
						$newroles[] = $r;
					}
				}
				
				if (!$in_it)
				{
					$newroles[] = 'ROLE_ADMIN'; 
				}
				
				$user->roles = implode(',', $newroles);
			}
			$user->save();
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;
		$criteria->condition = 'active >= 0 and companyId = ' . Yii::app()->user->userRecord->companyId; 
		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('User');
		$sort->applyOrder($criteria);

		$models=User::model()->with('committees')->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	public function actionInvite()
	{
		$form=new InviteForm;
		$dupe = array();
		$failToSend = array();
		if(isset($_POST['InviteForm'])){
			$form->attributes=$_POST['InviteForm'];
			if($form->validate()){
				$mails = explode(' ',$form->emails);
				$asAdmin = ($form->privilege == '1');
				 
				foreach ($mails as $email){
					if(trim($email)!=''){
					   $_model=User::model()->findByAttributes(array('email'=>$email));
					  
						if($_model===null)
						{
							if (!$this->inviteByMail(trim($email),$form->message, $asAdmin)) 
							{
							 $failToSend[] = $email; 
							}
						}
						else
						{
							$dupe[] = $email;
						}
					}
				}
			}
		}

        $emsg = array(); 
		if( count($dupe) > 0 )
		{		  
			$emsg[] = Yii::t('app', 'invite.dupe', array('{emails}'=>implode(', ', $dupe))); 
		}
        if( count($failToSend) > 0 )
        {         
            $emsg[] = Yii::t('app', 'invite.failtosend', array('{emails}'=>implode(', ', $failToSend))); 
        }
        if (count($emsg) > 0) {
            Growl::setPersisted();
            Growl::setMessage(implode('<br>', $emsg)); 
        }
		else{
            Growl::setMessageKey('invite.success'); 
		}

		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=User::model()->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	public function actionReinvite()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model=$this->loadInvite();
			$now = date('Y-m-d H:i:s',time());
			
			$email = $model->email;
			$subject = Yii::t('app', 'account.invite', array('{app}'=>Yii::app()->name, ));			
			$message = $model->message;


            if(MailEngine::mailnow(array('name'=>Yii::app()->params['fromname'], 'email'=>Yii::app()->params['from'], ), 
                array($email),$subject,$message)){
				$model->invitedOn = $now;
				$model->save();		

				$user=User::model()->findByAttributes(array('email'=>$email));
				$user->lastLogin = $now;
				$user->save();
			}	
		}
	}
	
	public function actionAccept(){
		$model = User::model()->findByAttributes(array('uuid'=>$_GET['u'])); 
		if($model != null)
		{
			$model->active = 1;
			$model->save();
			Invite::model()->deleteAllByAttributes(array('email'=>$model->email,));

			// auto login
			$identity=new UserIdentity($model->email, default_pwd);
			$identity->authenticate();
			
			
			if ($identity->errorCode == UserIdentity::ERROR_NONE)
			{
				$duration= 3600*24*30; // 30 days
				Yii::app()->user->login($identity,$duration);
				$this->redirect(array('/user/myprofile', 'activation'=>'now'));
			}
			else 
			{
				die('Error: ' . $identity->errorCode );				
			}
				
		}
		else
			throw new CHttpException(404,'The requested page does not exist.');	
	}
	
	public function actionActivity()
	{
		$model=$this->loadUser();
		//do something to load the activities
		$this->render('activity',array('model'=>$model));
	}

	protected function createInviteMessage($subject, $from, $content, $url)
	{
		if (LocaleManager::isEnglish())
		{
			$tmplfile = Yii::app()->basePath.'/views/templates/email_invitation.tmp';
		}
		else 
		{
			$tmplfile = Yii::app()->basePath.'/views/templates/' . LocaleManager::getCurrentLocale() . '/email_invitation.tmp';	
		}
		
		$message = file_get_contents($tmplfile);
		
		// substitude
		$message = str_replace("[subject]", $subject, $message);
		$message = str_replace("[appname]", Yii::app()->name, $message);
		$message = str_replace("[from]", $from, $message);
		$message = str_replace("[content]", $content, $message);
		$message = str_replace("[url]", $url, $message);
		
		return $message;		
	}
		
	protected function inviteByMail($email, $message, $asAdmin){
		$subject = Yii::t('app', 'account.invite', array('{app}'=>Yii::app()->name, ));
		$uuid = Utils::uuid();
		$url = Utils::http_link_base() . $this->createUrl('/user/accept', array('u'=>$uuid,));
		
		// clean up the user message
		$message = htmlspecialchars($message, ENT_QUOTES);
		$message = nl2br($message);
			
		$message = $this->createInviteMessage( $subject, Yii::app()->user->userRecord->userName, $message, $url);
		if(MailEngine::mailnow(array('name'=>Yii::app()->params['fromname'], 'email'=>Yii::app()->params['from'], ), array($email),$subject,$message)){
			$invited_user = new User;
			$invited_user->password = default_pwd;
			$invited_user->email = $email;			
			$invited_user->userName = preg_replace('/@(\w+)\.([a-z\.]{2,6})$/',' (at $1)',$email);
			$invited_user->uuid = $uuid;

			$now = date('Y-m-d H:i:s',time());
			$uid = Yii::app()->user->userRecord->userId;
			$invited_user->lastLogin = $now;
			$invited_user->createdOn = $now;
			$invited_user->createdBy = $uid;
			$invited_user->modifiedOn = $now;
			$invited_user->modifiedBy = $uid;
			$invited_user->version = 1;
			$invited_user->companyId = Yii::app()->user->userRecord->companyId;
            if ($asAdmin) 
            {
                $invited_user->roles = 'ROLE_ADMIN';
            }
            $invited_user->active = 0;
			$invited_user->save();

			//also create the invite record
			$invite = new Invite;
			$invite->email = $email;
			$invite->subject = $subject;
			$invite->message = $message;
			$invite->invitedOn = $now;
			$invite->uuid = $uuid;
			$invite->companyId = Yii::app()->user->userRecord->companyId;
			$invite->save();
			
			return true;
		}
		else {
		  return false;
		}
	}


	public function loadUser($userid=null)
	{
		if($this->_model===null)
		{
			if($userid!==null || isset($_GET['userid']))
			$this->_model=User::model()->findbyPk($userid!==null ? $userid : $_GET['userid']);
			if($this->_model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	public function loadInvite($email=null)
	{
		if($this->_model===null)
		{
			if($email!==null || isset($_GET['email']))
			$this->_model = Invite::model()->find('email=:email', array(':email'=>$email!==null ? $email : $_GET['email']));
			if($this->_model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
