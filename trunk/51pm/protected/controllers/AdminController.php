<?php

class AdminController extends _BaseController
{
	public $layout = 'admin'; 
	public $defaultAction='companies';
	const PAGE_SIZE = 20; 
	
	public function filters()
	{
		return array(
			'accessControl', 
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('companies', 'createCompany', 'editCompany','deleteCompany', 'users', 'deleteUser', 'createUser', 'editUser'),
				'expression'=>$this->isSiteAdmin(),				
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	
	public function actionCompanies()
	{
		$this->pageTitle = Yii::t('app', 'companies');
		$needCount = true;
		$paging = new ResultPage(); 		
		if (isset($_POST['page'])) 
		{
			$paging->currentPage = $_POST['page'];
		}
		if (isset($_POST['count']))
		{
			$paging->itemCount = $_POST['count'];
			$needCount=false;
		}
		if (isset($_POST['sort']))
		{
			$paging->sort = $_POST['sort']; 	
		}
		else 
		{
			$paging->sort = 'companyName';
		}
		if (isset($_POST['ascdesc']))
		{
			$paging->ascdesc = $_POST['ascdesc']; 	
		}
		else 
		{
			$paging->ascdesc = 'desc';
		}
		
		$paging->pageSize=self::PAGE_SIZE;
				
		$criteria=new CDbCriteria;
		if ($needCount) 
		{
			$paging->itemCount=Company::model()->count($criteria);
		}
		
		$paging->applyLimit($criteria);
		$companies=Company::model()->findAll($criteria);
		$paging->setResults($companies);	
		
		$this->render('index',array(
			'paging'=>$paging,
		));		
		
	}

	public function actionCreateCompany() 
	{
		$this->actionEditCompany();
	}

	public function actionEditCompany() 
	{
		if(isset($_GET['id']))
		{
			$company=Company::model()->findbyPk($_GET['id']);
		}
		else 
		{
			$company = new Company; 
		}
		
		if (isset($_POST['Company']))
		{
				$company->attributes=$_POST['Company'];
				if($company->save()) 
				{
					$this->redirect(array('companies'));
				}			
		}
		
		$this->pageTitle = Yii::t('app', 'edit.company');
		$this->render('editCompany',array(
			'company'=>$company,
		));		
		
	}
	
	public function actionDeleteCompany()
	{
		if (isset($_POST['id']))
		{
			Company::model()->deleteCompany($_POST['id']); 		
		}
		
		$this->redirect(array('companies'));
	}

	public function actionUsers() 
	{
		$this->pageTitle = Yii::t('app', 'users');
		$needCount = true;
		$paging = new ResultPage(); 		
		if (isset($_POST['page'])) 
		{
			$paging->currentPage = $_POST['page'];
		}
		if (isset($_POST['count']))
		{
			$paging->itemCount = $_POST['count'];
			$needCount=false;
		}
		if (isset($_POST['sort']))
		{
			$paging->sort = $_POST['sort']; 	
		}
		else 
		{
			$paging->sort = 'userId';
		}
		if (isset($_POST['ascdesc']))
		{
			$paging->ascdesc = $_POST['ascdesc']; 	
		}
		else 
		{
			$paging->ascdesc = 'desc';
		}
		
		$paging->pageSize=self::PAGE_SIZE;
				
		$criteria=new CDbCriteria;
		$criteria->addCondition('companyId=' . $_GET['company']); 
		$criteria->addCondition('active=1'); 
		if ($needCount) 
		{
			$paging->itemCount=User::model()->count($criteria);
		}
		
		$paging->applyLimit($criteria);
		$users=User::model()->findAll($criteria);
		$paging->setResults($users);	
		
		$this->render('users',array(
			'paging'=>$paging,
		));			
	}
	
	
	public function actionCreateUser() 
	{
		$this->actionEditUser();
	}

	public function actionEditUser() 
	{		
		if(isset($_GET['id']))
		{
			$user=User::model()->findbyPk($_GET['id']);
		}
		else 
		{
			$user = new User;
			$user->companyId = $_GET['company'];
			$tp = new Text_Password();
			$user->password =  $tp->create(8, 'unpronounceable');
		}
		
		$company = Company::model()->findbyPk($user->companyId);
		if (isset($_POST['User']))
		{
				$user->attributes=$_POST['User'];
				if (isset($_POST['role']))
					$user->roles = implode(',', $_POST['role']);
				else
					$user->roles = null;
					
				$plainpwd = $user->password;

				try {	
					$user->save(); 
					$this->redirect(array('users', 'company'=>$user->companyId,));
				}		
				catch (Exception $e) {
					if ($user->isNewRecord)
					{
						$user->password = $plainpwd; 
					}
					$user->addError('email', 'duplicate email');
				}	
		}
		
		$this->pageTitle = Yii::t('app', 'edit.user');
		$this->render('editUser',array(
			'user'=>$user,
			'company'=>$company,
		));		
		
	}
	
	public function actionDeleteUser()
	{
		if (isset($_POST['id']))
		{
			$user=User::model()->findbyPk($_POST['id']);
			$user->active = -1;
			$user->save(); 
			 		
			$this->redirect(array('users', 'company'=>$user->companyId,));
		}				
	}
	
}